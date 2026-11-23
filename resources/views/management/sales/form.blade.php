<div class="row g-3 mb-3">
    <div class="col-sm-8 col-lg-12 col-xl-8">
        <label for="customer_id" class="form-label">Cliente</label>
        <select name="customer_id" id="customer_id"
            class="form-select @error('customer_id') is-invalid @enderror">
            <option value="">Selecciona un cliente</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}"
                    @selected(old('customer_id') == $customer->id)>
                    {{ $customer->name }} · DNI {{ $customer->dni }}
                </option>
            @endforeach
        </select>
        @error('customer_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <a href="{{ route('customers.create', ['sale' => 1]) }}" id="saleCustomer"
            class="d-inline-block small text-secondary mt-1">
            Registrar cliente nuevo
        </a>
    </div>

    <div class="col-sm-4 col-lg-12 col-xl-4">
        <label for="date" class="form-label">Fecha</label>
        <input type="date" name="date" id="date" min="2000-01-01" max="{{ now()->toDateString() }}"
            class="form-control @error('date') is-invalid @enderror"
            value="{{ is_string(old('date')) ? old('date') : now()->toDateString() }}">
        @error('date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@foreach (['medicine_id', 'medicine_id.*', 'amount', 'amount.*'] as $field)
    @error($field)
        <div class="alert alert-danger py-2" role="alert">{{ $message }}</div>
    @enderror
@endforeach

<div class="table-responsive">
    <table class="table table-sm small align-middle mb-0">
        <thead>
            <tr>
                <th class="text-start text-uppercase small">Medicamento</th>
                <th class="d-none d-sm-table-cell d-lg-none d-xl-table-cell text-end text-uppercase small">Precio</th>
                <th class="col-3 col-md-2 col-lg-3 col-xl-2 text-center text-uppercase small">Cant.</th>
                <th class="text-end text-uppercase small">Subtotal</th>
                <th><span class="visually-hidden">Quitar</span></th>
            </tr>
        </thead>
        <tbody id="saleItems"
            data-medicines="{{ json_encode(old('medicine_id', [])) }}"
            data-amounts="{{ json_encode(old('amount', [])) }}"></tbody>
    </table>
</div>

<div class="table-empty" id="saleEmpty">
    <i class="bi bi-cart-plus"></i>
    <span>Agrega medicamentos desde la lista.</span>
</div>

<template id="saleRow">
    <tr>
        <td class="bg-transparent">
            <input type="hidden" name="medicine_id[]">
            <span class="d-block fw-medium text-break" data-cell="name"></span>
            <span class="d-block small text-secondary">
                <span class="d-sm-none d-lg-inline d-xl-none"><span data-cell="price"></span> ·</span>
                <span data-cell="stock"></span>
            </span>
        </td>
        <td class="bg-transparent d-none d-sm-table-cell d-lg-none d-xl-table-cell text-end text-nowrap" data-cell="price"></td>
        <td class="bg-transparent text-center">
            <input type="number" name="amount[]" min="1" step="1" required
                class="form-control form-control-sm text-center" aria-label="Cantidad">
        </td>
        <td class="bg-transparent text-end text-nowrap fw-medium" data-cell="subtotal"></td>
        <td class="bg-transparent text-end">
            <button type="button" class="btn btn-sm btn-light text-danger"
                title="Quitar" aria-label="Quitar">
                <i class="bi bi-x-lg"></i>
            </button>
        </td>
    </tr>
</template>

<div class="row justify-content-end mt-3 mb-3">
    <div class="col-sm-7 col-xl-6">
        <div class="d-flex justify-content-between small text-secondary mb-1">
            <span>Operación gravada</span>
            <span id="saleTaxable">S/ 0.00</span>
        </div>
        <div class="d-flex justify-content-between small text-secondary mb-2">
            <span>IGV ({{ \App\Models\Sale::IGV * 100 }} %)</span>
            <span id="saleIgv">S/ 0.00</span>
        </div>
        <div class="d-flex justify-content-between align-items-center border-top pt-2">
            <span class="fw-semibold">Total</span>
            <span class="sale-total text-primary" id="saleTotal">S/ 0.00</span>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary text-white">Registrar venta</button>
<a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancelar</a>
