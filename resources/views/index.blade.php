<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <title>Signin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-small-fontecred.png') }}"/>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="{{ asset('css/sign-in.css') }}" rel="stylesheet" />
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: #0000001a;
        border: solid rgba(0, 0, 0, 0.15);
        border-width: 1px 0;
        box-shadow:
          inset 0 0.5em 1.5em #0000001a,
          inset 0 0.125em 0.5em #00000026;
      }
      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }
      .bi {
        vertical-align: -0.125em;
        fill: currentColor;
      }
      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }
      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }
      @keyframes spin { to { transform: rotate(360deg); } }
      .spin-icon { display: inline-block; animation: spin .7s linear infinite; }
    </style>
  </head>
  <body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
      <form id="loginForm">
        <img
          class="mb-4"
          src="{{ asset('images/logo-small-fontecred.png') }}"
          alt=""
          width="72"
          height="67"
        />
        <h1 class="h3 mb-3 fw-normal">Login</h1>
        <div id="loginError" class="alert alert-danger d-none" role="alert"></div>
        <div class="form-floating">
          <input
            type="email"
            class="form-control"
            id="floatingInput"
            placeholder="name@example.com"
          />
          <label for="floatingInput">Email</label>
        </div>
        <div class="form-floating">
          <input
            type="password"
            class="form-control"
            id="floatingPassword"
            placeholder="Password"
          />
          <label for="floatingPassword">Senha</label>
        </div>
        <button class="btn btn-dark w-100 py-2 d-flex align-items-center justify-content-center gap-2" type="submit" id="btnEntrar">
          <i class="bi bi-box-arrow-in-right" id="btnIcon"></i>
          <span id="btnText">Entrar</span>
        </button>
      </form>
    </main>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script>
      document.getElementById('loginForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const btn = document.getElementById('btnEntrar');
        const errorBox = document.getElementById('loginError');
        const email = document.getElementById('floatingInput').value;
        const password = document.getElementById('floatingPassword').value;

        btn.disabled = true;
        document.getElementById('btnIcon').className = 'bi bi-arrow-repeat spin-icon';
        document.getElementById('btnText').textContent = 'Aguarde...';
        errorBox.classList.add('d-none');

        try {
          const response = await fetch('/login', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email, password })
          });

          const data = await response.json();

          if (response.ok) {
            localStorage.setItem('api_token', data.token);
            if (data.is_admin) {
              window.location.href = '/dashboard';
            } else {
              window.location.href = '/unauthorized';
            }
            return;
          } else {
            errorBox.textContent = data.message || 'Credenciais inválidas.';
            errorBox.classList.remove('d-none');
          }
        } catch (err) {
          errorBox.textContent = 'Erro ao conectar com o servidor.';
          errorBox.classList.remove('d-none');
        }
        btn.disabled = false;
        document.getElementById('btnIcon').className = 'bi bi-box-arrow-in-right';
        document.getElementById('btnText').textContent = 'Entrar';
      });
    </script>
  </body>
</html>
