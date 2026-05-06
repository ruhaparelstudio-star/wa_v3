# 04 Response Composer

Read `CLAUDE.md` before starting.

## Goal

Compose natural, short, tenant-aware, grounded WhatsApp replies after decision and validators have approved the response path.

## Scope

- ResponseComposer
- PromptBuilder
- Grounded context block
- Tenant tone/preference formatter
- Reply length guard
- Max question limit rule
- Anti-hallucination final instruction
- Final response validation hook

## Out of Scope

- Intent/entity classifier
- Decision engine
- Action dispatcher
- Full admin UI

## Technical Requirements

- Composer must not invent missing data.
- Composer must use grounded context only.
- Ask maximum 1–2 questions in one reply.
- Do not repeat information the user already provided.
- Respect tenant tone/preference.
- Never mention unavailable data as if it exists.

## Files Allowed

- AgentCore composer/prompt services
- Tenant preference resolver if needed
- Tests for reply behavior

## Do Not Modify

- WhatsApp gateway
- Database schema unless required for preferences
- Validators unless adding final validation interface

## Implementation Notes

- Keep WhatsApp replies concise.
- Composer receives final validated decision/fallback, not raw unvalidated intent.
- Store final reply in decision trace when available.

## Tests / Checks

- Simple FAQ returns short answer.
- Missing info asks one clear question.
- Does not repeat provided name/date.
- Uses tenant tone.
- Does not mention unavailable price/file/calendar result.

## Check when done

- [ ] ResponseComposer exists
- [ ] PromptBuilder exists
- [ ] Grounded context block exists
- [ ] Tenant tone formatting applied
- [ ] Reply length guard exists
- [ ] Final validation hook exists
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
