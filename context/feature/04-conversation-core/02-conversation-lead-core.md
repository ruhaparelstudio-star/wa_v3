# 02 Conversation and Lead Core

Read `CLAUDE.md` before starting.

## Goal

Create/reuse conversation and lead profile for every valid inbound WhatsApp customer message.

## Scope

- Conversations table/model
- Messages table/model
- Conversation state fields or table
- Lead profile table/model
- Conversation resolver
- Message logger
- Lead profile creation/update basics
- Tenant isolation tests

## Out of Scope

- Intent classification
- Entity extraction
- Lead scoring algorithm beyond initial placeholder fields
- AI reply generation
- Handoff UI

## Technical Requirements

- New customer phone creates new active conversation.
- Existing customer phone reuses active conversation.
- Message direction is stored.
- Lead profile is created for conversation/customer.
- All records include tenant_id.
- Tenant A cannot read Tenant B conversation/lead.

## Files Allowed

- Conversation module
- Lead module
- Related migrations/models/services/tests
- Basic inbox route only if needed for verification

## Do Not Modify

- AI Sales Engine
- WhatsApp Gateway internals
- Pricelist flow
- Booking flow

## Implementation Notes

- Keep conversation resolver deterministic.
- Do not infer business intent here.
- Use current_stage/active_goal/agent_mode/memory_mode defaults.
- Good defaults:
  - current_stage: `new_lead`
  - active_goal: `initial_response`
  - agent_mode: `active`
  - memory_mode: `active`

## Tests / Checks

- New phone creates conversation.
- Existing phone reuses active conversation.
- Message stored with direction inbound.
- Lead profile created.
- Tenant isolation enforced.

## Check when done

- [ ] Conversation resolver works
- [ ] Message logger works
- [ ] Lead profile created
- [ ] State defaults exist
- [ ] Tenant isolation tested
- [ ] No AI reply generation implemented in this unit
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
