# Progress Tracker

Update this file after every meaningful implementation change.

## Current Feature Unit

- `context/feature/02-auth-tenant/02-auth-activation.md`

## Current Goal

- Minimum tenant activation flow complete: superadmin can create tenant, activation token is generated, tenant admin sets password, tenant moves into trial, and login works.

## Completed

- Context kit created.
- Global project direction defined.
- Stack fixed.
- Core inbound flow approved.
- LLM configuration must be `.env` driven.
- Feature specs folder prepared.
- Laravel 13.8 foundation scaffolded.
- Docker Compose local stack added: app, Nginx, MySQL 8, Redis, queue worker, scheduler.
- Basic health endpoints added:
  - `/health`
  - `/health/db`
  - `/health/redis`
- `.env.example` now includes app, database, Redis, queue, cache, session, and LLM provider settings.
- Modular-ready base folder convention added under `app/Modules/`.
- Health endpoint feature/unit tests added.
- `docker compose up -d --build` verified.
- `php artisan test` verified.
- `php artisan route:list` verified.
- `php artisan migrate --pretend` verified.
- Docker PHP build now configures `/var/www/html` as a Git safe directory before Composer autoload generation.
- Foundation reverified through Docker Compose after rebuild.
- Login UI route added at `/login`.
- Login Blade view added with SaaS split layout, product identity, email/password fields, submit button, error message area, responsive styling, and client-side loading/disabled state.
- Login UI feature tests added for rendering, required controls, and validation error display.
- Docker image rebuilt so the non-mounted app container includes the login UI changes.
- Login unit checks verified: `php artisan test`, `php artisan route:list`, `php artisan migrate --pretend`, and internal Nginx render check for `/login`.
- Minimal auth configuration added for session-based web login.
- User roles added for `superadmin` and `tenant_admin`.
- Tenancy and activation schema added:
  - `users`
  - `tenants`
  - `tenant_user`
  - `activation_tokens`
- Tenant creation route added for authenticated superadmins at `POST /superadmin/tenants`.
- Activation screen and action added at `/activate/{token}`.
- Login POST action connected to the existing login UI.
- Activation tokens are stored as SHA-256 hashes, expire after 72 hours, and record `used_at` after successful activation.
- Tenant admins are linked to exactly their assigned tenant through `tenant_user`.
- Tenant status moves from `pending_activation` to `trial` after activation.
- Activation flow feature tests added for superadmin tenant creation, secure token generation, password activation, token expiry, one-time token use, tenant admin login, forbidden tenant creation by tenant admin, and tenant relationship isolation.
- PHPUnit env values now force sqlite in-memory / array drivers during tests so Docker service env does not push tests onto the MySQL dev container.
- Auth activation unit checks verified: `php artisan test`, `php artisan route:list`, and `php artisan migrate --pretend`.
- Superadmin seeder added and verified for local development.
- Local MySQL database seeded with superadmin user `superadmin@wa-v3.local`.
- Root route `/` now redirects to `/health` so the Nginx app port has a useful status entrypoint.
- Minimal authenticated `/dashboard` status page added as the login redirect target.
- Logout route added and verified.
- Login POST now has basic rate limiting for failed attempts.
- Activation POST now has basic rate limiting for repeated token submissions.
- Minimal superadmin tenant creation form added at `GET /superadmin/tenants/create`.
- Tenant creation POST now supports both JSON API-style responses and web form redirects with the activation link in session flash data.
- Gap-closure tests added for root redirect, dashboard/logout, superadmin web tenant creation, tenant-admin forbidden access, and login rate limiting.

## In Progress

- None.

## Next Up

- Recommended next unit: `context/feature/03-whatsapp-agent/01-baileys-gateway-skeleton.md`

## Open Questions

- Will the admin dashboard use Blade, Livewire, Inertia, or another frontend approach?
- What exact UI color palette should be used for the dashboard?
- Should UUIDs be used for all main tables from the start?
- Which Docker base image should be used for PHP? Current foundation uses `php:8.3-fpm` because Laravel 13 requires PHP 8.3+.

## Architecture Decisions

- Build as controlled workflow engine first, AI responder second.
- Use Laravel latest Modular Monolith.
- Use MySQL 8 as source of truth.
- Use Redis for queue/cache/locks.
- Use Laravel Queue Worker and Scheduler.
- Use Laravel Reverb/WebSocket for realtime dashboard.
- Use Node.js + Baileys Gateway for WhatsApp boundary.
- Use local storage for uploaded files initially.
- Use OpenAI GPT-5.3 through configurable provider adapter.
- Do not hardcode LLM model/provider in services.
- Use Nginx and Docker Compose.
- Keep phase coding out of main PRD; implementation prompts live in `context/feature/`.
- Use Laravel 13.8 with PHP 8.3 for the foundation.
- Use Predis for Redis access to keep the PHP image simple.
- Health routes are loaded through API routing without a prefix so `/health`, `/health/db`, and `/health/redis` avoid web session cookies.
- Nginx uses Docker DNS resolver for the PHP-FPM upstream so app container recreation does not leave a stale upstream IP.
- Login UI uses a minimal Blade view and is now connected to the custom session login action.
- Auth activation uses custom minimal Laravel session auth instead of Breeze/Jetstream/Fortify to keep this unit tightly scoped.
- Tenant activation policy assumption: new tenants become `trial` after successful activation.

## Session Notes

- Every new Claude Code/Codex session must start by reading `CLAUDE.md`.
- Feature specs are the execution prompts.
- Each feature spec must end with `Check when done`.
- After completing a feature unit, update this file.
- 2026-05-06: Completed feature unit `01-foundation/01-project-foundation.md`.
- 2026-05-06: `laravel/tinker` and `nunomaduro/collision` were not used because their available releases did not resolve cleanly with Laravel 13 during foundation setup.
- 2026-05-06: Added a minimal local `php artisan test` command that delegates to PHPUnit so the required project check works without incompatible dev packages.
- 2026-05-06: Re-ran `docker compose up -d --build`, verified app/Nginx/MySQL/Redis/queue/scheduler containers, health endpoints, route list, migrate pretend, and PHPUnit suite.
- 2026-05-06: Completed feature unit `02-auth-tenant/01-login-ui.md`; implemented UI-only login screen and did not add backend auth behavior.
- 2026-05-06: Completed feature unit `02-auth-tenant/02-auth-activation.md`; implemented minimal custom auth, tenant creation, activation token flow, tenant-user relationship, activation UI, and regression tests.
- 2026-05-06: Added idempotent `SuperAdminSeeder`, documented local superadmin env values, seeded the Docker MySQL database, and audited foundation/login/auth-activation gaps.
- 2026-05-06: Fixed in-scope audit gaps for the completed foundation/login/auth-activation units without adding WA, AI, billing, analytics, staff roles, or full dashboard behavior.
