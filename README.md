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
| Bootstrap | 5.3.3 |
| Bootstrap Icons | 1.11.3 |
| SheetJS (xlsx) | 0.18.5 |
| MySQL | 8.0 |
| Nginx | alpine |
| Docker Compose | v2 |

---

## Estrutura do projeto

```
app/
  Http/Controllers/
    Controller.php        # Anotações globais OpenAPI (Info, Server, SecurityScheme)
    AuthController.php    # Login, logout, endpoint /me
    UsersController.php   # CRUD de usuários + importação de CSV
  Models/
    User.php
resources/views/
  index.blade.php              # Página de login
  dashboard.blade.php          # Dashboard pós-login
  unauthorized.blade.php       # Tela de acesso negado
  modals/
    user-modal.blade.php       # Modal de criação/edição de usuário
    delete-modal.blade.php     # Modal de confirmação de exclusão
database/
  migrations/
  seeders/
    DatabaseSeeder.php         # Importa usuarios.csv na primeira carga
    usuarios.csv
docker/
  nginx/default.conf
  entrypoint.sh                # Cria diretórios de storage e ajusta permissões
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

> O seeder lê `database/seeders/usuarios.csv` e define `is_admin = true` automaticamente para e-mails com domínio `@fontecred.com.br`.

---

## Autenticação (Sanctum)

As rotas protegidas utilizam `auth:sanctum`. Envie o token no header:

```
Authorization: Bearer {token}
```

O token é gerado no login e armazenado no `localStorage` do browser.

### Controle de acesso por `is_admin`

Após o login, a API retorna o campo `is_admin` na resposta. O frontend redireciona para `/dashboard` se `is_admin` for `true`, ou para `/unauthorized` caso contrário.

```json
{ "token": "...", "is_admin": true }
```

### Bloqueio por expiração

Se o campo `expiration_date` do usuário estiver no passado, o login retorna **HTTP 403** com a mensagem `"Usuário Expirado."` e nenhum token é emitido.

### Rotas disponíveis

| Método | Rota | Auth | Descrição |
|---|---|---|---|
| `GET` | `/` | Não | Página de login |
| `POST` | `/login` | Não | Autentica e retorna token + `is_admin` |
| `POST` | `/logout` | Sim | Revoga o token atual |
| `GET` | `/dashboard` | Sim | Painel de gerenciamento de usuários |
| `GET` | `/unauthorized` | Não | Tela de acesso negado |
| `GET` | `/users` | Sim | Lista todos os usuários |
| `POST` | `/create_user` | Sim | Cria um novo usuário |
| `PUT` | `/update_user/{id}` | Sim | Atualiza um usuário existente |
| `DELETE` | `/delete_user/{id}` | Sim | Remove um usuário pelo ID |
| `POST` | `/upload_csv` | Sim | Importa usuários a partir de um CSV |

---

## Dashboard

O dashboard consome a API `/users` via `fetch` com o Bearer token e exibe:

- **Logo** — imagem `public/images/fontecred_logo-colored.png` na sidebar
- **Cards de estatísticas** — Total, Ativos e Expirados
- **Tabela de usuários** — nome, e-mail, status (badge), data de expiração e ações (editar/excluir)
- **Busca em tempo real** — filtra por nome ou e-mail
- **Paginação** — 8 registros por página com botões recolhidos (`…`)
- **Criar / Editar usuário** — modal com campos: nome, e-mail, senha (opcional na edição) e data de expiração
- **Excluir usuário** — confirmação em modal dedicado
- **Exportar Excel** — exporta os registros filtrados via SheetJS
- **Importar CSV** — envia o arquivo ao servidor (`POST /upload_csv`), insere usuários novos e retorna contagem de inserções/ignorados
- **Sair** — exibe spinner, chama `POST /logout`, apaga o token e redireciona ao login

### Colunas de Status

| Badge | Critério |
|---|---|
| `Ativo` (verde) | Sem data de expiração ou data futura (> 30 dias) |
| `Atenção` (amarelo) | Expira nos próximos 30 dias |
| `Expirado` (vermelho) | Data de expiração no passado |

---

## Importação via CSV

O endpoint `POST /upload_csv` aceita um arquivo `.csv` com as seguintes colunas (cabeçalho obrigatório):

| Coluna | Obrigatória | Observação |
|---|---|---|
| `name` ou `nome` | Sim | Nome do usuário |
| `email` | Sim | Deve ser único |
| `password` | Não | Se ausente, gera senha aleatória |
| `expiration_date` | Não | Formato `YYYY-MM-DD` |

- E-mails já cadastrados são ignorados (contados em `skipped`).
- `is_admin` é definido automaticamente pelo domínio `@fontecred.com.br`.
- A resposta retorna `inserted`, `skipped` e `errors`.

---

## Healthcheck do container PHP-FPM

O `docker-compose.yml` verifica se a porta 9000 do PHP-FPM está realmente aceitando conexões antes de iniciar o Nginx, evitando o erro **502 Bad Gateway** durante o startup.

```yaml
healthcheck:
  test: ["CMD-SHELL", "php -r \"$s=@fsockopen('127.0.0.1',9000,$e,$m,1);if(!$s){exit(1);}fclose($s);exit(0);\""]
  interval: 10s
  timeout: 5s
  retries: 5
  start_period: 30s
```

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

## Jobs e Agendamentos

### DeleteExpiredUsers

Remove automaticamente usuários (não-admins) cujo `expiration_date` é anterior a **6 meses** da data atual.

| Atributo | Valor |
|---|---|
| Classe | `App\Jobs\DeleteExpiredUsers` |
| Implementa | `ShouldQueue` (processado pela fila) |
| Agendamento | Mensal (`Schedule::job(...)->monthly()`) |
| Escopo | Somente usuários com `is_admin = false` |

#### Executar manualmente via Docker

**Execução direta (sem fila):**
```bash
docker exec loginflow_app php artisan tinker --execute="(new \App\Jobs\DeleteExpiredUsers())->handle()"
```

**Verificar quantos usuários seriam afetados:**
```bash
docker exec loginflow_app php artisan tinker --execute="echo \App\Models\User::whereDate('expiration_date', '<=', now()->subMonths(6))->where('is_admin', false)->count() . ' usuários elegíveis para exclusão';"
```

**Disparar via fila (requer worker ativo):**
```bash
docker exec loginflow_app php artisan tinker --execute="\App\Jobs\DeleteExpiredUsers::dispatch()"
```

**Iniciar worker da fila:**
```bash
docker exec loginflow_app php artisan queue:work
```

**Executar o scheduler manualmente:**
```bash
docker exec loginflow_app php artisan schedule:run
```

> O resultado da execução é registrado em `storage/logs/laravel.log`.

---

## License

[MIT](https://opensource.org/licenses/MIT)
