@extends('template.administrador')

@section('title', 'REPORTES')
@section('name_section', 'REPORTES')
@section('description', 'Reportes del sistema')
@section('item_section', 'Inicio')
@section('section', 'Reportes')
@section('module', 'Principal')

@section('content')
<div class="row g-3 panel-grid">
  <div class="col-12 col-md-6">
    <a href="{{ route('reports.sales') }}" class="stat-card stat-primary d-flex flex-column">
      <div class="d-flex align-items-center gap-3 mb-3">
        <span class="stat-icon"><i class="bi bi-receipt"></i></span>
        <div>
          <h6 class="fw-bold mb-1">Reporte de ventas</h6>
          <div class="stat-note">Ventas por rango de fechas con su importe, IGV y ticket promedio.</div>
        </div>
      </div>
      <div class="mt-auto">
        <div class="stat-label">Vendido este mes</div>
        <div class="stat-value">
          <span data-counter="{{ $salesTotal }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($salesTotal, 2) }}</span>
        </div>
      </div>
      <i class="bi bi-arrow-right stat-arrow"></i>
    </a>
  </div>
  <div class="col-12 col-md-6">
    <a href="{{ route('reports.medicines') }}" class="stat-card stat-info d-flex flex-column">
      <div class="d-flex align-items-center gap-3 mb-3">
        <span class="stat-icon"><i class="bi bi-capsule"></i></span>
        <div>
          <h6 class="fw-bold mb-1">Reporte de medicamentos</h6>
          <div class="stat-note">Medicamentos más vendidos por rango de fechas, en unidades e importe.</div>
        </div>
      </div>
      <div class="mt-auto">
        <div class="stat-label">Unidades vendidas este mes</div>
        <div class="stat-value">
          <span data-counter="{{ $unitsSold }}">{{ number_format($unitsSold) }}</span>
        </div>
      </div>
      <i class="bi bi-arrow-right stat-arrow"></i>
    </a>
  </div>
  <div class="col-12 col-md-6">
    <a href="{{ route('reports.stock') }}" class="stat-card stat-success d-flex flex-column">
      <div class="d-flex align-items-center gap-3 mb-3">
        <span class="stat-icon"><i class="bi bi-boxes"></i></span>
        <div>
          <h6 class="fw-bold mb-1">Reporte de stock e inventario</h6>
          <div class="stat-note">Existencias actuales, valor del inventario y medicamentos por reponer.</div>
        </div>
      </div>
      <div class="mt-auto">
        <div class="stat-label">Valor del inventario</div>
        <div class="stat-value">
          <span data-counter="{{ $stockValue }}" data-decimals="2" data-prefix="S/ ">S/ {{ number_format($stockValue, 2) }}</span>
        </div>
      </div>
      <i class="bi bi-arrow-right stat-arrow"></i>
    </a>
  </div>
  <div class="col-12 col-md-6">
    <a href="{{ route('reports.customers') }}" class="stat-card stat-warning d-flex flex-column">
      <div class="d-flex align-items-center gap-3 mb-3">
        <span class="stat-icon"><i class="bi bi-people"></i></span>
        <div>
          <h6 class="fw-bold mb-1">Reporte de clientes</h6>
          <div class="stat-note">Clientes con compras por rango de fechas y el total que compraron.</div>
        </div>
      </div>
      <div class="mt-auto">
        <div class="stat-label">Clientes con compras este mes</div>
        <div class="stat-value">
          <span data-counter="{{ $customersCount }}">{{ number_format($customersCount) }}</span>
        </div>
      </div>
      <i class="bi bi-arrow-right stat-arrow"></i>
    </a>
  </div>
</div>
@endsection
