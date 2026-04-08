<div class="modal fade" id="userModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Novo Usuário</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editIndex"/>
        <div class="mb-3">
          <label class="form-label">Nome completo</label>
          <input type="text" class="form-control" id="inputName" placeholder="Ex: Ana Silva"/>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" id="inputEmail" placeholder="email@exemplo.com"/>
        </div>
        <div class="mb-3">
          <label class="form-label" id="passwordLabel">Senha</label>
          <input type="password" class="form-control" id="inputPassword" placeholder="Nova senha"/>
          <div class="form-text" id="passwordHint" style="display:none;">Deixe em branco para manter a senha atual.</div>
        </div>
        <div class="mb-3">
          <label class="form-label">Data de Expiração</label>
          <input type="date" class="form-control" id="inputExpiry"/>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn-save" onclick="saveUser()">Salvar</button>
      </div>
    </div>
  </div>
</div>
