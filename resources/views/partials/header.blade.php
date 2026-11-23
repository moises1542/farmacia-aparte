    <header class="app-header bg-white border-bottom shadow-sm d-flex align-items-center px-3 px-lg-4 gap-3">

      <button class="btn btn-sm btn-outline-secondary border-0 sidebar-toggle-btn"
              data-sidebar-toggle
              aria-label="Alternar menú lateral">
        <i class="bi bi-list fs-5"></i>
      </button>

      <h1 class="h5 fw-bold mb-0 me-auto">@yield('name_section')</h1>

      <div class="d-flex align-items-center gap-2">

        <div class="dropdown">
          <button class="btn btn-sm btn-outline-secondary border-0 position-relative rounded-3"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                  aria-label="Notificaciones">
            <i class="bi bi-bell fs-5 {{ $lowStock->isNotEmpty() ? 'notify-ring' : null }}"></i>
            @if ($lowStock->isNotEmpty())
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary"
                  style="font-size:.58rem;padding:.28em .45em">{{ $lowStock->count() }}</span>
            @endif
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow border" style="min-width:290px;max-width:calc(100vw - 1rem)">
            <li><h6 class="dropdown-header">Notificaciones</h6></li>
            @forelse ($lowStock->take(5) as $medicine)
            <li>
              <a class="dropdown-item text-wrap py-2" href="{{ route('medicines.edit', $medicine->id) }}">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-exclamation-triangle text-danger mt-1 flex-shrink-0"></i>
                  <div class="overflow-hidden">
                    <div class="small fw-medium text-break">
                      {{ $medicine->stock == 0 ? 'Agotado' : 'Stock bajo' }}: {{ $medicine->name }}
                    </div>
                    <div class="text-secondary" style="font-size:.72rem">
                      @if ($medicine->stock == 0)
                        Sin unidades disponibles
                      @else
                        {{ $medicine->stock == 1 ? 'Queda 1 unidad' : 'Quedan '.$medicine->stock.' unidades' }}
                      @endif
                    </div>
                  </div>
                </div>
              </a>
            </li>
            @empty
            <li>
              <div class="dropdown-item-text py-2">
                <div class="d-flex align-items-start gap-2">
                  <i class="bi bi-check-circle text-success mt-1 flex-shrink-0"></i>
                  <div class="small">No hay medicamentos con stock bajo.</div>
                </div>
              </div>
            </li>
            @endforelse
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item text-center small text-primary fw-medium py-2"
                 href="{{ route('medicines.index') }}">
                Ver todos los medicamentos
              </a>
            </li>
          </ul>
        </div>

      </div>
    </header>
