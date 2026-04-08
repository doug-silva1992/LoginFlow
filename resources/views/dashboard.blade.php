<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  <style>
    :root {
      --green: #22c55e;
      --green-dark: #16a34a;
      --green-light: #dcfce7;
      --sidebar-bg: #0f172a;
      --sidebar-text: #94a3b8;
      --sidebar-active: #22c55e;
      --body-bg: #f1f5f9;
      --card-bg: #ffffff;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --border: #e2e8f0;
      --danger: #ef4444;
      --warning: #f59e0b;
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'Sora', sans-serif;
      background: var(--body-bg);
      color: var(--text-main);
      margin: 0;
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      width: 240px;
      background: var(--sidebar-bg);
      display: flex;
      flex-direction: column;
      padding: 0;
      position: fixed;
      top: 0; left: 0;
      height: 100vh;
      z-index: 100;
      transition: transform .3s;
    }

    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 28px 24px 24px;
      border-bottom: 1px solid rgba(255,255,255,.07);
    }
    .sidebar-logo .dot {
      width: 14px; height: 14px;
      background: var(--green);
      border-radius: 50%;
      flex-shrink: 0;
    }
    .sidebar-logo span {
      font-size: 1.4rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: -0.5px;
    }

    .sidebar-nav {
      flex: 1;
      padding: 20px 12px;
      list-style: none;
      margin: 0;
    }
    .sidebar-nav li a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 14px;
      border-radius: 10px;
      color: var(--sidebar-text);
      text-decoration: none;
      font-size: .875rem;
      font-weight: 500;
      transition: background .2s, color .2s;
    }
    .sidebar-nav li a:hover,
    .sidebar-nav li a.active {
      background: rgba(34,197,94,.12);
      color: var(--sidebar-active);
    }
    .sidebar-nav li a i { font-size: 1rem; }

    .sidebar-footer {
      padding: 16px 12px;
      border-top: 1px solid rgba(255,255,255,.07);
    }
    .sidebar-footer a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      border-radius: 10px;
      color: var(--sidebar-text);
      text-decoration: none;
      font-size: .875rem;
      font-weight: 500;
      transition: background .2s, color .2s;
    }
    .sidebar-footer a:hover { background: rgba(239,68,68,.12); color: #ef4444; }

    .main-wrap {
      margin-left: 240px;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .topbar {
      background: var(--card-bg);
      border-bottom: 1px solid var(--border);
      padding: 16px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
    }
    .topbar-title {
      font-size: 1.05rem;
      font-weight: 600;
      color: var(--text-main);
    }
    .topbar-right {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .avatar {
      width: 36px; height: 36px;
      background: var(--green-light);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--green-dark);
      font-weight: 700;
      font-size: .85rem;
    }

    .content {
      padding: 32px;
      flex: 1;
    }

    .stat-card {
      background: var(--card-bg);
      border-radius: 14px;
      border: 1px solid var(--border);
      padding: 22px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      transition: box-shadow .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); }
    .stat-icon {
      width: 46px; height: 46px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem;
      flex-shrink: 0;
    }
    .stat-icon.green  { background: #dcfce7; color: #16a34a; }
    .stat-icon.blue   { background: #dbeafe; color: #2563eb; }
    .stat-icon.orange { background: #ffedd5; color: #ea580c; }
    .stat-icon.red    { background: #fee2e2; color: #dc2626; }
    .stat-label { font-size: .75rem; color: var(--text-muted); font-weight: 500; margin-bottom: 2px; text-transform: uppercase; letter-spacing: .04em; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: var(--text-main); }

    .table-card {
      background: var(--card-bg);
      border-radius: 14px;
      border: 1px solid var(--border);
      overflow: hidden;
    }
    .table-card-header {
      padding: 20px 24px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid var(--border);
      flex-wrap: wrap;
      gap: 12px;
    }
    .table-card-header h5 {
      margin: 0;
      font-size: 1rem;
      font-weight: 600;
    }
    .search-box {
      position: relative;
    }
    .search-box input {
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 8px 12px 8px 36px;
      font-size: .85rem;
      font-family: 'Sora', sans-serif;
      background: var(--body-bg);
      color: var(--text-main);
      outline: none;
      transition: border-color .2s, box-shadow .2s;
      width: 220px;
    }
    .search-box input:focus {
      border-color: var(--green);
      box-shadow: 0 0 0 3px rgba(34,197,94,.12);
    }
    .search-box i {
      position: absolute;
      left: 11px; top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: .85rem;
    }

    .btn-excel {
      background: #fff;
      color: #16a34a;
      border: 1.5px solid #bbf7d0;
      border-radius: 8px;
      padding: 8px 16px;
      font-family: 'Sora', sans-serif;
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      display: flex; align-items: center; gap: 6px;
      transition: background .2s, border-color .2s, transform .15s;
    }
    .btn-excel:hover { background: #dcfce7; border-color: #86efac; transform: translateY(-1px); }

    .btn-import {
      background: #fff;
      color: #2563eb;
      border: 1.5px solid #bfdbfe;
      border-radius: 8px;
      padding: 8px 16px;
      font-family: 'Sora', sans-serif;
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      display: flex; align-items: center; gap: 6px;
      transition: background .2s, border-color .2s, transform .15s;
    }
    .btn-import:hover { background: #dbeafe; border-color: #93c5fd; transform: translateY(-1px); }

    .btn-add {
      background: var(--green);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 8px 16px;
      font-family: 'Sora', sans-serif;
      font-size: .85rem;
      font-weight: 600;
      cursor: pointer;
      display: flex; align-items: center; gap: 6px;
      transition: background .2s, transform .15s;
    }
    .btn-add:hover { background: var(--green-dark); transform: translateY(-1px); }

    /* table */
    .table-responsive { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    thead th {
      padding: 12px 18px;
      font-size: .72rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .06em;
      color: var(--text-muted);
      background: #f8fafc;
      border-bottom: 1px solid var(--border);
      white-space: nowrap;
    }
    tbody tr {
      border-bottom: 1px solid var(--border);
      transition: background .15s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f8fafc; }
    tbody td {
      padding: 14px 18px;
      font-size: .875rem;
      vertical-align: middle;
      color: var(--text-main);
    }

    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-avatar {
      width: 34px; height: 34px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: .75rem;
      font-weight: 700;
      flex-shrink: 0;
    }
    .user-name { font-weight: 600; font-size: .875rem; }
    .user-email-cell { color: var(--text-muted); font-size: .83rem; }

    .badge-status {
      display: inline-flex; align-items: center; gap: 5px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: .72rem;
      font-weight: 600;
      letter-spacing: .02em;
    }
    .badge-status .dot-s {
      width: 6px; height: 6px; border-radius: 50%;
    }
    .badge-status.active  { background: #dcfce7; color: #15803d; }
    .badge-status.active .dot-s { background: #22c55e; }
    .badge-status.expired { background: #fee2e2; color: #b91c1c; }
    .badge-status.expired .dot-s { background: #ef4444; }
    .badge-status.warning { background: #fef9c3; color: #92400e; }
    .badge-status.warning .dot-s { background: #f59e0b; }

    .date-cell { font-size: .83rem; color: var(--text-muted); white-space: nowrap; }
    .date-expiry { font-size: .83rem; font-weight: 500; white-space: nowrap; }
    .date-expiry.danger { color: var(--danger); }
    .date-expiry.warn   { color: var(--warning); }
    .date-expiry.ok     { color: #15803d; }

    .action-btns { display: flex; gap: 6px; }
    .btn-icon {
      width: 32px; height: 32px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: .85rem;
      border: 1px solid var(--border);
      background: #fff;
      cursor: pointer;
      transition: background .15s, border-color .15s, color .15s, transform .15s;
    }
    .btn-icon:hover { transform: translateY(-1px); }
    .btn-icon.edit:hover  { background: #dbeafe; border-color: #93c5fd; color: #1d4ed8; }
    .btn-icon.del:hover   { background: #fee2e2; border-color: #fca5a5; color: #dc2626; }

    /* pagination */
    .table-footer {
      padding: 14px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-top: 1px solid var(--border);
      font-size: .8rem;
      color: var(--text-muted);
      flex-wrap: wrap;
      gap: 10px;
    }
    .pagination-btns { display: flex; gap: 4px; }
    .pg-btn {
      width: 30px; height: 30px;
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
      border: 1px solid var(--border);
      background: #fff;
      font-size: .78rem;
      font-weight: 600;
      cursor: pointer;
      color: var(--text-main);
      transition: background .15s, color .15s, border-color .15s;
      font-family: 'Sora', sans-serif;
    }
    .pg-btn:hover, .pg-btn.active { background: var(--green); color: #fff; border-color: var(--green); }
    .pg-btn:disabled { opacity: .4; cursor: not-allowed; }

    .modal-content { border: none; border-radius: 16px; font-family: 'Sora', sans-serif; }
    .modal-header { border-bottom: 1px solid var(--border); padding: 20px 24px 16px; }
    .modal-title { font-weight: 700; font-size: 1rem; }
    .modal-body { padding: 20px 24px; }
    .modal-footer { border-top: 1px solid var(--border); padding: 16px 24px; }
    .form-label { font-size: .83rem; font-weight: 600; color: var(--text-main); margin-bottom: 6px; }
    .form-control, .form-select {
      font-family: 'Sora', sans-serif;
      font-size: .875rem;
      border-radius: 8px;
      border: 1px solid var(--border);
      padding: 9px 12px;
    }
    .form-control:focus, .form-select:focus {
      border-color: var(--green);
      box-shadow: 0 0 0 3px rgba(34,197,94,.12);
    }
    .btn-save {
      background: var(--green);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 9px 20px;
      font-family: 'Sora', sans-serif;
      font-size: .875rem;
      font-weight: 600;
      cursor: pointer;
      transition: background .2s;
    }
    .btn-save:hover { background: var(--green-dark); }
    .btn-cancel {
      background: #f1f5f9;
      color: var(--text-main);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 9px 20px;
      font-family: 'Sora', sans-serif;
      font-size: .875rem;
      font-weight: 600;
      cursor: pointer;
    }

    /* delete modal */
    .delete-icon {
      width: 60px; height: 60px;
      background: #fee2e2;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem;
      color: #dc2626;
      margin: 0 auto 16px;
    }

    @media (max-width: 767px) {
      .sidebar { transform: translateX(-100%); }
      .main-wrap { margin-left: 0; }
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    .spin-icon { display: inline-block; animation: spin .7s linear infinite; }
  </style>
</head>
<body>

<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="{{ asset('images/fontecred_logo-colored.png') }}" alt="FonteCred" style="max-height:40px;width:auto;">
  </div>
  <ul class="sidebar-nav">
    <li><a href="#" class="active"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
  </ul>
  <div class="sidebar-footer">
    <a href="#" id="logoutBtn" onclick="logout(event)"><i class="bi bi-box-arrow-left" id="logoutIcon"></i> <span id="logoutText">Sair</span></a>
  </div>
</aside>

<div class="main-wrap">

  <header class="topbar">
    <span class="topbar-title">Gerenciamento de Usuários</span>
    <div class="topbar-right">
      <div class="avatar">AD</div>
    </div>
  </header>

  <main class="content">

    <div class="row g-3 mb-4">
      <div class="col-6 col-xl-3">
        <div class="stat-card">
          <div class="stat-icon green"><i class="bi bi-people-fill"></i></div>
          <div>
            <div class="stat-label">Total</div>
            <div class="stat-value" id="statTotal">—</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-xl-3">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="bi bi-person-check-fill"></i></div>
          <div>
            <div class="stat-label">Ativos</div>
            <div class="stat-value" id="statActive">—</div>
          </div>
        </div>
      </div>
      <div class="col-6 col-xl-3">
        <div class="stat-card">
          <div class="stat-icon red"><i class="bi bi-person-x-fill"></i></div>
          <div>
            <div class="stat-label">Expirados</div>
            <div class="stat-value" id="statExpired">—</div>
          </div>
        </div>
      </div>
    </div>

    <div class="table-card">
      <div class="table-card-header">
        <h5>Usuários</h5>
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" placeholder="Buscar usuário..." oninput="filterTable()"/>
          </div>
          <button class="btn-excel" onclick="exportExcel()">
            <i class="bi bi-file-earmark-excel"></i> Exportar Excel
          </button>
          <input type="file" id="csvFileInput" accept=".csv" style="display:none;" onchange="importCSV(event)"/>
          <button class="btn-import" onclick="document.getElementById('csvFileInput').click()">
            <i class="bi bi-upload"></i> Importar CSV
          </button>
          <button class="btn-add" onclick="openModal('add')">
            <i class="bi bi-plus-lg"></i> Novo Usuário
          </button>
        </div>
      </div>

      <div class="table-responsive">
        <table id="userTable">
          <thead>
            <tr>
              <th>Usuário</th>
              <th>Email</th>
              <th>Status</th>
              <th>Expiração</th>
              <th>Cadastrado em</th>
              <th style="text-align:center;">Ações</th>
            </tr>
          </thead>
          <tbody id="tableBody">
          </tbody>
        </table>
      </div>

      <div class="table-footer">
        <span id="paginationInfo">Mostrando 1–8 de 12 registros</span>
        <div class="pagination-btns" id="paginationBtns"></div>
      </div>
    </div>

  </main>
</div>

@include('modals.user-modal')

@include('modals.delete-modal')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const COLORS = [
    ['#dbeafe','#1d4ed8'], ['#fce7f3','#be185d'], ['#ede9fe','#6d28d9'],
    ['#ffedd5','#c2410c'], ['#dcfce7','#15803d'], ['#fef9c3','#92400e'],
    ['#e0f2fe','#0369a1'], ['#f3e8ff','#7e22ce']
  ];

  let users = [];

  let filtered = [...users];
  let currentPage = 1;
  const PER_PAGE = 8;
  let deleteIndex = null;
  let userModal, deleteModal;

  function initials(name) {
    return name.split(' ').slice(0,2).map(w => w[0].toUpperCase()).join('');
  }
  function colorFor(i) {
    return COLORS[i % COLORS.length];
  }
  function formatDate(d) {
    const [y,m,day] = d.split('-');
    return `${day}/${m}/${y}`;
  }
  function expiryClass(d) {
    const today = new Date(); today.setHours(0,0,0,0);
    const exp = new Date(d);
    const diff = (exp - today) / 86400000;
    if (diff < 0) return 'danger';
    if (diff <= 30) return 'warn';
    return 'ok';
  }
  function statusBadge(s) {
    const map = { active:'active', warning:'warning', expired:'expired' };
    const label = { active:'Ativo', warning:'Expirando', expired:'Expirado' };
    return `<span class="badge-status ${map[s]}"><span class="dot-s"></span>${label[s]}</span>`;
  }

  function renderTable() {
    const start = (currentPage - 1) * PER_PAGE;
    const page  = filtered.slice(start, start + PER_PAGE);
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = page.map((u, localIdx) => {
      const globalIdx = users.indexOf(u);
      const [bg, fg] = colorFor(globalIdx);
      const ec = expiryClass(u.expiry);
      return `
        <tr>
          <td>
            <div class="user-cell">
              <div class="user-avatar" style="background:${bg};color:${fg}">${initials(u.name)}</div>
              <span class="user-name">${u.name}</span>
            </div>
          </td>
          <td class="user-email-cell">${u.email}</td>
          <td>${statusBadge(u.status)}</td>
          <td class="date-expiry ${u.expiry ? expiryClass(u.expiry) : ''}">${u.expiry ? formatDate(u.expiry) : '—'}</td>
          <td class="date-cell">${u.created !== '—' ? formatDate(u.created) : '—'}</td>
          <td style="text-align:center;">
            <div class="action-btns" style="justify-content:center;">
              <button class="btn-icon edit" title="Editar" onclick="openModal('edit', ${globalIdx})"><i class="bi bi-pencil"></i></button>
              <button class="btn-icon del"  title="Excluir" onclick="openDelete(${globalIdx})"><i class="bi bi-trash3"></i></button>
            </div>
          </td>
        </tr>`;
    }).join('');
    renderPagination();
  }

  function renderPagination() {
    const total = filtered.length;
    const pages = Math.ceil(total / PER_PAGE);
    const start = (currentPage - 1) * PER_PAGE + 1;
    const end   = Math.min(currentPage * PER_PAGE, total);
    document.getElementById('paginationInfo').textContent =
      total === 0 ? 'Nenhum registro encontrado' : `Mostrando ${start}–${end} de ${total} registros`;

    const container = document.getElementById('paginationBtns');
    const MAX = 5;
    let startPage, endPage;
    if (pages <= MAX) {
      startPage = 1; endPage = pages;
    } else {
      const half = Math.floor(MAX / 2);
      startPage = Math.max(1, currentPage - half);
      endPage   = startPage + MAX - 1;
      if (endPage > pages) { endPage = pages; startPage = endPage - MAX + 1; }
    }

    let html = `<button class="pg-btn" onclick="goPage(${currentPage-1})" ${currentPage===1?'disabled':''}>‹</button>`;
    if (startPage > 1) {
      html += `<button class="pg-btn" onclick="goPage(1)">1</button>`;
      if (startPage > 2) html += `<span class="pg-btn" style="cursor:default;border:none;">…</span>`;
    }
    for (let p = startPage; p <= endPage; p++) {
      html += `<button class="pg-btn ${p===currentPage?'active':''}" onclick="goPage(${p})">${p}</button>`;
    }
    if (endPage < pages) {
      if (endPage < pages - 1) html += `<span class="pg-btn" style="cursor:default;border:none;">…</span>`;
      html += `<button class="pg-btn" onclick="goPage(${pages})">${pages}</button>`;
    }
    html += `<button class="pg-btn" onclick="goPage(${currentPage+1})" ${currentPage===pages||pages===0?'disabled':''}>›</button>`;
    container.innerHTML = html;
  }

  function goPage(p) {
    const pages = Math.ceil(filtered.length / PER_PAGE);
    if (p < 1 || p > pages) return;
    currentPage = p;
    renderTable();
  }

  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    filtered = users.filter(u =>
      u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)
    );
    currentPage = 1;
    renderTable();
  }

  function openModal(mode, idx) {
    document.getElementById('editIndex').value = idx ?? '';
    if (mode === 'edit' && idx !== undefined) {
      const u = users[idx];
      document.getElementById('modalTitle').textContent  = 'Editar Usuário';
      document.getElementById('inputName').value         = u.name;
      document.getElementById('inputEmail').value        = u.email;
      document.getElementById('inputPassword').value     = '';
      document.getElementById('inputExpiry').value       = u.expiry ?? '';
      document.getElementById('passwordHint').style.display = '';
      document.getElementById('passwordLabel').textContent  = 'Senha';
    } else {
      document.getElementById('modalTitle').textContent  = 'Novo Usuário';
      document.getElementById('inputName').value         = '';
      document.getElementById('inputEmail').value        = '';
      document.getElementById('inputPassword').value     = '';
      document.getElementById('inputExpiry').value       = '';
      document.getElementById('passwordHint').style.display = 'none';
      document.getElementById('passwordLabel').textContent  = 'Senha';
    }
    userModal.show();
  }

  async function saveUser() {
    const name     = document.getElementById('inputName').value.trim();
    const email    = document.getElementById('inputEmail').value.trim();
    const password = document.getElementById('inputPassword').value;
    const expiry   = document.getElementById('inputExpiry').value || null;
    if (!name || !email) { alert('Preencha todos os campos obrigatórios.'); return; }

    const idx   = document.getElementById('editIndex').value;
    const today = new Date().toISOString().split('T')[0];
    const token = localStorage.getItem('api_token');
    const isEdit = idx !== '';

    if (!isEdit && !password) { alert('Informe a senha para o novo usuário.'); return; }

    const saveBtn = document.querySelector('#userModal .btn-save');
    saveBtn.disabled = true;
    saveBtn.textContent = 'Salvando...';

    try {
      let res, data;

      if (isEdit) {
        const user = users[parseInt(idx)];
        const body = { name, email, expiration_date: expiry };
        if (password) body.password = password;
        res  = await fetch(`/update_user/${user.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify(body)
        });
        data = await res.json();
        if (!res.ok) { alert(data.error || 'Erro ao atualizar usuário.'); return; }
        const isExpired = expiry && new Date(expiry) < new Date(today);
        const entry = { ...user, name, email, expiry, status: isExpired ? 'expired' : 'active' };
        users[parseInt(idx)] = entry;
      } else {
        res  = await fetch('/create_user', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ name, email, password, expiration_date: expiry })
        });
        data = await res.json();
        if (!res.ok) { alert(data.error || 'Erro ao criar usuário.'); return; }
        const isExpired = expiry && new Date(expiry) < new Date(today);
        users.push({
          id:      data.user?.id ?? null,
          name, email,
          expiry,
          status:  isExpired ? 'expired' : 'active',
          created: today
        });
      }

      filtered = [...users];
      updateStats();
      filterTable();
      userModal.hide();
    } catch (e) {
      alert('Erro ao conectar com o servidor.');
    } finally {
      saveBtn.disabled = false;
      saveBtn.textContent = 'Salvar';
    }
  }

  function openDelete(idx) {
    deleteIndex = idx;
    deleteModal.show();
  }

  async function importCSV(event) {
    const file = event.target.files[0];
    if (!file) return;
    const token = localStorage.getItem('api_token');

    // Salva o arquivo no servidor
    const form = new FormData();
    form.append('file', file);
    try {
      const uploadRes = await fetch('/upload_csv', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token,
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: form
      });
      if (!uploadRes.ok) {
        const err = await uploadRes.json().catch(() => ({}));
        console.warn('Erro ao salvar CSV no servidor:', err);
      } else {
        const result = await uploadRes.json();
        alert(`Importação concluída:\n✔ ${result.inserted} inserido(s)\n⏭ ${result.skipped} ignorado(s)${result.errors.length ? '\n⚠ Erros: ' + result.errors.join(', ') : ''}`);
        await loadUsers();
        return;
      }
    } catch (e) {
      console.warn('Aviso: não foi possível salvar o CSV no servidor.', e);
    }

    // Processa o arquivo localmente
    const reader = new FileReader();
    reader.onload = function(e) {
      const lines = e.target.result.split(/\r?\n/).filter(l => l.trim());
      if (lines.length < 2) { alert('CSV vazio ou sem dados.'); return; }
      const headers = lines[0].split(',').map(h => h.trim().toLowerCase());
      const today   = new Date().toISOString().split('T')[0];
      const todayD  = new Date(today);
      let added = 0;
      for (let i = 1; i < lines.length; i++) {
        const cols = lines[i].split(',').map(c => c.trim());
        if (cols.length < headers.length) continue;
        const row = {};
        headers.forEach((h, idx) => row[h] = cols[idx] ?? '');
        const expiry  = row['expiration_date'] || row['expiry'] || null;
        const expired = expiry && new Date(expiry) < todayD;
        users.push({
          id:      null,
          name:    row['name']  || row['nome']  || '',
          email:   row['email'] || '',
          expiry:  expiry,
          status:  expired ? 'expired' : 'active',
          created: row['created_at'] ? row['created_at'].split('T')[0] : today,
        });
        added++;
      }
      event.target.value = '';
      filtered = [...users];
      updateStats();
      filterTable();
      alert(`${added} registro(s) importado(s).`);
    };
    reader.readAsText(file);
  }

  function exportExcel() {
    const statusLabel = { active: 'Ativo', warning: 'Expirando', expired: 'Expirado' };
    const data = filtered.map(u => ({
      'Nome':                    u.name,
      'Email':                   u.email,
      'Status':                  statusLabel[u.status],
      'Data de Expiração':       formatDate(u.expiry),
      'Cadastrado em':           formatDate(u.created),
    }));
    const ws = XLSX.utils.json_to_sheet(data);
    // Column widths
    ws['!cols'] = [{ wch: 28 }, { wch: 34 }, { wch: 14 }, { wch: 22 }, { wch: 18 }];
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Usuários');
    XLSX.writeFile(wb, 'usuarios.xlsx');
  }

  async function confirmDelete() {
    if (deleteIndex === null) return;
    const user = users[deleteIndex];
    const token = localStorage.getItem('api_token');

    try {
      const res = await fetch(`/delete_user/${user.id}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token,
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      });

      if (!res.ok) {
        const data = await res.json();
        alert(data.error || 'Erro ao excluir usuário.');
        return;
      }
    } catch (e) {
      alert('Erro ao conectar com o servidor.');
      return;
    }

    users.splice(deleteIndex, 1);
    deleteIndex = null;
    filtered = [...users];
    updateStats();
    if ((currentPage - 1) * PER_PAGE >= filtered.length && currentPage > 1) currentPage--;
    filterTable();
    deleteModal.hide();
  }

  async function logout(e) {
    e.preventDefault();
    const btn  = document.getElementById('logoutBtn');
    const icon = document.getElementById('logoutIcon');
    const text = document.getElementById('logoutText');
    btn.style.pointerEvents = 'none';
    btn.style.opacity = '0.6';
    icon.className = 'bi bi-arrow-repeat spin-icon';
    text.textContent = 'Saindo...';
    const token = localStorage.getItem('api_token');
    try {
      await fetch('/logout', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token,
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      });
    } finally {
      localStorage.removeItem('api_token');
      window.location.href = '/';
    }
  }

  async function loadUsers() {
    const token = localStorage.getItem('api_token');
    if (!token) { window.location.href = '/'; return; }
    try {
      const res = await fetch('/users', {
        headers: {
          'Accept': 'application/json',
          'Authorization': 'Bearer ' + token
        }
      });
      if (res.status === 401 || res.status === 403) {
        localStorage.removeItem('api_token');
        window.location.href = '/';
        return;
      }
      const data = await res.json();
      const today = new Date(); today.setHours(0,0,0,0);
      users = data.map(u => {
        const expiry = u.expiration_date ?? u.expiry ?? null;
        const isExpired = expiry && new Date(expiry) < today;
        return {
          id:      u.id,
          name:    u.name,
          email:   u.email,
          expiry:  expiry,
          status:  isExpired ? 'expired' : (u.status ?? 'active'),
          created: u.created_at ? u.created_at.split('T')[0] : '—',
        };
      });
      filtered = [...users];
      updateStats();
      renderTable();
    } catch (e) {
      console.error('Erro ao carregar usuários:', e);
    }
  }

  function updateStats() {
    document.getElementById('statTotal').textContent   = users.length;
    document.getElementById('statActive').textContent  = users.filter(u => u.status === 'active').length;
    document.getElementById('statExpired').textContent = users.filter(u => u.status === 'expired').length;
  }

  document.addEventListener('DOMContentLoaded', () => {
    userModal   = new bootstrap.Modal(document.getElementById('userModal'));
    deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    loadUsers();
  });
</script>
</body>
</html>
