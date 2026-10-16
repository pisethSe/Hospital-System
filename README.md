# Hospital Management System

A full-stack **Hospital Management System (HMS)** — modernized from a legacy PHP application to:

| Layer    | Technology                     |
| -------- | ------------------------------ |
| Frontend | **React 18** + **Tailwind CSS v4** + **shadcn/ui** (Vite) |
| Backend  | **Laravel 12** (REST API, Sanctum auth) |
| Database | **MySQL**                      |

Two roles are supported, mapping to the original login tables:

- **Admin** (`his_admin`) — signs in with **email + password**
- **Doctor** (`his_docs`) — signs in with **doctor ID + password** (e.g. `pkd`), exactly like the legacy system

## Project structure

```
Hospital-System/
├── backend/               # Laravel API
│   ├── app/
│   │   ├── Http/Controllers/   # Auth + module controllers
│   │   ├── Http/Middleware/    # Role gate (role:admin, role:doctor)
│   │   ├── Models/             # 18 models (his_* tables preserved)
│   │   └── Support/            # Legacy algorithm helpers
│   ├── database/migrations/    # 18 legacy tables + Sanctum tokens
│   ├── database/seeders/       # Demo accounts
│   ├── routes/api.php          # All API endpoints
│   └── config/
├── frontend/              # React + Tailwind SPA
│   └── src/
│       ├── api/                # Axios client (Sanctum bearer token)
│       ├── components/         # App shell + shared UI
│       ├── context/            # Auth state
│       └── pages/              # Landing, login, dashboard, modules
└── DATABASE FILE/         # Original MySQL dump (legacy reference)
```

## Modules

- **Dashboard** — live statistics + recent activity (role-aware)
- **Patients** — register (auto-generated patient number), edit, discharge, delete, search/filter
- **Doctors** — accounts, departments, login IDs
- **Laboratory** — request tests, record results, pending filter
- **Surgery / Theatre** — theatre patients, surgeon assignment, status
- **Prescriptions** — with medicines list (name / qty / time)
- **Pharmacy** — medicines, categories, vendors
- **Patient Vitals** — temperature, pulse, respiration, blood pressure

## Prerequisites

- PHP **8.2+** (Laravel 12), Composer
- Node.js **18+**, npm
- MySQL / MariaDB

## Backend setup

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate

# Create the database (or import the legacy dump from "DATABASE FILE/")
mysql -u root -e "CREATE DATABASE IF NOT EXISTS hmisphp"

