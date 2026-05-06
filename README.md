# SaaS WhatsApp AI Sales Agent — AI Development Context Kit

This repository folder is a context kit for developing a SaaS WhatsApp AI Sales Agent for wedding vendors using Claude Code or Codex.

The goal of this kit is to make every AI coding session start with the same project understanding, rules, architecture, and current progress.

## How to use

1. Move `CLAUDE.md` to the root of the real project repository.
2. Keep the `context/` folder in the root of the real project repository.
3. Start every Claude Code/Codex session by telling the AI:

```text
Read CLAUDE.md first before doing anything.
```

4. When working on a specific implementation unit, point the AI to one file inside `context/feature/`.

Example:

```text
Read CLAUDE.md first.
Then work only on context/feature/02-auth-tenant/01-login-ui.md.
Do not implement anything outside that feature spec.
```

5. After each meaningful implementation change, update:

```text
context/progress-tracker.md
```

## Project principle

Build this system as a controlled workflow engine first, AI responder second.

The AI must never become the final authority. Final authority must always come from:

- Tenant data
- Conversation state
- Policy
- Grounding
- Action permission
- Agent mode
- Human admin when needed

## Folder structure

```text
SaaS WhatsApp AI Sales Agent/
├── CLAUDE.md
├── README.md
└── context/
    ├── feature/
    │   ├── 00-feature-template.md
    │   ├── 01-foundation/
    │   ├── 02-auth-tenant/
    │   ├── 03-whatsapp-agent/
    │   ├── 04-conversation-core/
    │   ├── 05-knowledge-management/
    │   ├── 06-ai-sales-engine/
    │   ├── 07-pricelist-flow/
    │   ├── 08-booking-flow/
    │   ├── 09-handoff-admin/
    │   └── 10-ui-dashboard/
    ├── project-overview.md
    ├── architecture.md
    ├── code-standards.md
    ├── ai-workflow-rules.md
    ├── ui-context.md
    └── progress-tracker.md
```

## Feature spec rule

Every file inside `context/feature/` is a small implementation prompt.

Each feature spec must include:

- Goal
- Scope
- Out of scope
- Design or technical rules
- Files allowed
- Files protected
- Implementation notes
- Tests/checks
- `Check when done`
- Progress tracker update requirement

This keeps Claude Code/Codex from drifting into unrelated future work.
# wa_v3
