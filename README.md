# LoginFlow

Aplicação de autenticação e gerenciamento de usuários construída com **Laravel 13**, **Laravel Sanctum**, **Docker** e documentação de API via **Swagger (L5-Swagger)**.

---

## Stack

| Tecnologia | Versão |
|---|---|
| PHP | 8.3 |
| Laravel | 13 |
| Laravel Sanctum | 4.x |
| L5-Swagger (darkaonline) | 11.x |
| MySQL | 8.0 |
| Nginx | alpine |
| Docker Compose | v2 |

---

## Estrutura do projeto

```
app/
  Http/Controllers/
    Controller.php        # Anotações globais OpenAPI (Info, Server, SecurityScheme)
    AuthController.php    # Endpoints de autenticação documentados
    UsersController.php   # Endpoints de usuários (listar, excluir)
  Models/
    User.php
resources/views/
  index.blade.php         # Página de login
  dashboard.blade.php     # Dashboard pós-login com tabela de usuários
  unauthorized.blade.php  # Tela de acesso negado
database/
  migrations/
    ..._create_personal_access_tokens_table.php  # Migration do Sanctum
docker/
  nginx/default.conf
  entrypoint.sh
```

---

## Subindo o ambiente

```bash
docker compose up -d
```

Serviços disponíveis:

| Serviço | URL / Porta |
|---|---|
| Aplicação (Nginx) | http://localhost:8080 |
| MySQL | localhost:3306 |

### Primeira execução

```bash
docker exec loginflow_app php artisan migrate
docker exec loginflow_app php artisan db:seed
```

---

## Autenticação (Sanctum)

As rotas protegidas utilizam `auth:sanctum`. Envie o token no header:

```
Authorization: Bearer {token}
```

O token é gerado no login e armazenado no `localStorage` do browser.

### Controle de acesso por domínio

Ao efetuar login, o e-mail é verificado no frontend. Caso não termine em `@fontecred.com.br`, o usuário é redirecionado para a página `/unauthorized`.

### Rotas disponíveis

| Método | Rota | Auth | Descrição |
|---|---|---|---|
| `POST` | `/login` | Não | Autentica e retorna token Sanctum |
| `POST` | `/logout` | Sim | Revoga o token atual |
| `GET` | `/users` | Sim | Lista todos os usuários |
| `DELETE` | `/delete_user/{id}` | Sim | Remove um usuário pelo ID |
| `GET` | `/dashboard` | Sim | Painel de gerenciamento de usuários |
| `GET` | `/unauthorized` | Não | Tela de acesso negado |
| `GET` | `/` | Não | Página de login |

---

## Dashboard

O dashboard consome a API `/users` via `fetch` com o Bearer token e exibe:

- **Card Total** — número total de usuários retornados pela API
- **Tabela de usuários** — nome, e-mail, data de cadastro e ações
- **Busca em tempo real** — filtra por nome ou e-mail
- **Paginação** — 8 registros por página, máximo 5 botões visíveis com `…` para intervalos
- **Exportar Excel** — exporta os registros filtrados via SheetJS
- **Excluir** — chama `DELETE /delete_user/{id}` e atualiza a tabela localmente
- **Sair** — chama `POST /logout`, remove o token e redireciona para o login

---

## Documentação da API (Swagger)

A documentação é gerada com **L5-Swagger 11** usando **PHP 8 Attributes** (`#[OA\...]`).

### Acessar a UI

```
http://localhost:8080/api/documentation
```

### Regenerar a documentação

```bash
docker exec loginflow_app php artisan l5-swagger:generate
```

### Arquivo gerado

```
storage/api-docs/api-docs.json
```

### Como documentar um endpoint

```php
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/exemplo',
    summary: 'Descrição do endpoint',
    tags: ['Tag'],
    security: [['sanctum' => []]],
    responses: [
        new OA\Response(response: 200, description: 'Sucesso'),
    ]
)]
public function exemplo() { ... }
```

---

## Variáveis de ambiente (.env)

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=loginflow
DB_USERNAME=laravel
DB_PASSWORD=secret
```

---

## License

[MIT](https://opensource.org/licenses/MIT)
