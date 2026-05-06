# 01 Inbound Turn Storage

Read `CLAUDE.md` before starting.

## Goal

Ensure every inbound WhatsApp message is stored safely and prepared for queued processing before any AI logic runs.

## Scope

- Raw inbound message storage
- Deduplication by provider_message_id
- Queue job dispatch for processing
- Lock key convention per tenant + customer phone
- Basic message log placeholder if conversation exists

## Out of Scope

- Full conversation state machine
- AI interpretation
- Decision JSON
- Response composer
- Outbound WhatsApp sending

## Technical Requirements

- Raw inbound must be stored before AI processing.
- Duplicate provider_message_id must be ignored safely.
- Queue processing must be idempotent.
- Lock should be per tenant + customer_phone.
- Processing order from the same phone should be preserved as much as possible.

## Files Allowed

- WhatsApp module inbound service/job
- Conversation module only if needed for basic link
- Migrations for inbound messages if not already created
- Tests for storage/dedup/queue

## Do Not Modify

- LLM provider
- Decision engine
- Validators
- UI dashboard

## Implementation Notes

- Treat inbound storage as audit-safe boundary.
- Do not parse intent here.
- Do not compose reply here.
- Queue payload should include inbound message id, not raw data only.

## Tests / Checks

- Valid inbound stored once.
- Duplicate inbound ignored.
- Queue job dispatched for new inbound.
- Missing/invalid tenant or wa_account handled safely.

## Check when done

- [ ] Raw inbound persists before processing
- [ ] Deduplication works
- [ ] Queue job dispatched once
- [ ] Lock key convention documented in code/test
- [ ] No AI logic implemented in this unit
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
