@extends('template.administrador')

@section('title', 'REPORTES')
@section('name_section', 'REPORTES')
@section('description', 'Reporte de stock e inventario')
@section('item_section', 'Stock e inventario')
@section('section', 'Reportes')
@section('module', 'Principal')

@section('content')
<div class="row g-3 mb-4 panel-grid">
  <div class="col-12 col-lg-4">
    <div class="stat-card stat-info">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Medicamentos</span>
        <span class="stat-icon"><i class="bi bi-capsule"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $medicines->count() }}">{{ number_format($medicines->count()) }}</span>
      </div>
      <div class="stat-note">Registrados en el sistema</div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="stat-card stat-success">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Unidades en stock</span>
        <span class="stat-icon"><i class="bi bi-boxes"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $units }}">{{ number_format($units) }}</span>
      </div>
      <div class="stat-note">Suma de todas las existencias</div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="stat-card stat-primary">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Valor del inventario</span>
        <span class="stat-icon"><i class="bi bi-cash-stack"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $value }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($value, 2) }}</span>
      </div>
      <div class="stat-note">Suma de precio por stock</div>
    </div>
  </div>
  <div class="col-12 col-sm-6">
    <div class="stat-card stat-warning">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Con stock bajo</span>
        <span class="stat-icon"><i class="bi bi-exclamation-triangle"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $lowStock }}">{{ number_format($lowStock) }}</span>
      </div>
      <div class="stat-note">Con {{ \App\Models\Medicine::LOW_STOCK }} unidades o menos, incluidos los agotados</div>
    </div>
  </div>
  <div class="col-12 col-sm-6">
    <div class="stat-card stat-danger">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Agotados</span>
        <span class="stat-icon"><i class="bi bi-x-circle"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $outOfStock }}">{{ number_format($outOfStock) }}</span>
      </div>
      <div class="stat-note">Sin unidades disponibles</div>
    </div>
  </div>
</div>

<div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary">
  <div class="card-header bg-white">
    <div class="row g-2 align-items-center">
      <div class="col-12 col-md">
        <h6 class="panel-title">
          <i class="bi bi-boxes"></i>
          Inventario al {{ now()->format('d/m/Y') }}
        </h6>
      </div>
      <div class="col-12 col-sm-6 col-md-auto">
        <a href="{{ route('reports.export', 'stock') }}"
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
      Total de registros encontrados: {{ $medicines->count() }}
    </h6>
    <div class="table-responsive">
      <table class="table table-sm table-hover w-100 bg-white">
        <thead>
          <tr>
            <th class="text-center align-middle text-uppercase small" style="min-width:220px">Medicamento</th>
            <th class="text-center align-middle text-uppercase small">Categoría</th>
            <th class="text-center align-middle text-uppercase small">Precio</th>
            <th class="text-center align-middle text-uppercase small">Stock</th>
            <th class="text-center align-middle text-uppercase small">Valor</th>
            <th class="text-center align-middle text-uppercase small">Estado</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($medicines as $medicine)
            <tr>
              <td class="text-start align-middle">{{ $medicine->name }}</td>
              <td class="text-start align-middle">{{ $medicine->category->name }}</td>
              <td class="text-end align-middle text-nowrap">S/ {{ number_format($medicine->price, 2) }}</td>
              <td class="text-center align-middle">{{ number_format($medicine->stock) }}</td>
              <td class="text-end align-middle text-nowrap">S/ {{ number_format($medicine->value, 2) }}</td>
              <td class="text-center align-middle">
                @if ($medicine->stock == 0)
                  <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Agotado</span>
                @elseif ($medicine->stock <= \App\Models\Medicine::LOW_STOCK)
                  <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Stock bajo</span>
                @else
                  <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Disponible</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
        @if ($medicines->isNotEmpty())
          <tfoot>
            <tr>
              <th colspan="3" class="text-end align-middle text-uppercase small">Total</th>
              <th class="text-center align-middle">{{ number_format($units) }}</th>
              <th class="text-end align-middle text-nowrap">S/ {{ number_format($value, 2) }}</th>
              <th></th>
            </tr>
          </tfoot>
        @endif
      </table>
    </div>
    @if ($medicines->isEmpty())
      <div class="table-empty text-center">
        <i class="bi bi-inbox"></i>
        <span>Todavía no hay medicamentos registrados.</span>
      </div>
    @endif
  </div>
</div>
@endsection
