<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailSale;
use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function index()
    {
        $from = now()->startOfMonth()->toDateString();
        $to = now()->toDateString();

        $salesTotal = Sale::whereDate('date', '>=', $from)->whereDate('date', '<=', $to)->sum('total');
        $unitsSold = DetailSale::join('sales', 'sales.id', '=', 'detail_sales.sale_id')
            ->whereDate('sales.date', '>=', $from)
            ->whereDate('sales.date', '<=', $to)
            ->sum('detail_sales.amount');
        $stockValue = Medicine::sum(DB::raw('price * stock'));
        $customersCount = Sale::whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->distinct()
            ->count('customer_id');

        return view('principal.reports.index', compact(
            'salesTotal', 'unitsSold', 'stockValue', 'customersCount',
        ));
    }

    public function sales(Request $request)
    {
        [$from, $to] = $this->period($request, 'sales');

        $sales = $this->salesData($from, $to);
        $total = $sales->sum('total');
        $igv = $sales->sum('igv');
        $average = $sales->isEmpty() ? 0 : $total / $sales->count();

        return view('principal.reports.sales', compact('from', 'to', 'sales', 'total', 'igv', 'average'));
    }

    public function medicines(Request $request)
    {
        [$from, $to] = $this->period($request, 'medicines');

        $medicines = $this->medicinesData($from, $to);
        $units = $medicines->sum('units');
        $total = $medicines->sum('total');
        $maxUnits = (int) $medicines->max('units');

        return view('principal.reports.medicines', compact('from', 'to', 'medicines', 'units', 'total', 'maxUnits'));
    }

    public function stock()
    {
        $medicines = $this->stockData();
        $units = $medicines->sum('stock');
        $value = $medicines->sum('value');
        $lowStock = $medicines->where('stock', '<=', Medicine::LOW_STOCK)->count();
        $outOfStock = $medicines->where('stock', 0)->count();

        return view('principal.reports.stock', compact('medicines', 'units', 'value', 'lowStock', 'outOfStock'));
    }

    public function customers(Request $request)
    {
        [$from, $to] = $this->period($request, 'customers');

        $customers = $this->customersData($from, $to);
        $purchases = $customers->sum('purchases');
        $total = $customers->sum('total');

        return view('principal.reports.customers', compact('from', 'to', 'customers', 'purchases', 'total'));
    }

    public function export(Request $request, $report)
    {
        $names = [
            'sales'     => 'ventas',
            'medicines' => 'medicamentos',
            'stock'     => 'stock',
            'customers' => 'clientes',
        ];

        if (! isset($names[$report])) {
            abort(404);
        }

        if ($report === 'stock') {
            $rows = $this->stockRows();
        } else {
            [$from, $to] = $this->period($request, $report);

            $rows = match ($report) {
                'sales'     => $this->salesRows($from, $to),
                'medicines' => $this->medicinesRows($from, $to),
                'customers' => $this->customersRows($from, $to),
            };
        }

        return response()->streamDownload(function () use ($rows) {
            $file = fopen('php://output', 'w');

            fwrite($file, "\xEF\xBB\xBF");

            foreach ($rows as $row) {
                fputcsv($file, $row, ';', '"', '');
            }

            fclose($file);
        }, 'reporte-'.$names[$report].'-'.now()->format('Ymd').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function period(Request $request, $report)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $request->merge([
            'from' => is_string($from) ? $from : now()->startOfMonth()->toDateString(),
            'to'   => is_string($to) ? $to : now()->toDateString(),
        ]);

        try {
            $validated = $request->validate([
                'from' => 'date_format:Y-m-d',
                'to'   => 'date_format:Y-m-d',
            ], [
                'from.date_format' => 'La fecha inicial no es válida.',
                'to.date_format'   => 'La fecha final no es válida.',
            ]);

            if ($validated['to'] < $validated['from']) {
                throw ValidationException::withMessages([
                    'to' => 'La fecha final debe ser igual o posterior a la inicial.',
                ]);
            }
        } catch (ValidationException $exception) {
            throw $exception->redirectTo(route('reports.'.$report));
        }

        return [$validated['from'], $validated['to']];
    }

    private function salesData($from, $to)
    {
        return Sale::with(['customer', 'user'])
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->orderBy('date')
            ->orderBy('id')
            ->get();
    }

    private function medicinesData($from, $to)
    {
        return Medicine::join('categories', 'categories.id', '=', 'medicines.category_id')
            ->join('detail_sales', 'detail_sales.medicine_id', '=', 'medicines.id')
            ->join('sales', 'sales.id', '=', 'detail_sales.sale_id')
            ->whereDate('sales.date', '>=', $from)
            ->whereDate('sales.date', '<=', $to)
            ->groupBy('medicines.id', 'medicines.name', 'categories.name')
            ->select('medicines.id', 'medicines.name', 'categories.name as category_name')
            ->selectRaw('SUM(detail_sales.amount) as units, SUM(detail_sales.subtotal) as total')
            ->orderByDesc('units')
            ->orderBy('medicines.name')
            ->get();
    }

    private function stockData()
    {
        return Medicine::with('category')
            ->select('medicines.*')
            ->selectRaw('price * stock as value')
            ->orderBy('stock')
            ->orderBy('name')
            ->get();
    }

    private function customersData($from, $to)
    {
        return Customer::join('sales', 'sales.customer_id', '=', 'customers.id')
            ->whereDate('sales.date', '>=', $from)
            ->whereDate('sales.date', '<=', $to)
            ->groupBy('customers.id', 'customers.name', 'customers.dni')
            ->select('customers.id', 'customers.name', 'customers.dni')
            ->selectRaw('COUNT(sales.id) as purchases, SUM(sales.total) as total, MAX(sales.date) as last_purchase')
            ->withCasts(['last_purchase' => 'date'])
            ->orderByDesc('total')
            ->orderBy('customers.name')
            ->get();
    }

    private function salesRows($from, $to)
    {
        return $this->salesData($from, $to)
            ->map(fn ($sale) => [
                str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                $sale->date->format('d/m/Y'),
                $this->text($sale->customer->name),
                $this->text($sale->user->name),
                number_format($sale->total, 2, '.', ''),
            ])
            ->prepend(['Ticket', 'Fecha', 'Cliente', 'Vendedor', 'Total']);
    }

    private function medicinesRows($from, $to)
    {
        return $this->medicinesData($from, $to)
            ->map(fn ($medicine) => [
                $this->text($medicine->name),
                $this->text($medicine->category_name),
                $medicine->units,
                number_format($medicine->total, 2, '.', ''),
            ])
            ->prepend(['Medicamento', 'Categoría', 'Unidades', 'Importe']);
    }

    private function stockRows()
    {
        return $this->stockData()
            ->map(fn ($medicine) => [
                $this->text($medicine->name),
                $this->text($medicine->category->name),
                number_format($medicine->price, 2, '.', ''),
                $medicine->stock,
                number_format($medicine->value, 2, '.', ''),
                $this->status($medicine),
            ])
            ->prepend(['Medicamento', 'Categoría', 'Precio', 'Stock', 'Valor', 'Estado']);
    }

    private function customersRows($from, $to)
    {
        return $this->customersData($from, $to)
            ->map(fn ($customer) => [
                $this->text($customer->name),
                $this->text($customer->dni),
                $customer->purchases,
                number_format($customer->total, 2, '.', ''),
                $customer->last_purchase->format('d/m/Y'),
            ])
            ->prepend(['Cliente', 'DNI', 'Compras', 'Total comprado', 'Última compra']);
    }

    private function status($medicine)
    {
        if ($medicine->stock == 0) {
            return 'Agotado';
        }

        return $medicine->stock <= Medicine::LOW_STOCK ? 'Stock bajo' : 'Disponible';
    }

    private function text($value)
    {
        return in_array(substr(ltrim($value), 0, 1), ['=', '+', '-', '@'], true) ? "'".$value : $value;
    }
}
