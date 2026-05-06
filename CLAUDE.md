# CLAUDE.md — SaaS WhatsApp AI Sales Agent

You are working on a production-grade SaaS WhatsApp AI Sales Agent for wedding vendors.

This is not a normal chatbot and not a simple CRUD app.

This system must be built as a controlled workflow engine first, AI responder second.

## Required reading order

Before implementing anything or making architectural decisions, read these files in order:

1. `context/project-overview.md` — product definition, goals, features, scope, and success criteria
2. `context/architecture.md` — stack, system boundaries, storage model, service boundaries, and invariants
3. `context/ui-context.md` — visual language, admin dashboard UI direction, layout, colors, and component rules
4. `context/code-standards.md` — Laravel implementation rules, module rules, testing rules, and anti-drift rules
5. `context/ai-workflow-rules.md` — AI sales workflow, decision flow, scoping, validator rules, and delivery approach
6. `context/progress-tracker.md` — current unit, completed work, open questions, and next step

After reading the global context, only read the feature spec that the user explicitly asks you to work on.

Example:

```text
context/feature/02-auth-tenant/01-login-ui.md
```

Do not read or implement unrelated feature specs unless explicitly instructed.

## Core product principle

The AI is never the final authority.

Final authority must always come from:

1. Tenant data
2. Conversation state
3. Tenant policy
4. Global policy
5. Grounding references
6. Action permission
7. Agent mode
8. Human admin when needed

## Fixed stack

Use this stack unless the user explicitly changes it:

- Backend: Laravel latest
- Architecture: Modular Monolith
- Database: MySQL 8
- Cache/Queue: Redis
- Queue Worker: Laravel Queue Worker
- Scheduler: Laravel Scheduler
- Realtime: Laravel Reverb / WebSocket
- WhatsApp Gateway: Node.js + Baileys Gateway
- Storage: Local storage
- LLM: OpenAI GPT-5.3 through configurable provider adapter
- Web Server: Nginx
- Container: Docker Compose

## LLM configuration rule

LLM settings must never be hardcoded in services.

Use `.env` configuration:

```env
LLM_PROVIDER=openai
OPENAI_API_KEY=
OPENAI_BASE_URL=https://api.openai.com/v1
OPENAI_MODEL=gpt-5.3
OPENAI_TIMEOUT_SECONDS=10
OPENAI_TEMPERATURE=0
OPENAI_MAX_OUTPUT_TOKENS=500
```

Implementation must allow the model/provider to be changed without refactoring core business logic.

## Required inbound message flow

Every inbound WhatsApp message must follow this conceptual flow:

```text
Baileys Gateway receives WhatsApp message
↓
Send inbound payload to Laravel internal endpoint
↓
Verify internal secret / signature
↓
Store raw inbound message
↓
Deduplicate provider_message_id
↓
Push processing to queue
↓
Acquire lock per tenant + customer_phone
↓
Load WA account
↓
Load tenant from wa_account_id
↓
Check tenant status: trial / active / expired / suspended
↓
Check plan limit: WA agent, lead limit, feature gating
↓
Find or create conversation
↓
Load conversation state
↓
Load lead profile
↓
Early mode gate:
  - paused_admin → no AI reply + notify admin
  - handoff → no normal AI reply + notify admin
  - limited → only safe limited reply
  - active → continue
↓
Interpret message:
  - intent classification
  - entity extraction
  - correction detection
  - topic switch detection
↓
Match tenant-specific entities:
  - package name
  - package alias
  - service
  - price reference
↓
Carry forward previous valid entities
↓
Update lead candidate / lead profile
↓
Retrieve knowledge:
  - structured data first
  - unstructured data only if needed
  - respect tenant isolation
  - respect version/effective date
↓
Build Decision JSON
↓
Pre-validate decision:
  - global policy
  - tenant policy
  - grounding requirement
  - action permission
  - mode validator
↓
Resolve final action or fallback
↓
Compose grounded reply
↓
Final response validation:
  - no hallucinated price
  - no hallucinated availability
  - no hallucinated booking link
  - no wrong file
  - no action while paused
↓
Dispatch allowed action:
  - send text
  - send file
  - create handoff
  - trigger notification
  - send booking link
↓
Store:
  - message log
  - decision trace
  - validator result
  - action log
  - token usage
  - metrics
↓
Return processing result
```

## Non-negotiable rules

- Do not place Baileys logic inside the Laravel app.
- Do not allow LLM output to execute actions directly.
- Do not answer price, availability, booking link, file, or invoice status without grounding.
- Do not hardcode tenant package names or service names.
- Do not bypass validators.
- Do not reply while conversation is paused/admin takeover mode.
- Do not use dormant memory by default.
- Do not send booking link until all booking requirements are satisfied.
- Do not send pricelist file before tenant minimum requirements are satisfied.
- Do not send files/assets belonging to another tenant.
- Do not implement future features outside the current feature spec.
- Do not modify protected files unless the feature spec explicitly allows it.

## Working mode

For every task:

1. Read `CLAUDE.md`.
2. Read the required global context files.
3. Read only the requested feature spec.
4. Summarize the current task goal.
5. List exact files expected to be touched.
6. List database tables/migrations involved, if any.
7. Identify tests/checks first.
8. Identify boundary risks and anti-drift checks.
9. Wait for approval if the user asks for confirmation mode.
10. Implement the smallest safe change.
11. Run targeted tests/checks.
12. Update `context/progress-tracker.md`.
13. End with a concise implementation report.

## Required closeout report

End each implementation unit with:

```text
Feature Unit Report
Feature Spec:
- ...

Files Added:
- ...

Files Modified:
- ...

Tests / Checks Run:
- ...

Behavior Verified:
- ...

Progress Tracker Updated:
- Yes / No

Known Risks:
- ...

Next Suggested Unit:
- ...
```
