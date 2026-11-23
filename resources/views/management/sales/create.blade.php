@extends('template.administrador')

@section('title', 'VENTAS')
@section('name_section', 'VENTAS')
@section('description', 'Nueva venta')
@section('item_section', 'Nuevo registro')
@section('section', 'Ventas')
@section('module', 'Gestión')

@section('content')
<div class="row g-4 panel-grid">
    <div class="col-lg-5">
        <div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary h-100">
            <div class="card-header bg-white">
                <h6 class="panel-title"><i class="bi bi-capsule"></i>Medicamentos</h6>
            </div>
            <div class="card-body">
                <div class="table-search mw-100 input-group mb-3">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-secondary"></i></span>
                    <input type="search" id="medicineSearch" class="form-control border-start-0"
                        placeholder="Buscar..." aria-label="Buscar medicamento por nombre o categoría"
                        autocomplete="off" @disabled($medicines->isEmpty())>
                </div>

                <div class="medicine-list pt-1">
                    @forelse ($medicines as $medicine)
                        <button type="button" class="medicine-item text-body"
                            data-id="{{ $medicine->id }}"
                            data-name="{{ $medicine->name }}"
                            data-category="{{ $medicine->category->name }}"
                            data-price="{{ $medicine->price }}"
                            data-stock="{{ $medicine->stock }}"
                            @disabled($medicine->stock == 0)>
                            <span class="flex-grow-1 overflow-hidden">
                                <span class="d-block fw-medium text-break">{{ $medicine->name }}</span>
                                <span class="d-block small text-secondary text-break">{{ $medicine->category->name }}</span>
                            </span>
                            <span class="badge rounded-pill bg-primary d-none" data-added></span>
                            <span class="text-end flex-shrink-0">
                                <span class="d-block fw-semibold">S/ {{ number_format($medicine->price, 2) }}</span>
                                @if ($medicine->stock == 0)
                                    <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Agotado</span>
                                @elseif ($medicine->stock <= \App\Models\Medicine::LOW_STOCK)
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Stock: {{ $medicine->stock }}</span>
                                @else
                                    <span class="small text-secondary">Stock: {{ $medicine->stock }}</span>
                                @endif
                            </span>
                        </button>
                    @empty
                        <div class="table-empty">
                            <i class="bi bi-capsule"></i>
                            <span>Todavía no hay medicamentos registrados.</span>
                        </div>
                    @endforelse
                </div>

                <div class="table-empty d-none" id="medicineEmpty">
                    <i class="bi bi-search"></i>
                    <span>Sin resultados para la búsqueda.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary h-100">
            <div class="card-header bg-white">
                <h6 class="panel-title"><i class="bi bi-receipt"></i>Detalle de la venta</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('sales.store') }}" method="POST" id="saleForm"
                      data-igv="{{ \App\Models\Sale::IGV }}">
                    @csrf
                    @include('management.sales.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
