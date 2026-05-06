# AI Workflow Rules

## Approach

Build this project incrementally using a spec-driven workflow.

Context files define what to build, how to build it, and the current state of progress. Feature files define the exact implementation unit to execute.

Always implement against these specs. Do not infer or invent behavior from scratch.

## Core System Flow

The approved core flow is:

```text
Baileys inbound
→ Laravel internal endpoint with secret/signature verification
→ raw inbound storage
→ deduplication
→ queue
→ lock per tenant/customer phone
→ load WA account, tenant, status, plan, conversation, state, lead
→ early mode gate
→ intent/entity/correction/topic switch
→ dynamic tenant entity matching
→ carry-forward valid entities
→ lead update
→ structured-first knowledge retrieval
→ Decision JSON
→ validators
→ fallback/action resolution
→ grounded reply
→ final validation
→ dispatch allowed actions
→ store logs, traces, validator results, token usage, and metrics
```

## Scoping Rules

- Work on one feature unit at a time.
- Read `CLAUDE.md` before starting.
- Read global context files before implementation.
- Read only the requested feature spec after global context.
- Prefer small, verifiable increments over broad speculative changes.
- Do not combine unrelated system boundaries in one implementation step.
- Do not implement future features unless the current feature spec explicitly requires it.

## When to Split Work

Split an implementation step if it combines:

- UI and backend business logic that can be delivered separately.
- Gateway changes and Laravel business rule changes.
- Database schema changes and AI prompt/refinement changes.
- Multiple unrelated API routes.
- Behavior not clearly defined in the context files.
- Anything that cannot be verified end to end quickly.

If a change cannot be checked with a concise checklist, the scope is too broad.

## Handling Missing Requirements

- Do not invent product behavior not defined in the context files.
- If a requirement is ambiguous, add it as an open question in `context/progress-tracker.md`.
- If the missing requirement blocks safe implementation, stop and ask the user.
- If it does not block implementation, choose the safest minimal behavior and record the assumption.

## Decision JSON Rule

The Decision JSON is a structured decision artifact. It is not automatically executable.

Minimum expected shape:

```json
{
  "intent": "ask_pricelist",
  "confidence": 0.86,
  "entities": {
    "customer_name": null,
    "event_date": null,
    "event_type": null,
    "location": null,
    "package_interest": null,
    "budget_min": null,
    "budget_max": null
  },
  "current_stage": "qualification",
  "active_goal": "collect_missing_info",
  "decision": "ask_missing_required_info",
  "desired_actions": ["send_pricelist_file"],
  "allowed_actions": ["reply_text"],
  "blocked_actions": [
    {
      "action": "send_pricelist_file",
      "reason": "minimum_requirement_not_met"
    }
  ],
  "handoff_required": false,
  "notification_required": false,
  "grounding_refs": [],
  "reply_strategy": "short_contextual_question"
}
```

## Validator Order

Use this order conceptually:

1. Decision Engine
2. Global Policy Check
3. Tenant Policy Check
4. Tenant Preference Formatting
5. Response Composer
6. Grounding Validator
7. Action Permission Validator
8. Mode Validator
9. Send / Dispatch

Implementation may split pre-validation and final response validation, but no sensitive action can bypass the validators.

## Global Forbidden Actions

The system must not:

- Hallucinate price.
- Hallucinate availability.
- Hallucinate booking link.
- Hallucinate file.
- Hallucinate invoice status.
- Reply while paused.
- Send invoice more than max.
- Use dormant memory by default.
- Use old price unless explicitly required by context.
- Execute action without permission.

## Mode Rules

- `active`: normal validated flow allowed.
- `paused_admin`: block all AI reply/action, notify admin.
- `handoff`: block normal AI reply, allow notification/admin context only.
- `limited`: allow only safe limited replies, block sales flow.

## Grounding Rules

Structured data is the source of truth.

Grounding is required for:

- Price answers
- Package answers
- Pricelist file sending
- Booking link sending
- Availability claims
- Invoice status
- Tenant policy claims

## Progress Update Rule

Update `context/progress-tracker.md` after each meaningful implementation change.

Update it with:

- Current feature unit
- Completed work
- In progress work
- Next up
- Open questions
- Architecture decisions
- Session notes

## Before Moving to the Next Unit

1. The current unit works within its defined scope.
2. No invariant defined in `context/architecture.md` was violated.
3. No validator rule was bypassed.
4. Tenant isolation is preserved.
5. Tests/checks from the feature spec are satisfied.
6. `context/progress-tracker.md` is updated.
