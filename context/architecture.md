# Architecture Context

## Stack

| Layer | Technology | Role |
| --- | --- | --- |
| Backend | Laravel latest | Main application, dashboard, APIs, business rules |
| Architecture | Modular Monolith | Keep domain modules separated while deploying as one app |
| Database | MySQL 8 | Source of truth for tenants, knowledge, conversations, logs, audit |
| Cache / Queue | Redis | Queue backend, locks, cache, rate limit, idempotency |
| Queue Worker | Laravel Queue Worker | Async inbound processing and outbound dispatch |
| Scheduler | Laravel Scheduler | Scheduled jobs, follow-up, cleanup, summaries later |
| Realtime | Laravel Reverb / WebSocket | Dashboard updates, inbox notifications, WA status |
| WhatsApp Gateway | Node.js + Baileys Gateway | WA QR, session, inbound listener, outbound sender |
| Storage | Local storage | Pricelist PDFs, invoice files, uploaded assets |
| LLM | OpenAI GPT-5.3 via provider adapter | Intent/entity/reply composition, configurable through `.env` |
| Web Server | Nginx | Reverse proxy |
| Container | Docker Compose | Local and simple production deployment |

## Main System Boundaries

### Laravel App

Responsible for:

- Tenant management
- Auth and activation
- Plans and feature gates
- Admin dashboard
- Business data and knowledge management
- Conversation state
- Lead profile
- AI orchestration
- Decision engine
- Validators
- Action permission
- Handoff
- Notification
- Analytics/audit
- Internal API for WA gateway

### Node.js Baileys Gateway

Responsible for:

- WhatsApp QR generation
- WhatsApp session persistence
- Incoming message listener
- Outbound message sender
- Session status callback
- Reconnect handling

Must not contain business rules, tenant policies, pricing logic, booking logic, or AI decisions.

### Redis

Responsible for:

- Queue
- Cache
- Temporary locks
- Rate limits
- Idempotency keys

### MySQL

Responsible for:

- Tenant data
- Plan data
- Conversation source of truth
- Knowledge source of truth
- Decision traces
- Audit logs
- Action logs
- Notifications

### Local Storage

Responsible for:

- Tenant uploaded pricelist PDF
- Invoice files
- Other tenant assets

Never trust file path directly from user input. Always resolve file ownership through database metadata.

## Recommended Laravel Module Structure

```text
app/Modules/
  Platform/
  Tenancy/
  Billing/
  Plans/
  Activation/
  Auth/
  WhatsApp/
  Conversation/
  AgentCore/
  Knowledge/
  Lead/
  Booking/
  Calendar/
  Invoice/
  Handoff/
  Notification/
  Analytics/
  Audit/
  Shared/
```

Recommended folder inside each module:

```text
Actions/
DTOs/
Enums/
Events/
Jobs/
Models/
Policies/
Repositories/
Services/
Tests/
routes.php
```

## Storage Model

### Database

Store:

- Tenant metadata
- User relationships
- Plan and subscription data
- Knowledge metadata
- Package/price/FAQ data
- Conversation state
- Lead profile
- Message logs
- Action logs
- Decision traces
- Validator results
- Notifications
- Analytics snapshots

### Local Filesystem

Store:

- PDF pricelists
- Invoice files
- Uploaded tenant assets

### Redis

Store temporary operational data:

- Queue jobs
- Locks
- Cache
- Rate limit counters
- Idempotency keys

## Auth and Access Model

- Superadmin can manage all tenants and platform-level settings.
- Tenant admin can manage only their own tenant data.
- Tenant admin cannot assign their own plan.
- Tenant admin cannot access another tenant's conversation, package, asset, lead, invoice, or WA account.
- Internal WA endpoints require shared secret or request signature.

## Important Database Areas

### Tenancy

- tenants
- tenant_users
- tenant_settings
- tenant_policies
- tenant_preferences
- activation_tokens
- plans
- plan_features
- tenant_subscriptions

### WhatsApp

- wa_accounts
- wa_sessions
- wa_inbound_messages
- wa_outbound_messages
- wa_message_delivery_logs

### Conversation

- conversations
- messages
- conversation_states
- conversation_summaries
- decision_traces
- action_logs
- conversation_replay_logs

### Knowledge

- service_catalogs
- products
- packages
- package_items
- prices
- discounts
- faqs
- knowledge_documents
- tenant_assets
- knowledge_versions

### Lead

- lead_profiles
- lead_sources
- lead_scores

### Booking / Calendar / Invoice

- booking_settings
- calendar_connections
- calendar_settings
- calendar_availability_checks
- invoices
- invoice_send_logs

### Handoff / Notification

- handoffs
- notifications

## Invariants

1. Baileys business logic must never live inside Laravel.
2. LLM output must never directly execute actions.
3. Structured tenant data is the source of truth for package, price, booking link, pricelist, invoice, and availability.
4. Every tenant-owned resource must be tenant-isolated.
5. Every sensitive action must pass policy, grounding, action permission, and mode validation.
6. Agent mode `paused_admin` blocks AI replies.
7. Agent mode `handoff` blocks normal AI flow.
8. Agent mode `limited` allows only safe limited replies.
9. Booking link can only be sent after required booking readiness checks.
10. Pricelist file can only be sent after tenant requirements and file ownership checks.
11. LLM provider/model/config must be configurable from `.env`.
12. Every meaningful AI turn must store decision trace and validator result.
13. Raw inbound messages must be stored before AI processing.
14. Duplicate provider_message_id must be processed once only.
15. Processing must lock per tenant + customer phone to preserve order.
