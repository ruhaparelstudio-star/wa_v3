# Feature Specs

This folder contains implementation prompts for Claude Code/Codex.

Each file is a small feature unit.

Rules:

1. Read `CLAUDE.md` before starting.
2. Read only the feature spec the user asks you to work on.
3. Do not implement unrelated future work.
4. Follow `Check when done` before reporting completion.
5. Update `context/progress-tracker.md` after completion.

## Suggested execution order

This is not a rigid PRD phase plan. It is a Kanban-style implementation order.

1. `01-foundation/01-project-foundation.md`
2. `02-auth-tenant/01-login-ui.md`
3. `02-auth-tenant/02-auth-activation.md`
4. `03-whatsapp-agent/01-baileys-gateway-skeleton.md`
5. `04-conversation-core/01-inbound-turn-storage.md`
6. `04-conversation-core/02-conversation-lead-core.md`
7. `05-knowledge-management/01-structured-knowledge.md`
8. `06-ai-sales-engine/01-intent-entity-layer.md`
9. `06-ai-sales-engine/02-decision-engine.md`
10. `06-ai-sales-engine/03-validators.md`
11. `06-ai-sales-engine/04-response-composer.md`
12. `07-pricelist-flow/01-pricelist-upload.md`
13. `07-pricelist-flow/02-pricelist-decision-flow.md`
14. `08-booking-flow/01-booking-link-rules.md`
15. `09-handoff-admin/01-handoff-takeover-resume.md`
16. `10-ui-dashboard/01-superadmin-dashboard.md`
17. `10-ui-dashboard/02-tenant-dashboard-inbox.md`
