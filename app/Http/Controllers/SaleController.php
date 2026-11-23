<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailSale;
use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('customer', 'user')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return view('management.sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $medicines = Medicine::with('category')->orderBy('name')->get();

        return view('management.sales.create', compact('customers', 'medicines'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'   => 'required|integer|exists:customers,id',
            'date'          => 'required|date_format:Y-m-d|after_or_equal:2000-01-01|before_or_equal:today',
            'medicine_id'   => 'required|array|min:1',
            'medicine_id.*' => 'required|integer|distinct|exists:medicines,id',
            'amount'        => 'required|array|size:'.$request->collect('medicine_id')->count(),
            'amount.*'      => 'required|integer|min:1',
        ], [
            'customer_id'   => 'Selecciona un cliente de la lista.',
            'date'          => 'Indica una fecha válida, desde el año 2000 y no posterior a hoy.',
            'medicine_id.*' => 'El detalle debe tener al menos un medicamento disponible, sin repetir.',
            'amount.*'      => 'Indica la cantidad de cada medicamento con un número entero mayor que cero.',
        ]);

        $lines = array_combine(
            array_map('intval', $validated['medicine_id']),
            array_map('intval', $validated['amount']),
        );

        return DB::transaction(function () use ($request, $validated, $lines) {
            $medicines = Medicine::lockForUpdate()->findOrFail(array_keys($lines))->keyBy('id');
            $details = collect();

            foreach ($lines as $id => $amount) {
                $medicine = $medicines[$id];

                $detail = new DetailSale([
                    'amount'     => $amount,
                    'unit_price' => $medicine->price,
                ]);
                $detail->medicine()->associate($medicine);

                if (! $detail->validateStock()) {
                    return back()
                        ->withErrors(['medicine_id' => "No hay stock suficiente de {$medicine->name} (stock disponible: {$medicine->stock})."])
                        ->withInput();
                }

                $detail->subtotal = $detail->calculateSubtotal();
                $details->push($detail);
            }

            if (round($details->sum('subtotal'), 2) > 99999999.99) {
                return back()
                    ->withErrors(['medicine_id' => 'El total de la venta supera el máximo permitido (S/ 99,999,999.99).'])
                    ->withInput();
            }

            $sale = Sale::create([
                'user_id'     => $request->user()->id,
                'customer_id' => $validated['customer_id'],
                'date'        => $validated['date'],
                'total'       => 0,
                'igv'         => 0,
            ]);

            foreach ($details as $detail) {
                $sale->details()->save($detail);
                $detail->medicine->updateStock(-$detail->amount);
            }

            $sale->calculateTotal();

            return to_route('sales.show', $sale->id)
                ->with('ok', 'Venta registrada correctamente.');
        });
    }

    public function show(Sale $sale)
    {
        $sale->load('customer', 'user', 'details.medicine');

        return view('management.sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale = Sale::lockForUpdate()->findOrFail($sale->id);

            foreach ($sale->details()->with('medicine')->orderBy('medicine_id')->get() as $detail) {
                $detail->medicine->updateStock($detail->amount);
            }

            $sale->delete();
        });

        return to_route('sales.index')
            ->with('ok', 'Venta anulada correctamente.');
    }
}
