# 01 Intent and Entity Layer

Read `CLAUDE.md` before starting.

## Goal

Create the interpretation layer that classifies intent, extracts entities, detects corrections/topic switches, and matches tenant-specific package/service entities.

## Scope

- Intent enum
- Entity DTO
- Conversation stage enum
- Agent mode enum
- Memory mode enum
- LLM provider interface
- OpenAI adapter shell/config
- Intent classifier service
- Entity extraction service
- Deterministic date/budget parser where practical
- Tenant entity matcher
- Safe fallback for invalid LLM JSON

## Out of Scope

- Final decision engine
- Validators
- Response composer
- Action dispatcher
- WhatsApp sending

## Technical Requirements

- LLM config comes from `.env`.
- LLM output must be JSON-only and validated.
- Invalid JSON returns safe fallback.
- Classifier output is not final decision.
- Tenant package/service names must be matched from tenant data.
- Unknown packages must not be hallucinated.

## Files Allowed

- AgentCore module
- Shared LLM provider/contracts
- Enums/DTOs/services/tests
- Knowledge resolver only as dependency interface/usage

## Do Not Modify

- Action dispatcher
- Booking action policy
- Pricelist action policy
- WhatsApp gateway

## Implementation Notes

- Keep prompt templates explicit and testable.
- Return confidence and reason when possible.
- Correction must not reset conversation flow.
- Topic switch should change active_goal later, not erase state.

## Tests / Checks

- `ask_pricelist` classified correctly.
- `ask_availability` classified correctly.
- Correction detected.
- Topic switch detected.
- Package alias matched from tenant data.
- Unknown package not hallucinated.
- Invalid LLM JSON returns safe fallback.

## Check when done

- [ ] Intent enum exists
- [ ] Entity DTO exists
- [ ] LLM adapter reads `.env`
- [ ] Intent classifier returns validated result
- [ ] Entity extractor returns normalized result
- [ ] Tenant entity matcher uses tenant knowledge
- [ ] Invalid JSON fallback works
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
