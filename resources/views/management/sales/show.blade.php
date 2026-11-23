@extends('template.administrador')

@section('title', 'VENTAS')
@section('name_section', 'VENTAS')
@section('description', 'Ticket de venta')
@section('item_section', 'Ticket')
@section('section', 'Ventas')
@section('module', 'Gestión')

@section('content')
@if (session('ok'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('ok') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="ticket">
  <div class="text-center">
    <i class="bi bi-plus-circle-fill text-primary fs-1 lh-1"></i>
    <h2 class="h5 fw-bold mt-2 mb-0">Farmacia San Juan Bautista</h2>
    <p class="small text-secondary mb-2">Ticket de venta</p>
    <span class="badge rounded-pill bg-primary-subtle text-primary px-3">
      Nº {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
    </span>
  </div>

  <div class="ticket-divider"></div>

  <ul class="list-unstyled small mb-0">
    <li class="d-flex justify-content-between gap-3 mb-1">
      <span class="text-secondary">Fecha</span>
      <span class="fw-medium text-end">{{ $sale->date->format('d/m/Y') }}</span>
    </li>
    <li class="d-flex justify-content-between gap-3 mb-1">
      <span class="text-secondary">Cliente</span>
      <span class="fw-medium text-end text-break">{{ $sale->customer->name }}</span>
    </li>
    <li class="d-flex justify-content-between gap-3 mb-1">
      <span class="text-secondary">DNI</span>
      <span class="fw-medium text-end">{{ $sale->customer->dni }}</span>
    </li>
    <li class="d-flex justify-content-between gap-3">
      <span class="text-secondary">Vendedor</span>
      <span class="fw-medium text-end text-break">{{ $sale->user->name }}</span>
    </li>
  </ul>

  <div class="ticket-divider"></div>

  <div class="table-responsive">
    <table class="table table-sm table-borderless small mb-0">
      <thead>
        <tr>
          <th class="text-center text-uppercase small">Cant.</th>
          <th class="text-start text-uppercase small">Medicamento</th>
          <th class="d-none d-sm-table-cell text-end text-uppercase small">P. unit.</th>
          <th class="text-end text-uppercase small">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($sale->details as $detail)
          <tr>
            <td class="text-center">{{ $detail->amount }}</td>
            <td class="text-start text-break">
              <span class="d-block">{{ $detail->medicine->name }}</span>
              <span class="d-block d-sm-none text-secondary">S/ {{ number_format($detail->unit_price, 2) }} c/u</span>
            </td>
            <td class="d-none d-sm-table-cell text-end text-nowrap">S/ {{ number_format($detail->unit_price, 2) }}</td>
            <td class="text-end text-nowrap">S/ {{ number_format($detail->subtotal, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="ticket-divider"></div>

  <div class="d-flex justify-content-between small text-secondary mb-1">
    <span>Operación gravada</span>
    <span>S/ {{ number_format($sale->total - $sale->igv, 2) }}</span>
  </div>
  <div class="d-flex justify-content-between small text-secondary mb-2">
    <span>IGV ({{ \App\Models\Sale::IGV * 100 }} %)</span>
    <span>S/ {{ number_format($sale->igv, 2) }}</span>
  </div>
  <div class="d-flex justify-content-between align-items-center">
    <span class="fw-semibold">Total</span>
    <span class="sale-total text-primary">S/ {{ number_format($sale->total, 2) }}</span>
  </div>

  <div class="ticket-divider"></div>

  <p class="text-center small text-secondary mb-0">¡Gracias por su compra!</p>
</div>

<div class="d-flex flex-wrap justify-content-center gap-2 mt-4 no-print">
  <button type="button" class="btn btn-primary text-white" data-print>
    <i class="bi bi-printer me-2"></i>Imprimir
  </button>
  <a href="{{ route('sales.index') }}" class="btn btn-secondary">Volver</a>
  <form method="POST"
        action="{{ route('sales.destroy', $sale->id) }}"
        onsubmit="return confirm('¿Anular esta venta?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger">Anular venta</button>
  </form>
</div>
@endsection
