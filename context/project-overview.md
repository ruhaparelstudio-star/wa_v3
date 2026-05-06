# Project Overview — SaaS WhatsApp AI Sales Agent

## Product Summary

SaaS WhatsApp AI Sales Agent is a multi-tenant SaaS platform for wedding vendors.

The product helps tenant/vendor businesses answer WhatsApp leads, qualify prospects, respond from tenant-owned knowledge, guide users toward booking, allow human admin handoff, and keep every AI decision auditable.

This is not a simple FAQ chatbot. It is a controlled AI sales workflow engine.

## Target Users

### Superadmin

Platform owner who manages tenants, plans, activation, usage, system health, and global settings.

### Tenant Admin

Wedding vendor owner or admin who connects WhatsApp, manages packages/prices/FAQ/pricelist, monitors inbox, takes over conversations, resumes AI, and manages leads.

### Customer / Lead

Prospective wedding client who chats with the tenant through WhatsApp.

## Main Goals

1. Help wedding vendors respond faster to WhatsApp leads.
2. Keep AI answers grounded in tenant data.
3. Prevent unsafe automation such as hallucinated price, availability, booking links, files, or invoices.
4. Support admin handoff when AI should not continue.
5. Track decision traces, actions, validators, and token usage for auditability.
6. Make tenant data fully dynamic from admin panel.

## Core User Flow

1. Superadmin creates a tenant.
2. Tenant activates account and logs in.
3. Tenant connects WhatsApp through QR.
4. Tenant configures packages, prices, FAQ, pricelist, booking link, policies, and preferences.
5. Customer sends WhatsApp message.
6. Baileys Gateway forwards inbound message to Laravel internal endpoint.
7. Laravel stores raw inbound, deduplicates, queues, locks, loads tenant/conversation/state/lead, and processes the turn.
8. AI interpretation produces intent/entities but does not decide final action.
9. Decision engine creates Decision JSON.
10. Validators check policy, grounding, permission, and mode.
11. Response composer creates grounded reply.
12. Allowed actions are dispatched.
13. Decision trace, action log, validator result, token usage, and metrics are stored.
14. Tenant admin can view inbox, context, trace, and take over if needed.

## Core Features

### Tenant and Activation

- Superadmin creates tenant.
- Activation token/link generated.
- Tenant sets password.
- Tenant status supports trial, active, expired, suspended.

### Plan and Feature Gating

- Starter, Growth, Pro style plans.
- WA agent limit.
- Calendar availability gating.
- Monthly unique lead limit.
- Automation behavior when lead limit is exhausted.

### WhatsApp Agent

- Connect WA account via QR.
- Store WA session/status.
- Receive inbound messages.
- Send outbound messages.
- Deduplicate provider messages.
- Queue and retry outbound.
- Node.js + Baileys Gateway boundary.

### Conversation Engine

- Conversation and message logs.
- Conversation state.
- Agent mode.
- Memory mode.
- Active goal.
- Stage tracking.
- Lead profile.

### Knowledge Management

- Service catalog.
- Products.
- Packages.
- Package items.
- Prices.
- Discounts.
- FAQ.
- Booking link.
- Pricelist assets.
- Version/effective date.

### AI Sales Engine

- Intent classification.
- Entity extraction.
- Correction detection.
- Topic switch detection.
- Tenant-specific entity matching.
- Structured-first knowledge retrieval.
- Decision JSON.
- Validator layers.
- Grounded response composer.

### Pricelist Flow

- Upload PDF.
- Text-first/file-first behavior.
- Minimum requirement: name_only or name_date.
- File ownership validation.
- Safe fallback.

### Booking Flow

- Booking readiness check.
- Booking link allowed only when requirements are satisfied.
- Handoff if booking link or availability requires admin.

### Handoff and Admin Control

- Handoff creation.
- Admin takeover.
- Resume AI.
- Admin context panel.
- Notification.

### Audit and Metrics

- Decision trace.
- Validator result.
- Action logs.
- Token usage.
- Basic metrics.

## In Scope

- Laravel modular monolith backend.
- Node.js Baileys Gateway.
- Local storage.
- Docker Compose local development.
- Configurable OpenAI provider adapter.
- Admin dashboard UI.
- Controlled WhatsApp sales workflow.
- Feature specs under `context/feature/`.

## Out of Scope For Initial Build

- Native mobile app.
- Full omnichannel support outside WhatsApp.
- Complex finance/accounting system.
- Fully autonomous payment collection.
- AI decision without validators.
- Hardcoded tenant-specific packages.

## Success Criteria

1. Tenant can activate, login, connect WhatsApp, and configure basic knowledge.
2. Customer inbound message creates conversation and lead safely.
3. AI can answer basic package/pricelist questions only from tenant data.
4. Booking link is blocked unless all requirements are met.
5. Admin can take over and pause AI.
6. Every important AI turn stores decision trace and validator result.
7. No cross-tenant data leakage is possible.
8. LLM provider/model can be changed from `.env`.
