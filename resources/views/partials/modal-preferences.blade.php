<div class="modal fade" id="preferencesModal" tabindex="-1"
     aria-labelledby="preferencesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-0 shadow">

      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title fw-bold" id="preferencesModalLabel">
          <i class="bi bi-sliders text-primary me-2"></i>Preferencias
        </h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body p-4">
        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" role="switch" id="prefAnimations" checked>
          <label class="form-check-label small" for="prefAnimations">Animaciones de la interfaz</label>
        </div>
        <div class="form-check form-switch mb-0">
          <input class="form-check-input" type="checkbox" role="switch" id="prefSidebar">
          <label class="form-check-label small" for="prefSidebar">Iniciar con el menú lateral oculto</label>
        </div>
        <p class="text-secondary mt-3 mb-0" style="font-size:.72rem">
          Se guardan solo en este navegador.
        </p>
      </div>

      <div class="modal-footer border-top justify-content-center py-3">
        <button type="button" class="btn btn-sm btn-primary text-white px-4"
                data-bs-dismiss="modal">Listo</button>
      </div>

    </div>
  </div>
</div>
