# 01 Booking Link Rules

Read `CLAUDE.md` before starting.

## Goal

Ensure booking link is only sent when all booking readiness requirements are satisfied.

## Scope

- BookingReadinessService
- BookingActionPolicy
- Decision branch for booking intent
- Booking link resolver
- Handoff when booking link missing or admin needed
- Waiting booking status update if action succeeds

## Out of Scope

- Full Google Calendar adapter
- Payment/invoice
- Follow-up automation
- Advanced lead scoring

## Technical Requirements

Booking link can only be sent if:

- Package selected
- Customer name available
- Availability checked and valid
- User shows booking intent
- Closing readiness is high enough
- Booking link tenant exists
- Agent mode is active
- Tenant status is active/trial

If any condition fails, block `send_booking_link` and use safe fallback/handoff.

## Files Allowed

- Booking module
- AgentCore booking decision branch
- Conversation state update service
- Tests for booking flow

## Do Not Modify

- Calendar provider adapter except using existing availability result interface
- Invoice module
- WhatsApp gateway internals

## Implementation Notes

- Availability claim must come from calendar result/manual admin confirmation later.
- Missing booking link should create handoff or admin notification.
- Do not send booking link twice if already sent unless explicitly allowed.

## Tests / Checks

- Missing package blocks booking link.
- Missing name blocks booking link.
- Missing availability blocks booking link.
- Low readiness blocks booking link.
- Missing booking link creates handoff.
- Valid booking sends link once.

## Check when done

- [ ] BookingReadinessService exists
- [ ] BookingActionPolicy exists
- [ ] Booking link blocked when requirements missing
- [ ] Missing link handoff/notification works
- [ ] Valid booking sends link once
- [ ] Conversation status updates to waiting_booking when valid
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
