@extends('template.administrador')

@section('title', 'REPORTES')
@section('name_section', 'REPORTES')
@section('description', 'Reporte de ventas')
@section('item_section', 'Ventas')
@section('section', 'Reportes')
@section('module', 'Principal')

@section('content')
@include('principal.reports.filter', ['report' => 'sales'])

<div class="row g-3 mb-4 panel-grid">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card stat-primary">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Ventas</span>
        <span class="stat-icon"><i class="bi bi-receipt"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $sales->count() }}">{{ number_format($sales->count()) }}</span>
      </div>
      <div class="stat-note">Registradas en el periodo</div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card stat-success">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Importe total</span>
        <span class="stat-icon"><i class="bi bi-cash-coin"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $total }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($total, 2) }}</span>
      </div>
      <div class="stat-note">Precios con IGV incluido</div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card stat-info">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">IGV</span>
        <span class="stat-icon"><i class="bi bi-percent"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $igv }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($igv, 2) }}</span>
      </div>
      <div class="stat-note">{{ \App\Models\Sale::IGV * 100 }} % del valor de venta</div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card stat-warning">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Ticket promedio</span>
        <span class="stat-icon"><i class="bi bi-calculator"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $average }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($average, 2) }}</span>
      </div>
      <div class="stat-note">Importe medio por venta</div>
    </div>
  </div>
</div>

<div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary">
  <div class="card-header bg-white">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md">
        <h6 class="panel-title">
          <i class="bi bi-receipt"></i>
          Ventas del {{ date('d/m/Y', strtotime($from)) }} al {{ date('d/m/Y', strtotime($to)) }}
        </h6>
      </div>
      <div class="col-12 col-sm-6 col-md-auto">
        <a href="{{ route('reports.export', ['report' => 'sales', 'from' => $from, 'to' => $to]) }}"
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
      Total de registros encontrados: {{ $sales->count() }}
    </h6>
    <div class="table-responsive">
      <table class="table table-sm table-hover w-100 bg-white">
        <thead>
          <tr>
            <th class="text-center align-middle text-uppercase small">Ticket</th>
            <th class="text-center align-middle text-uppercase small">Fecha</th>
            <th class="text-center align-middle text-uppercase small" style="min-width:220px">Cliente</th>
            <th class="text-center align-middle text-uppercase small" style="min-width:150px">Vendedor</th>
            <th class="text-center align-middle text-uppercase small">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sales as $sale)
            <tr>
              <td class="text-center align-middle">{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
              <td class="text-center align-middle">{{ $sale->date->format('d/m/Y') }}</td>
              <td class="text-start align-middle">{{ $sale->customer->name }}</td>
              <td class="text-start align-middle">{{ $sale->user->name }}</td>
              <td class="text-end align-middle text-nowrap">S/ {{ number_format($sale->total, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
        @if ($sales->isNotEmpty())
          <tfoot>
            <tr>
              <th colspan="4" class="text-end align-middle text-uppercase small">Total</th>
              <th class="text-end align-middle text-nowrap">S/ {{ number_format($total, 2) }}</th>
            </tr>
          </tfoot>
        @endif
      </table>
    </div>
    @if ($sales->isEmpty())
      <div class="table-empty text-center">
        <i class="bi bi-inbox"></i>
        <span>No hay ventas en el periodo seleccionado.</span>
      </div>
    @endif
  </div>
</div>
@endsection
