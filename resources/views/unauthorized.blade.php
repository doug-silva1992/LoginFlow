<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Acesso Negado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Sora', sans-serif;
      background: #f1f5f9;
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card-unauth {
      background: #fff;
      border-radius: 20px;
      border: 1px solid #e2e8f0;
      padding: 48px 40px;
      text-align: center;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 8px 32px rgba(0,0,0,.07);
    }
    .icon-wrap {
      width: 72px; height: 72px;
      background: #fee2e2;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 2rem;
      color: #dc2626;
      margin: 0 auto 24px;
    }
    h1 {
      font-size: 1.35rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 10px;
    }
    p {
      font-size: .9rem;
      color: #64748b;
      margin-bottom: 32px;
      line-height: 1.6;
    }
    .btn-logout {
      background: #ef4444;
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 11px 28px;
      font-family: 'Sora', sans-serif;
      font-size: .9rem;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex; align-items: center; gap: 8px;
      transition: background .2s, transform .15s;
    }
    .btn-logout:hover { background: #dc2626; transform: translateY(-1px); }
    .btn-logout:disabled { opacity: .7; cursor: not-allowed; transform: none; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .spin-icon { display: inline-block; animation: spin .7s linear infinite; }
  </style>
</head>
<body>
  <div class="card-unauth">
    <div class="icon-wrap">
      <i class="bi bi-shield-lock-fill"></i>
    </div>
    <h1>Acesso não autorizado</h1>
    <p>Você não tem permissão para acessar esta página.<br/>Entre em contato com o administrador do sistema.</p>
    <button class="btn-logout" id="logoutBtn" onclick="logout()">
      <i class="bi bi-box-arrow-left" id="logoutIcon"></i> <span id="logoutText">Sair</span>
    </button>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    async function logout() {
      const btn  = document.getElementById('logoutBtn');
      const icon = document.getElementById('logoutIcon');
      const text = document.getElementById('logoutText');
      btn.disabled = true;
      icon.className = 'bi bi-arrow-repeat spin-icon';
      text.textContent = 'Saindo...';
      const token = localStorage.getItem('api_token');
      try {
        await fetch('/logout', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + (token ?? ''),
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });
      } finally {
        localStorage.removeItem('api_token');
        window.location.href = '/';
      }
    }
  </script>
</body>
</html>
