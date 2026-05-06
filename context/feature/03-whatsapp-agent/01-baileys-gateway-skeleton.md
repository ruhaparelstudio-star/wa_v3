# 01 Baileys Gateway Skeleton

Read `CLAUDE.md` before starting.

## Goal

Create the initial Node.js + Baileys Gateway boundary and Laravel-side WA account/inbound/outbound skeleton.

## Scope

- Node.js gateway skeleton
- Basic Express server
- Health endpoint
- WA account model/table in Laravel
- WA session/status model/table if needed
- Inbound internal endpoint in Laravel
- Outbound message record skeleton
- Internal secret/signature check
- Simulated inbound payload acceptance

## Out of Scope

- Full QR implementation if not needed for this unit
- Real WhatsApp production session handling
- AI processing
- Decision engine
- Conversation response generation

## Technical Requirements

- Baileys logic must stay in Node.js gateway.
- Laravel internal endpoint must require secret/signature.
- Raw inbound payload must be stored before processing.
- Duplicate `provider_message_id` must be ignored.
- Outbound send should be queued or represented as queued record.

## Files Allowed

- `wa-gateway/`
- Laravel WhatsApp module
- Migrations for WA accounts/sessions/inbound/outbound
- Internal routes/controllers for WA gateway boundary
- Tests for inbound endpoint and deduplication

## Do Not Modify

- AI engine
- Knowledge module
- Booking flow
- Pricelist flow

## Implementation Notes

- Gateway should not contain tenant business rules.
- Laravel should resolve tenant through `wa_account_id`.
- Store raw payload for audit/debug.
- Use Redis queue later for processing; skeleton can dispatch job if queue exists.

## Tests / Checks

- Gateway health endpoint works.
- Laravel rejects inbound without secret/signature.
- Laravel accepts valid simulated inbound.
- Duplicate provider message is not processed twice.
- WA account status can be stored/updated.

## Check when done

- [x] `wa-gateway` skeleton exists
- [x] Laravel internal inbound endpoint exists
- [x] Secret/signature required
- [x] Raw inbound stored
- [x] Duplicate message ignored
- [x] No business rules added to gateway
- [x] Tests pass
- [x] `context/progress-tracker.md` updated
