@extends('template.administrador')

@section('title', 'Dashboard')
@section('name_section', 'Dashboard')
@section('description', 'Resumen de estadísticas')
@section('item_section', 'Inicio')
@section('section', 'Dashboard')
@section('module', 'Principal')

@section('content')
<div class="row g-3 mb-4 panel-grid">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card stat-primary">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Ventas de hoy</span>
        <span class="stat-icon"><i class="bi bi-cash-coin"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $todayTotal }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($todayTotal, 2) }}</span>
      </div>
      <div class="stat-note">{{ number_format($todayCount) }} {{ $todayCount == 1 ? 'venta' : 'ventas' }}</div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card stat-success">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Ventas del mes</span>
        <span class="stat-icon"><i class="bi bi-calendar2-check"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $monthTotal }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($monthTotal, 2) }}</span>
      </div>
      <div class="stat-note">{{ number_format($monthCount) }} {{ $monthCount == 1 ? 'venta' : 'ventas' }}</div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card stat-info">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Medicamentos</span>
        <span class="stat-icon"><i class="bi bi-capsule"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $medicinesCount }}">{{ number_format($medicinesCount) }}</span>
      </div>
      <div class="stat-note">{{ number_format($lowStock->count()) }} con stock bajo</div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card stat-warning">
      <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
        <span class="stat-label">Clientes</span>
        <span class="stat-icon"><i class="bi bi-people"></i></span>
      </div>
      <div class="stat-value">
        <span data-counter="{{ $customersCount }}">{{ number_format($customersCount) }}</span>
      </div>
      <div class="stat-note">Registrados en el sistema</div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4 panel-grid">
  <div class="col-12 col-xl-8">
    <div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary h-100">
      <div class="card-header bg-white">
        <h6 class="panel-title"><i class="bi bi-bar-chart-line"></i>Ventas de los últimos 7 días</h6>
      </div>
      <div class="card-body">
        <div class="chart-bars">
          @foreach ($days as $day)
            <div class="chart-col">
              <div class="chart-track">
                <div class="chart-bar" style="--value: {{ $day['percent'] }}"
                     data-label="S/ {{ number_format($day['total'], 2) }}"
                     data-short="{{ number_format($day['total']) }}"></div>
              </div>
              <div class="chart-label">{{ $day['label'] }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-xl-4">
    <div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary h-100">
      <div class="card-header bg-white">
        <h6 class="panel-title"><i class="bi bi-trophy"></i>Más vendidos</h6>
      </div>
      <div class="card-body">
        @forelse ($topMedicines as $medicine)
          <div class="d-flex align-items-center gap-3 {{ $loop->last ? null : 'mb-3' }}">
            <span class="rank-number">{{ $loop->iteration }}</span>
            <div class="flex-grow-1 overflow-hidden">
              <div class="d-flex justify-content-between gap-2 small mb-1">
                <span class="fw-medium text-truncate">{{ $medicine->name }}</span>
                <span class="text-secondary flex-shrink-0">{{ number_format($medicine->units) }} {{ $medicine->units == 1 ? 'unidad' : 'unidades' }}</span>
              </div>
              <div class="progress" style="height:6px">
                <div class="progress-bar" style="width: {{ $maxUnits > 0 ? round($medicine->units / $maxUnits * 100) : 0 }}%"></div>
              </div>
            </div>
          </div>
        @empty
          <div class="table-empty text-center">
            <i class="bi bi-trophy"></i>
            <span>Todavía no hay ventas en los últimos 30 días.</span>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4 panel-grid">
  <div class="col-12 col-xl-7">
    <div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary h-100">
      <div class="card-header bg-white">
        <h6 class="panel-title"><i class="bi bi-receipt"></i>Últimas ventas</h6>
      </div>
      <div class="card-body">
        @if ($recentSales->isEmpty())
          <div class="table-empty text-center">
            <i class="bi bi-receipt"></i>
            <span>Todavía no hay ventas registradas.</span>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-sm table-hover w-100 bg-white small mb-0">
              <thead>
                <tr>
                  <th class="text-center align-middle text-uppercase small">Ticket</th>
                  <th class="text-center align-middle text-uppercase small">Fecha</th>
                  <th class="text-center align-middle text-uppercase small">Cliente</th>
                  <th class="text-center align-middle text-uppercase small">Total</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($recentSales as $sale)
                  <tr>
                    <td class="text-center align-middle">
                      <a href="{{ route('sales.show', $sale->id) }}" class="fw-medium text-decoration-none">
                        {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                      </a>
                    </td>
                    <td class="text-center align-middle">{{ $sale->date->format('d/m/Y') }}</td>
                    <td class="text-start align-middle">{{ $sale->customer->name }}</td>
                    <td class="text-end align-middle text-nowrap">S/ {{ number_format($sale->total, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
  <div class="col-12 col-xl-5">
    <div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary h-100">
      <div class="card-header bg-white">
        <h6 class="panel-title"><i class="bi bi-exclamation-triangle"></i>Stock bajo</h6>
      </div>
      <div class="card-body">
        @if ($lowStock->isEmpty())
          <div class="table-empty text-center">
            <i class="bi bi-check2-circle"></i>
            <span>Todo en orden: ningún medicamento tiene stock bajo.</span>
          </div>
        @else
          <div class="list-group list-group-flush overflow-y-auto" style="max-height:350px">
            @foreach ($lowStock as $medicine)
              <a href="{{ route('medicines.edit', $medicine->id) }}"
                 class="list-group-item list-group-item-action text-body d-flex align-items-center justify-content-between gap-2 px-1">
                <span class="text-truncate">{{ $medicine->name }}</span>
                @if ($medicine->stock == 0)
                  <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Agotado</span>
                @else
                  <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">{{ $medicine->stock }} {{ $medicine->stock == 1 ? 'unidad' : 'unidades' }}</span>
                @endif
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary">
  <div class="card-header bg-white">
    <h6 class="panel-title"><i class="bi bi-lightning-charge"></i>Accesos rápidos</h6>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-12 col-sm-6 col-lg">
        <a href="{{ route('sales.create') }}" class="quick-action h-100">
          <i class="bi bi-cart-plus"></i>
          <span class="fw-medium">Nueva venta</span>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-lg">
        <a href="{{ route('medicines.create') }}" class="quick-action h-100">
          <i class="bi bi-capsule"></i>
          <span class="fw-medium">Nuevo medicamento</span>
        </a>
      </div>
      <div class="col-12 col-sm-6 col-lg">
        <a href="{{ route('customers.create') }}" class="quick-action h-100">
          <i class="bi bi-person-plus"></i>
          <span class="fw-medium">Nuevo cliente</span>
        </a>
      </div>
      @if (auth()->user()->isAdmin())
      <div class="col-12 col-sm-6 col-lg">
        <a href="{{ route('reports.index') }}" class="quick-action h-100">
          <i class="bi bi-bar-chart"></i>
          <span class="fw-medium">Reportes</span>
        </a>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
