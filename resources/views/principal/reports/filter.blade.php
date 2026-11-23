<div class="card bg-white border-0 shadow-sm border-5 border-top border-secondary mb-4 no-print">
    <div class="card-body">
        <form action="{{ route('reports.'.$report) }}" method="GET">
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="input-group has-validation">
                        <label for="from" class="input-group-text">Desde</label>
                        <input type="date" name="from" id="from"
                            class="form-control @error('from') is-invalid @enderror"
                            value="{{ is_string(old('from')) ? old('from') : $from }}">
                        @error('from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="input-group has-validation">
                        <label for="to" class="input-group-text">Hasta</label>
                        <input type="date" name="to" id="to"
                            class="form-control @error('to') is-invalid @enderror"
                            value="{{ is_string(old('to')) ? old('to') : $to }}">
                        @error('to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-lg-4 d-flex align-items-start gap-2">
                    <button type="submit" class="btn btn-primary text-white flex-fill">
                        <i class="bi bi-funnel me-2"></i>Filtrar
                    </button>
                    <a href="{{ route('reports.'.$report) }}" class="btn btn-secondary flex-fill">Este mes</a>
                </div>
            </div>
        </form>
    </div>
</div>
