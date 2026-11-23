<div class="modal fade" id="passwordModal" tabindex="-1"
     aria-labelledby="passwordModalLabel" aria-hidden="true"
     @if ($errors->password->any()) data-auto-open @endif>
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form method="POST" action="{{ route('profile.password') }}">
        @csrf
        @method('PUT')

        <div class="modal-header border-0 pb-0">
          <h6 class="modal-title fw-bold" id="passwordModalLabel">
            <i class="bi bi-shield-lock text-primary me-2"></i>Cambiar contraseña
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body p-4">
          <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña actual</label>
            <input type="password" name="current_password" id="current_password"
                class="form-control @error('current_password', 'password') is-invalid @enderror"
                autocomplete="current-password">
            @error('current_password', 'password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="new_password" class="form-label">Contraseña nueva</label>
            <input type="password" name="password" id="new_password"
                class="form-control @error('password', 'password') is-invalid @enderror"
                autocomplete="new-password">
            @error('password', 'password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-0">
            <label for="password_confirmation" class="form-label">Repetir contraseña nueva</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control" autocomplete="new-password">
          </div>
        </div>

        <div class="modal-footer border-top justify-content-center py-3 gap-2">
          <button type="button" class="btn btn-sm btn-outline-secondary px-4"
                  data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-sm btn-primary text-white px-4">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
