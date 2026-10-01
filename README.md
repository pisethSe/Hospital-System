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
