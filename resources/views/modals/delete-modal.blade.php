<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <div class="delete-icon"><i class="bi bi-trash3-fill"></i></div>
        <h6 style="font-weight:700;margin-bottom:8px;">Excluir usuário?</h6>
        <p style="font-size:.85rem;color:var(--text-muted);margin-bottom:20px;">Esta ação não pode ser desfeita.</p>
        <div class="d-flex gap-2 justify-content-center">
          <button class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn-save" style="background:#ef4444;" onclick="confirmDelete()">Excluir</button>
        </div>
      </div>
    </div>
  </div>
</div>
