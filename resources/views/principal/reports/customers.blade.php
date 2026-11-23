@extends('template.administrador')

@section('title', 'REPORTES')
@section('name_section', 'REPORTES')
@section('description', 'Reporte de clientes')
@section('item_section', 'Clientes')
@section('section', 'Reportes')
@section('module', 'Principal')

@section('content')
@include('principal.reports.filter', ['report' => 'customers'])

<div class="row g-3 mb-4 panel-grid">
  <div class="col-12 col-md-4">
    <div class="stat-card stat-warning">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Clientes con compras</span>
        <span class="stat-icon"><i class="bi bi-people"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $customers->count() }}">{{ number_format($customers->count()) }}</span>
      </div>
      <div class="stat-note">Con al menos una compra en el periodo</div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="stat-card stat-info">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Compras</span>
        <span class="stat-icon"><i class="bi bi-bag-check"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $purchases }}">{{ number_format($purchases) }}</span>
      </div>
      <div class="stat-note">Ventas registradas a esos clientes</div>
    </div>
  </div>
  <div class="col-12 col-md-4">
    <div class="stat-card stat-success">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Total comprado</span>
        <span class="stat-icon"><i class="bi bi-cash-coin"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $total }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($total, 2) }}</span>
      </div>
      <div class="stat-note">Precios con IGV incluido</div>
    </div>
  </div>
</div>

<div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary">
  <div class="card-header bg-white">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md">
        <h6 class="panel-title">
          <i class="bi bi-people"></i>
          Clientes con compras del {{ date('d/m/Y', strtotime($from)) }} al {{ date('d/m/Y', strtotime($to)) }}
        </h6>
      </div>
      <div class="col-12 col-sm-6 col-md-auto">
        <a href="{{ route('reports.export', ['report' => 'customers', 'from' => $from, 'to' => $to]) }}"
           class="btn btn-primary rounded-3 text-uppercase text-white w-100 no-print">
          <i class="bi bi-download me-2"></i>
          <span class="small fw-medium">Exportar CSV</span>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-md-auto">
        <button type="button" class="btn btn-secondary rounded-3 text-uppercase w-100 no-print" data-print>
          <i class="bi bi-printer me-2"></i>
          <span class="small fw-medium">Imprimir</span>
        </button>
      </div>
    </div>
  </div>
  <div class="card-body">
    <h6 class="text-uppercase small text-muted mb-3">
      Total de registros encontrados: {{ $customers->count() }}
    </h6>
    <div class="table-responsive">
      <table class="table table-sm table-hover w-100 bg-white">
        <thead>
          <tr>
            <th class="text-center align-middle text-uppercase small">Nº</th>
            <th class="text-center align-middle text-uppercase small" style="min-width:220px">Cliente</th>
            <th class="text-center align-middle text-uppercase small">DNI</th>
            <th class="text-center align-middle text-uppercase small">Compras</th>
            <th class="text-center align-middle text-uppercase small">Total comprado</th>
            <th class="text-center align-middle text-uppercase small">Última compra</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($customers as $customer)
            <tr>
              <td class="text-center align-middle"><span class="rank-number">{{ $loop->iteration }}</span></td>
              <td class="text-start align-middle">{{ $customer->name }}</td>
              <td class="text-center align-middle">{{ $customer->dni }}</td>
              <td class="text-center align-middle">{{ number_format($customer->purchases) }}</td>
              <td class="text-end align-middle text-nowrap">S/ {{ number_format($customer->total, 2) }}</td>
              <td class="text-center align-middle">{{ $customer->last_purchase->format('d/m/Y') }}</td>
            </tr>
          @endforeach
        </tbody>
        @if ($customers->isNotEmpty())
          <tfoot>
            <tr>
              <th colspan="3" class="text-end align-middle text-uppercase small">Total</th>
              <th class="text-center align-middle">{{ number_format($purchases) }}</th>
              <th class="text-end align-middle text-nowrap">S/ {{ number_format($total, 2) }}</th>
              <th></th>
            </tr>
          </tfoot>
        @endif
      </table>
    </div>
    @if ($customers->isEmpty())
      <div class="table-empty text-center">
        <i class="bi bi-inbox"></i>
        <span>No hay clientes con compras en el periodo seleccionado.</span>
      </div>
    @endif
  </div>
</div>
@endsection
