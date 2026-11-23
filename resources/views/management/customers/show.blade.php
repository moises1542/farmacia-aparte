@extends('template.administrador')

@section('title', 'CLIENTES')
@section('name_section', 'CLIENTES')
@section('description', 'Historial de compras')
@section('item_section', 'Historial')
@section('section', 'Clientes')
@section('module', 'Gestión')

@section('content')
<div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary mb-4">
  <div class="card-body d-flex flex-column flex-md-row align-items-md-center gap-3">
    <div class="profile-avatar rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0">
      <i class="bi bi-person text-white fs-1"></i>
    </div>
    <div class="flex-grow-1">
      <h2 class="h5 fw-bold text-break mb-1">{{ $customer->name }}</h2>
      <div class="d-flex flex-wrap column-gap-4 row-gap-1 small">
        <span>
          <i class="bi bi-person-vcard text-secondary me-1"></i>
          <span class="text-secondary">DNI:</span>
          <span class="fw-medium">{{ $customer->dni }}</span>
        </span>
        <span>
          <i class="bi bi-telephone text-secondary me-1"></i>
          <span class="text-secondary">Teléfono:</span>
          <span class="fw-medium">{{ $customer->phone ?? 'Sin registrar' }}</span>
        </span>
        <span>
          <i class="bi bi-calendar-check text-secondary me-1"></i>
          <span class="text-secondary">Edad:</span>
          <span class="fw-medium">{{ $customer->age ?? 'Sin registrar' }}</span>
        </span>
      </div>
    </div>
    <a href="{{ route('customers.index') }}" class="btn btn-secondary">
      <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
  </div>
</div>

<div class="row g-3 mb-4 panel-grid">
  <div class="col-12 col-md-6 col-xl-4">
    <div class="stat-card stat-primary">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Compras</span>
        <span class="stat-icon"><i class="bi bi-bag-check"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $sales->count() }}">{{ number_format($sales->count()) }}</span>
      </div>
      <div class="stat-note">Tickets registrados</div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="stat-card stat-success">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Total comprado</span>
        <span class="stat-icon"><i class="bi bi-cash-coin"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $sales->sum('total') }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($sales->sum('total'), 2) }}</span>
      </div>
      <div class="stat-note">IGV incluido</div>
    </div>
  </div>
  <div class="col-12 col-xl-4">
    <div class="stat-card stat-info">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Última compra</span>
        <span class="stat-icon"><i class="bi bi-calendar-event"></i></span>
      </div>
      @if ($sales->isEmpty())
        <div class="stat-value">Sin compras</div>
        <div class="stat-note">Todavía no registra tickets</div>
      @else
        <div class="stat-value">{{ $sales->first()->date->format('d/m/Y') }}</div>
        <div class="stat-note">Ticket Nº {{ str_pad($sales->first()->id, 6, '0', STR_PAD_LEFT) }}</div>
      @endif
    </div>
  </div>
</div>

<div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary">
  <div class="card-header bg-white">
    <h6 class="panel-title"><i class="bi bi-clock-history"></i>Compras del cliente</h6>
  </div>
  <div class="card-body">
    @if ($sales->isEmpty())
      <div class="table-empty">
        <i class="bi bi-bag"></i>
        <span>Este cliente todavía no tiene compras registradas.</span>
      </div>
    @else
      <h6 class="text-uppercase small text-muted mb-3">
        Total de registros encontrados: {{ $sales->count() }}
      </h6>
      <table id="display" class="table table-sm table-hover w-100 bg-white">
        <thead>
          <tr>
            <th class="text-center align-middle text-uppercase small">Ticket</th>
            <th class="text-center align-middle text-uppercase small">Fecha</th>
            <th class="text-center align-middle text-uppercase small">Vendedor</th>
            <th class="text-center align-middle text-uppercase small">Total</th>
            <th class="text-center align-middle text-uppercase small">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sales as $sale)
            <tr>
              <td class="text-center align-middle">{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
              <td class="text-center align-middle">{{ $sale->date->format('d/m/Y') }}</td>
              <td class="text-start align-middle">{{ $sale->user->name }}</td>
              <td class="text-end align-middle text-nowrap">S/ {{ number_format($sale->total, 2) }}</td>
              <td class="text-center align-middle">
                <a href="{{ route('sales.show', $sale->id) }}"
                   class="btn btn-sm btn-light text-nowrap">
                  <i class="bi bi-receipt me-1"></i>Ver ticket
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</div>
@endsection
