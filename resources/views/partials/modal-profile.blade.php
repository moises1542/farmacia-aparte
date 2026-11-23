<div class="modal fade" id="profileModal" tabindex="-1"
     aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">

      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <div class="profile-avatar rounded-circle bg-primary d-inline-flex align-items-center justify-content-center mb-3">
            <span class="text-white fw-bold fs-4">{{ auth()->user()->initials() }}</span>
          </div>
          <h6 class="fw-bold text-break mb-1" id="profileModalLabel">{{ auth()->user()->name }}</h6>
          <span class="badge bg-primary-subtle text-primary px-3 rounded-pill">{{ auth()->user()->role->name }}</span>
        </div>

        <ul class="list-group list-group-flush small">
          <li class="list-group-item d-flex justify-content-between gap-3 px-0">
            <span class="text-secondary text-nowrap"><i class="bi bi-envelope me-2"></i>Correo</span>
            <span class="fw-medium text-end text-break">{{ auth()->user()->email }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between gap-3 px-0">
            <span class="text-secondary text-nowrap"><i class="bi bi-person-vcard me-2"></i>DNI</span>
            <span class="fw-medium text-end text-break">{{ auth()->user()->dni }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between gap-3 px-0">
            <span class="text-secondary text-nowrap"><i class="bi bi-telephone me-2"></i>Teléfono</span>
            <span class="fw-medium text-end text-break">{{ auth()->user()->phone ?? 'Sin registrar' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between gap-3 px-0">
            <span class="text-secondary text-nowrap"><i class="bi bi-award me-2"></i>Colegiatura</span>
            <span class="fw-medium text-end text-break">{{ auth()->user()->tuition ?? 'Sin registrar' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between gap-3 px-0">
            <span class="text-secondary text-nowrap"><i class="bi bi-calendar-check me-2"></i>Miembro desde</span>
            <span class="fw-medium text-end text-break">{{ auth()->user()->created_at?->format('d/m/Y') ?? 'Sin registrar' }}</span>
          </li>
        </ul>
      </div>

      <div class="modal-footer border-top justify-content-center py-3 gap-2">
        <button type="button" class="btn btn-sm btn-outline-secondary px-4"
                data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-sm btn-primary text-white px-4"
                data-bs-toggle="modal" data-bs-target="#passwordModal">
          <i class="bi bi-shield-lock me-1"></i>Cambiar contraseña
        </button>
      </div>

    </div>
  </div>
</div>
