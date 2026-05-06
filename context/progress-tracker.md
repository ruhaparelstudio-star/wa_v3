# Progress Tracker

Update this file after every meaningful implementation change.

## Current Feature Unit

- `context/feature/01-foundation/01-project-foundation.md`

## Current Goal

- Project foundation complete: Laravel app skeleton can run locally with Docker Compose, Nginx, MySQL 8, Redis, queue worker, scheduler, and health endpoints.

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

## In Progress

- None.

## Next Up

- Recommended next unit: `context/feature/02-auth-tenant/01-login-ui.md`

## Open Questions

- Which auth starter will be used: Laravel Breeze, Jetstream, Fortify, or custom minimal auth?
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

## Session Notes

- Every new Claude Code/Codex session must start by reading `CLAUDE.md`.
- Feature specs are the execution prompts.
- Each feature spec must end with `Check when done`.
- After completing a feature unit, update this file.
- 2026-05-06: Completed feature unit `01-foundation/01-project-foundation.md`.
- 2026-05-06: `laravel/tinker` and `nunomaduro/collision` were not used because their available releases did not resolve cleanly with Laravel 13 during foundation setup.
- 2026-05-06: Added a minimal local `php artisan test` command that delegates to PHPUnit so the required project check works without incompatible dev packages.
