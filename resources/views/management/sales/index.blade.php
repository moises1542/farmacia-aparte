@extends('template.administrador')

@section('title', 'VENTAS')
@section('name_section', 'VENTAS')
@section('description', 'Listado de ventas')
@section('item_section', 'Inicio')
@section('section', 'Ventas')
@section('module', 'Gestión')

@section('content')
@if (session('ok'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('ok') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif
@if (session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary">
  <div class="card-header bg-white">
    <div class="row">
      <div class="col-12 col-md-3">
        <a href="{{ route('sales.create') }}"
           class="btn btn-primary rounded-3 text-uppercase text-white w-100">
          <i class="bi bi-plus-lg me-2"></i>
          <span class="small fw-medium">Nuevo registro</span>
        </a>
      </div>
    </div>
  </div>
  <div class="card-body">
    <h6 class="text-uppercase small text-muted mb-3">
      Total de registros encontrados: {{ $sales->count() }}
    </h6>
    <table id="display" class="table table-sm table-hover w-100 bg-white">
      <thead>
        <tr>
          <th class="text-center align-middle text-uppercase small">Nº</th>
          <th class="text-center align-middle text-uppercase small">Ticket</th>
          <th class="text-center align-middle text-uppercase small">Fecha</th>
          <th class="text-center align-middle text-uppercase small">Cliente</th>
          <th class="text-center align-middle text-uppercase small">Vendedor</th>
          <th class="text-center align-middle text-uppercase small">Total</th>
          <th class="text-center align-middle text-uppercase small">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @php $contador = 1; @endphp
        @foreach ($sales as $sale)
          <tr>
            <td class="text-center align-middle">{{ $contador }}</td>
            <td class="text-center align-middle">{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</td>
            <td class="text-center align-middle">{{ $sale->date->format('d/m/Y') }}</td>
            <td class="text-start align-middle">{{ $sale->customer->name }}</td>
            <td class="text-start align-middle">{{ $sale->user->name }}</td>
            <td class="text-end align-middle text-nowrap">S/ {{ number_format($sale->total, 2) }}</td>
            <td class="text-center align-middle">
              <div class="dropdown">
                <button class="btn btn-light" type="button"
                        data-bs-toggle="dropdown">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item"
                       href="{{ route('sales.show', $sale->id) }}">
                      Ver ticket
                    </a>
                  </li>
                  <li>
                    <form method="POST"
                          action="{{ route('sales.destroy', $sale->id) }}"
                          onsubmit="return confirm('¿Anular esta venta?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="dropdown-item">Anular</button>
                    </form>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
          @php $contador++; @endphp
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
