# 02 Pricelist Decision Flow

Read `CLAUDE.md` before starting.

## Goal

Implement safe end-to-end decision behavior for customer asking pricelist.

## Scope

- PricelistPolicyService
- Minimum requirement check
- Tenant preference: text_first or file_first
- File enabled/disabled rule
- File missing fallback
- Decision branch for `ask_pricelist`
- Blocked reason trace

## Out of Scope

- Upload UI if not already done
- WhatsApp media sending implementation if dispatcher not ready
- Full response prompt overhaul

## Technical Requirements

- If minimum requirement missing, ask for missing data first.
- If requirement is `name_only`, customer name is required.
- If requirement is `name_date`, customer name and event date are required.
- File must belong to tenant.
- Disabled file means text only.
- Missing file means text fallback or handoff according to policy.
- Never invent a file.

## Files Allowed

- AgentCore decision branch
- Pricelist policy service
- Asset resolver usage
- Response composer branch if needed
- Tests for pricelist flow

## Do Not Modify

- Calendar integration
- Booking flow except shared decision primitives
- Invoice module

## Implementation Notes

- Keep branch grounded in structured settings/assets.
- Trace blocked actions clearly.
- Do not send file action unless validator approves.

## Tests / Checks

- User asks pricelist without name → ask name.
- User asks pricelist with name → send/respond according to preference.
- `name_date` requirement asks date too.
- File missing → text fallback/handoff.
- File tenant mismatch blocked.
- Disabled file → text only.

## Check when done

- [ ] Minimum requirement check works
- [ ] text_first/file_first behavior works
- [ ] Disabled file behavior works
- [ ] Missing file fallback works
- [ ] Tenant mismatch blocked
- [ ] Blocked reason stored
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
