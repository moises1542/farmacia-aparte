<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();

        return view('management.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('management.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|max:50',
            'dni'   => 'required|digits:8|unique:customers',
            'phone' => 'nullable|digits:9',
            'age'   => 'nullable|integer|min:0|max:120',
        ]);

        $customer = Customer::create($validated);

        if ($request->boolean('sale')) {
            return to_route('sales.create')
                ->withInput(['customer_id' => $customer->id]);
        }

        return to_route('customers.index')
            ->with('ok', 'Cliente registrado correctamente.');
    }

    public function show(Customer $customer)
    {
        $sales = $customer->sales()
            ->with('user')
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return view('management.customers.show', compact('customer', 'sales'));
    }

    public function edit(Customer $customer)
    {
        return view('management.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'  => 'required|max:50',
            'dni'   => [
                'required',
                'digits:8',
                Rule::unique('customers', 'dni')->ignore($customer->id),
            ],
            'phone' => 'nullable|digits:9',
            'age'   => 'nullable|integer|min:0|max:120',
        ]);

        $customer->update($validated);

        return to_route('customers.index')
            ->with('ok', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->sales()->exists()) {
            return to_route('customers.index')
                ->with('error', 'No se puede eliminar: tiene ventas asociadas.');
        }

        $customer->delete();

        return to_route('customers.index')
            ->with('ok', 'Cliente eliminado correctamente.');
    }
}
