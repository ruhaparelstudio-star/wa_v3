# 02 Decision Engine

Read `CLAUDE.md` before starting.

## Goal

Implement a Decision Engine that converts interpretation, conversation state, lead profile, tenant policy, and knowledge into structured Decision JSON.

## Scope

- Decision DTO
- DecisionEngineService
- ActiveGoalResolver
- StageTransitionService basics
- Lead scoring base or placeholder service
- Decision trace stub
- No-action/no-reply result

## Out of Scope

- Validators implementation
- Response composer
- Action dispatcher
- Full lead scoring algorithm
- UI trace viewer

## Technical Requirements

- Intent result is not final authority.
- Decision must consider state, active goal, lead profile, tenant policy, available knowledge, and mode.
- Decision must include desired actions, allowed action candidates, blocked action candidates, and handoff/notification flags.
- Booking link must be candidate-blocked if readiness is incomplete.
- Pricelist file must be candidate-blocked if minimum requirements are incomplete.

## Files Allowed

- AgentCore module
- Conversation module services for state interaction
- Lead module service for basic read/update
- Tests for decision branches

## Do Not Modify

- Validator services unless creating interfaces/placeholders
- Action dispatcher
- WhatsApp gateway
- UI dashboard

## Implementation Notes

- This unit can decide but must not dispatch unsafe actions.
- Store decision trace stub if architecture is ready.
- Keep blocked reasons explicit.

## Tests / Checks

- Ask pricelist without name decides ask_missing_info.
- Ask package detail with valid package decides answer_package_detail.
- Booking intent without availability blocks booking link candidate.
- Correction updates entity without reset.
- Topic switch changes active_goal while stage is preserved.

## Check when done

- [ ] Decision DTO exists
- [ ] DecisionEngineService exists
- [ ] Active goal resolver exists
- [ ] Stage transition basics exist
- [ ] Decision trace stub exists if required
- [ ] No action dispatch occurs in this unit
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
