# Fácil Consulta

Monorepo com API em **Laravel 12** (`backend/`) e interface em **Vue 3 + Vite** (`frontend/`).

## Requisitos

| Parte     | Requisito                          |
|----------|-------------------------------------|
| Backend  | PHP **8.2+**, Composer, extensão **SQLite** habilitada |
| Frontend | Node.js **18+** (recomendado LTS) e npm |

## Estrutura

- `backend/` — API REST (`/api/...`), autenticação com **Laravel Sanctum** (token Bearer).
- `frontend/` — SPA que consome a API (URLs da API apontam para `http://localhost:8000` nos componentes).

## Backend (Laravel)

No diretório `backend/`:

1. **Instalar dependências**

   ```bash
   composer install
   ```

2. **Ambiente**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   O `.env.example` usa **SQLite** (`DB_CONNECTION=sqlite`). Crie o arquivo do banco:

   ```bash
   touch database/database.sqlite
   ```

3. **Banco e dados de exemplo**

   ```bash
   php artisan migrate --seed
   ```

   O seeder cria médicos, agendas e um paciente de teste (útil para login local).

4. **Subir o servidor HTTP**

   ```bash
   php artisan serve
   ```

   A API fica em **http://localhost:8000** (prefixo padrão: **http://localhost:8000/api**).

5. **Testes (opcional)**

   ```bash
   composer test
   ```

   ou `php artisan test`.

### Saúde da API

- Rota de health do framework: `GET http://localhost:8000/up`

## Frontend (Vue + Vite)

No diretório `frontend/`:

1. **Instalar dependências**

   ```bash
   npm install
   ```

2. **Ambiente**

   Existe um arquivo `.env` com `VITE_API_URL=http://localhost:8000`. Hoje as chamadas `fetch` nos componentes usam a URL fixa `http://localhost:8000`; para desenvolvimento local, mantenha o backend na porta **8000** ou alinhe as URLs no código.

3. **Modo desenvolvimento**

   ```bash
   npm run dev
   ```

   O Vite costuma expor a aplicação em **http://localhost:5173**.

4. **Build de produção**

   ```bash
   npm run build
   npm run preview
   ```

## Fluxo típico de desenvolvimento

1. Terminal 1 — backend: `cd backend && php artisan serve`
2. Terminal 2 — frontend: `cd frontend && npm run dev`
3. Abrir o endereço exibido pelo Vite no navegador.

## Paciente de exemplo (após `migrate --seed`)

| Campo   | Valor                    |
|---------|--------------------------|
| E-mail  | `erickcordeiroa@gmail.com` |
| Senha   | `123456`                 |

Altere ou remova esse usuário em ambientes reais; trate apenas como dado local de desenvolvimento.

## Endpoints principais da API

| Método | Rota | Autenticação |
|--------|------|--------------|
| POST | `/api/auth/register` | Não |
| POST | `/api/auth/login` | Não |
| POST | `/api/auth/logout` | Sanctum |
| GET | `/api/auth/me` | Sanctum |
| GET | `/api/doctors` | Não |
| GET/POST | `/api/appointments` | Sanctum |

## Observações

- **Filas**: o `.env.example` define `QUEUE_CONNECTION=database`. Para processar jobs em segundo plano no dia a dia, use `php artisan queue:work` (ou o script `composer dev` do Laravel, que também sobe fila e Vite integrado ao template — neste repositório o frontend é separado, então o fluxo mais simples é `serve` + `npm run dev`).
- **MySQL/PostgreSQL**: ajuste `DB_*` no `.env` e rode `php artisan migrate --seed` de novo, se preferir outro SGBD.