php artisan migrate --seed
php artisan serve          # http://localhost:8000
```

**Testing without MySQL:** the config also ships a `sqlite` connection, so
the whole API can be exercised locally with
`DB_CONNECTION=sqlite php artisan migrate --seed` and
`DB_CONNECTION=sqlite php artisan serve` — no MySQL needed. Production
stays on MySQL.

## Production security

- **APP_DEBUG=false** in production — error pages never leak stack traces.
- **Dedicated database user** (`hospital_app`) scoped to the `hmisphp`
  database only — the app never connects as root.
- **Legacy password hashing preserved** (`sha1(md5())`) so existing
  accounts keep working, but **all new/changed passwords must be at
  least 8 characters with letters and numbers**.
- **Brute-force protection**: sign-in and password-reset endpoints are
  rate-limited to 5 attempts per minute.
- **Password reset hardening**: the public reset endpoint never returns
  the temporary password or token, and unknown emails are silently
  ignored (no account enumeration). Approvals notify the staff member
  by email (configure `MAIL_MAILER=smtp` + your server in `.env`;
  the default `log` mailer writes emails to `storage/logs/laravel.log`).
- **Token revocation**: changing a password or approving a reset
  revokes all existing API tokens for the affected accounts.
- **Security headers** on every response: `X-Frame-Options: DENY`,
  `X-Content-Type-Options: nosniff`, `Referrer-Policy`,
  `Permissions-Policy`.
- **Force HTTPS** behind a TLS proxy: set `APP_FORCE_HTTPS=true`.
- Secrets live only in `backend/.env` (gitignored, never committed).

## Production setup

The shipped configuration runs in production mode (`APP_ENV=production`,
`APP_DEBUG=false`) against a dedicated MySQL user (`hospital_app`) with
full privileges on the `hmisphp` database only — never the root user.

Setup steps:

```bash
mysql -u root -e "
CREATE DATABASE IF NOT EXISTS hmisphp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'hospital_app'@'localhost' IDENTIFIED BY 'your-password';
CREATE USER IF NOT EXISTS 'hospital_app'@'127.0.0.1' IDENTIFIED BY 'your-password';
GRANT ALL PRIVILEGES ON hmisphp.* TO 'hospital_app'@'localhost';
GRANT ALL PRIVILEGES ON hmisphp.* TO 'hospital_app'@'127.0.0.1';
FLUSH PRIVILEGES;"
```

Then set `DB_USERNAME=hospital_app` and `DB_PASSWORD=your-password` in
`backend/.env` and run `php artisan migrate --seed`.

**Profile photos:** uploaded photos are stored in
`storage/app/public/users/` and served via the public disk — run
`php artisan storage:link` once after setup so `/storage/...` resolves.

**Backups:** dump the database regularly —

```bash
mysqldump --no-tablespaces -u hospital_app -p hmisphp > backup_$(date +%F).sql
```

**Restoring:** `mysql -u hospital_app -p hmisphp < backup_2026-01-01.sql`

## Deploying to Render

The repository ships a **Render Blueprint** (`render.yaml`) that deploys
all three services at once:

| Service | Type | What it runs |
| --- | --- | --- |
| `hospital-mysql` | private service (Docker) | MySQL 8.4 with a 1 GB persistent disk — reachable only by the API |
| `hospital-api` | web service (Docker) | Laravel 12 on PHP 8.3-Apache (`backend/Dockerfile`), 1 GB disk for uploads/logs, auto-migrates on deploy |
| `hospital-web` | static site | the built React app (`frontend/dist`), SPA fallback routing included |

### One-time setup

1. Push this repository to GitHub.
2. In Render: **New → Blueprint** → select the repository → Render reads
   `render.yaml`.
3. Render prompts for the secret values (marked *sync: false*). Enter:
   - `MYSQL_ROOT_PASSWORD`, `MYSQL_PASSWORD` — pick strong passwords
   - `APP_KEY` — a 32-byte base64 key, e.g.
     `php -r "echo 'base64:'.base64_encode(random_bytes(32));"`
   - `DB_USERNAME` — `hospital_app`
   - `DB_PASSWORD` — the same value as `MYSQL_PASSWORD`
4. Deploy. On boot the API container waits for MySQL, runs migrations,
   seeds the demo accounts (only when the database is empty), caches
   config, links storage and starts Apache on Render's assigned port.

### How the pieces connect

- The frontend is built with `VITE_API_URL` = the API's Render URL, so
  it calls the API across origins (CORS already allows it via
  `FRONTEND_URL`, which the blueprint sets from the API's URL).
- Uploaded profile photos are served by the API at `/storage/...` and
  stored on the API's persistent disk.
- MySQL lives on the private network — it is never exposed publicly.

### Production checklist

- All secrets (`APP_KEY`, DB passwords) are set in the Render
  dashboard — never in the repository.
- Verify: open the `hospital-web` URL, sign in as
  `admin@hospital.com` / `admin123`, then **change the demo passwords
  immediately**.
- Backups: use Render's disk snapshots, or run the API container's
  shell: `mysqldump --no-tablespaces -u hospital_app -p hmisphp`.

## Frontend setup

```bash
cd frontend
npm install
npm run dev                # http://localhost:5173
```

The Vite dev server proxies `/api/*` to `http://localhost:8000`, so no CORS
configuration is needed in development.

## Demo accounts

| Role   | Login               | Password  |
| ------ | ------------------- | --------- |
| Admin  | `admin@hospital.com`| `admin123`|
| Admin  | `admin2@hospital.com`| `admin123`|
| Admin  | `admin3@hospital.com`| `admin123`|
| Doctor | ID `pkd`            | `pkd123`  |
| Doctor | ID `visal`          | `visal123`|
| Doctor | ID `sml`            | `sml123`  |

## Preserved legacy business logic

The conversion keeps the original system's algorithms and behaviour untouched:

- **Password hashing** — every password is double-encrypted with
  `sha1(md5($password))`, so legacy password hashes in `his_admin.ad_pwd`
  and `his_docs.doc_pwd` remain valid (`app/Support/HisPassword.php`).
- **Record numbering** — patient, surgery, lab, prescription, vendor,
  payroll and vitals numbers are generated with the original algorithm
  `substr(str_shuffle($charset), 1, $length)`
  (`app/Support/HisCode.php`).
- **Barcodes / account codes** — random numeric codes, as in the legacy
  pharmacy and accounting modules.
- **Doctor login** — doctors authenticate by **doctor ID**
  (`doc_number`), not email, matching the original `his_doc/index.php`.
- **Discharge** — sets `pat_discharge_status` and `pat_walk_out_date`,
  matching both legacy discharge handlers.
- **Schema** — all 18 `his_*` tables keep their original table and column
  names, so the legacy dump (`DATABASE FILE/hmisphp.sql`) can still be
  imported directly.

## API overview

| Method | Endpoint                        | Access          |
| ------ | ------------------------------- | --------------- |
| POST   | `/api/login/admin`              | public          |
| POST   | `/api/login/doctor`             | public          |
| GET    | `/api/me` · POST `/api/logout`  | authenticated   |
| GET    | `/api/dashboard`                | authenticated   |
| GET    | `/api/patients` · `/{id}`       | authenticated   |
| POST/PUT/DELETE | `/api/patients`        | admin           |
| POST   | `/api/patients/{id}/discharge`  | admin           |
| GET/POST/PUT/DELETE | `/api/doctors`     | admin           |
| GET/POST | `/api/lab-tests`              | admin + doctor  |
| PUT    | `/api/lab-tests/{id}/result`    | admin + doctor  |
| GET/POST | `/api/prescriptions`          | admin + doctor  |
| GET/POST | `/api/vitals`                 | admin + doctor  |
| GET/POST | `/api/pharmaceuticals`, `/api/pharmaceutical-categories`, `/api/vendors` | admin |
| GET/PUT | `/api/surgeries`               | admin           |

Authentication uses **Laravel Sanctum** bearer tokens with role abilities
(`role:admin` / `role:doctor`), enforced by the `role` middleware.
