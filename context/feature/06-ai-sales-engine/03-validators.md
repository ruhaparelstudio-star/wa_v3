# 03 Validators

Read `CLAUDE.md` before starting.

## Goal

Implement the safety validator layer that blocks unsafe AI replies/actions.

## Scope

- PolicyValidatorService
- GroundingValidatorService
- ActionPermissionValidatorService
- ModeValidatorService
- Fallback resolver
- Blocked action reason structure
- Validator result stored in decision trace if trace exists

## Out of Scope

- Response composer prompt polish
- UI trace viewer
- Action dispatcher implementation
- Full calendar adapter
- Full invoice lifecycle

## Technical Requirements

Validators must block:

- Reply/action while paused.
- Sales flow while limited.
- Booking link when requirements missing.
- Price answer when structured price missing.
- Availability claim without calendar result.
- Pricelist file when file missing or tenant mismatch.
- Invoice resend after max count.
- Dormant memory use without trigger.

## Files Allowed

- AgentCore validators
- Booking policy service only if needed for preconditions
- Pricelist policy service only if needed for preconditions
- Tests for validator matrix

## Do Not Modify

- Baileys gateway
- UI dashboard
- Unrelated migrations

## Implementation Notes

- Fail closed for sensitive actions when required data is missing.
- Return explicit block reason codes.
- Validator output must be traceable.
- Do not make LLM responsible for validator decisions.

## Tests / Checks

- Paused mode blocks all AI reply/action.
- Limited mode blocks sales flow.
- Booking link blocked when missing requirements.
- Calendar claim blocked without calendar result.
- Price answer blocked without structured price.
- Pricelist file blocked when missing.
- Invoice resend blocked after max count.

## Check when done

- [ ] PolicyValidator exists
- [ ] GroundingValidator exists
- [ ] ActionPermissionValidator exists
- [ ] ModeValidator exists
- [ ] Fail-closed behavior implemented
- [ ] Block reasons are explicit
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
