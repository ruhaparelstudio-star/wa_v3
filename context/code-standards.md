# Code Standards

## General Principles

- Keep modules small and domain-focused.
- Fix root causes, not symptoms.
- Do not mix unrelated concerns in one controller, service, or job.
- Prefer explicit DTOs/enums for workflow data.
- Do not hardcode tenant-specific data.
- Do not implement future scope while working on the current feature spec.
- Do not bypass validators to make tests pass.

## Laravel Rules

- Controllers must be thin.
- Business logic belongs in services/actions.
- Validation belongs in Form Requests or explicit input validators.
- External input must be validated at the boundary.
- Use policies/guards for access control.
- Use jobs for async work.
- Use events only when decoupling is useful.
- Avoid God services; split by responsibility.
- Do not call LLM directly from controllers.
- Do not call Baileys directly from business services without an interface/client.

## Modular Monolith Rules

- Place code inside the correct domain module.
- Shared utilities go under `Shared/` only when truly generic.
- Modules may depend on Shared and stable contracts.
- Avoid circular module dependencies.
- Do not leak tenant data across modules without explicit tenant scoping.

## Database and Model Rules

- Use UUIDs where the project convention requires them.
- Every tenant-owned table must include `tenant_id`.
- Add indexes for tenant_id, conversation_id, provider_message_id, status, and timestamps where relevant.
- Do not store large files in database columns.
- File paths must be resolved through asset metadata.
- Versioned knowledge must include effective date fields when applicable.

## AI / LLM Rules

- LLM response must be parsed and validated.
- LLM invalid JSON must produce safe fallback.
- LLM classification is not the final decision.
- LLM must not invent package, price, file, booking link, availability, or invoice status.
- LLM model/provider must be configured through `.env`.
- Keep prompts versionable and testable.
- Store token usage when available.

## Validator Rules

Every sensitive decision/action must pass:

1. PolicyValidator
2. GroundingValidator
3. ActionPermissionValidator
4. ModeValidator

Blocked action reasons must be stored in decision trace.

## WhatsApp Gateway Rules

- Node.js + Baileys Gateway owns QR, session, incoming listener, outbound sender.
- Laravel owns business rules and decisions.
- Gateway calls Laravel internal endpoints with secret/signature.
- Laravel sends outbound commands to gateway through internal API/queue.
- Gateway must not decide pricing, booking, policy, or AI response.

## Testing Rules

Add or update tests for every meaningful implementation.

Minimum test types:

- Unit tests for services/validators.
- Feature tests for routes and flows.
- Integration tests for gateway boundary where applicable.
- Tenant isolation tests for tenant-owned resources.
- Regression tests for conversation flow bugs.

Do not move to the next feature unit until the current unit has clear checks.

## UI Code Rules

- Follow `context/ui-context.md`.
- Keep UI consistent with SaaS admin dashboard style.
- Do not introduce random colors outside the design tokens.
- Keep forms clear, compact, and readable.
- Show loading, error, empty, and success states where relevant.

## Protected Areas

Do not modify unless the feature spec explicitly allows it:

- Third-party vendor code
- Generated files
- Existing migrations unrelated to the current task
- Core auth/session internals unless the current feature requires it
- Baileys gateway internals when working on Laravel-only features
- Laravel app business logic when working on gateway-only features

## Required Checks Before Closeout

Run the most relevant checks for the current feature:

```bash
php artisan test
php artisan route:list
php artisan migrate --pretend
```

If frontend assets are touched:

```bash
npm run build
```

If only a specific test suite is relevant, run targeted tests first and broader tests when shared code changed.
