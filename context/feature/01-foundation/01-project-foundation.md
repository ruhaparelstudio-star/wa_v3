# 01 Project Foundation

Read `CLAUDE.md` before starting.

## Goal

Create the initial Laravel project foundation that can run locally with Docker Compose.

## Scope

- Laravel latest application skeleton
- Docker Compose local setup
- Nginx container
- PHP app container
- MySQL 8
- Redis
- Queue worker container
- Scheduler container
- Basic health endpoints
- Base context alignment with this project

## Out of Scope

- Tenant auth
- WhatsApp Gateway implementation
- AI engine
- Dashboard UI beyond a minimal health/status page
- Production deploy pack

## Technical Requirements

- Use Docker Compose.
- Use MySQL 8.
- Use Redis for queue/cache/locks.
- Add `.env.example` values for database, Redis, queue, and LLM provider.
- LLM settings must be configurable through `.env`.
- Add basic health endpoints:
  - `/health`
  - `/health/db`
  - `/health/redis`
- Keep Laravel code modular-ready.

## Files Allowed

- `docker-compose.yml`
- `docker/`
- `.env.example`
- Laravel app bootstrap/config files
- `routes/web.php` or `routes/api.php`
- `app/Modules/` base folders
- Tests related to health checks

## Do Not Modify

- Do not add real business modules yet.
- Do not add Baileys code inside Laravel.
- Do not implement tenant auth yet.

## Implementation Notes

- Create base module folder convention under `app/Modules/`.
- Add a simple health controller/service if needed.
- Queue connection should be Redis-ready.
- Scheduler container can use a simple loop that runs `php artisan schedule:run`.

## Tests / Checks

- App boots.
- DB health endpoint returns OK.
- Redis health endpoint returns OK.
- Queue config is Redis-ready.
- Docker Compose can start the required services.

## Check when done

- [ ] `docker compose up -d --build` works
- [ ] Laravel app responds on local Nginx port
- [ ] `/health` returns OK
- [ ] `/health/db` checks MySQL connection
- [ ] `/health/redis` checks Redis connection
- [ ] `.env.example` includes LLM provider config
- [ ] No Baileys logic exists inside Laravel
- [ ] `php artisan test` passes
- [ ] `context/progress-tracker.md` updated
