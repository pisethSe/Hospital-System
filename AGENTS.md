# AGENTS.md — Hospital System

Guidance for AI coding agents (Cline, Claude Code, Codex, ...) working in
this repository.

## Stack

| Layer    | Technology                                    |
| -------- | --------------------------------------------- |
| Frontend | React 18, Tailwind CSS v4, shadcn/ui (Base UI), Vite |
| Backend  | Laravel 11 REST API, Sanctum token auth       |
| Database | MySQL (legacy `his_*` tables, original column names) |

```
frontend/   React SPA — landing, login, admin + doctor panels
backend/    Laravel API — auth + module controllers
DATABASE FILE/   Legacy MySQL dump (reference only)
```

## Hard rules

1. **Do not modify `backend/`** — its business logic and algorithms are
   intentionally preserved from the original system:
   - `sha1(md5($password))` password hashing
   - record-number generation: `substr(str_shuffle($charset), 1, $length)`
   - doctor sign-in by doctor ID, admin sign-in by email
   - discharge sets `pat_discharge_status` + `pat_walk_out_date`
2. **Do not modify `DATABASE FILE/`** — legacy schema reference.
3. **Frontend-only by default.** API contracts are fixed; the UI adapts to
   them, never the reverse.

## Environment notes

- Node/npm are not installed globally. Use the portable Node:
  `export PATH="$HOME/hospital-tools/node-v20.18.1-darwin-arm64/bin:$PATH"`
- PHP 8.2+, Composer and MySQL are not installed on this machine. If the
  user asks to run the backend, they must install those first.
- Frontend dev server: `cd frontend && npm run dev` → http://localhost:5173
  (proxies `/api` to `http://localhost:8000`).
- Backend dev server: `cd backend && php artisan serve` → http://localhost:8000

## Design system ("chart room")

- Light clinical surfaces, hairline borders, pen-ink text
- Deep scrub-teal `--primary`, used only for actions and active states
- Medical red only for destructive actions; amber only for pending states
- IBM Plex Sans for text; IBM Plex Mono strictly for record codes
- Semantic tokens only — `bg-primary`, `text-muted-foreground` — never raw
  hex values or `dark:` overrides
- Compose shadcn/ui components from `frontend/src/components/ui/`;
  forms use `FieldGroup` + `Field`, spacing uses `gap-*` (never `space-y-*`)
- UI design guidance lives in `.agents/skills/` — read before design work

## Demo accounts

- Admin: `admin@hospital.com` / `admin123` (sign-in by email)
- Doctor: ID `pkd` / `pkd123` (sign-in by doctor ID)
