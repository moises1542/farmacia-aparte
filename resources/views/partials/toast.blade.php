@if (session('status'))
<div class="toast-container position-fixed top-0 end-0 p-3">
  <div class="toast align-items-center text-bg-success border-0" role="status"
       aria-live="polite" aria-atomic="true" data-bs-delay="5000">
    <div class="d-flex">
      <div class="toast-body">
        <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
  </div>
</div>
@endif
