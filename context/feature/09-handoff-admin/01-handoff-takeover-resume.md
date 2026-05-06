# 01 Handoff, Takeover, and Resume

Read `CLAUDE.md` before starting.

## Goal

Implement human admin control over AI conversation: handoff creation, takeover pause, resume active, and admin notification/context basics.

## Scope

- Handoff table/model
- Notification table/model
- HandoffService
- NotificationService
- Takeover endpoint/action
- Resume endpoint/action
- Agent mode transition
- Admin context panel data structure

## Out of Scope

- Browser notification polish
- Sound alert polish
- Full frontend inbox redesign
- Advanced priority scoring

## Technical Requirements

Handoff triggers include:

- User asks admin
- Complaint
- Invoice/payment sensitive topic
- AI confidence low
- Booking needs admin
- Calendar unavailable but user asks availability
- Booking link missing
- Lead limit exhausted
- Message while paused/handoff

Takeover must set agent mode to `paused_admin`.

Resume must set agent mode back to `active` only when admin chooses it.

Paused mode blocks AI reply/action.

## Files Allowed

- Handoff module
- Notification module
- Conversation state service
- Tenant admin route/controller/UI basics
- Tests for mode transitions and handoff

## Do Not Modify

- Baileys gateway
- LLM provider
- Structured knowledge CRUD

## Implementation Notes

- Handoff data should include tenant_id, conversation_id, lead_id, reason, priority, current_stage, active_goal, summary, recommended_next_action, status.
- Admin context panel should show name, phone, package interest, event date, location, budget, objection, stage, active goal, summary, reason handoff, recommended next action.

## Tests / Checks

- Handoff created on complaint.
- User asks admin creates handoff.
- Takeover sets mode paused_admin.
- Paused mode blocks AI reply.
- Resume sets mode active.
- Message during paused creates notification.
- Context panel data returns expected summary.

## Check when done

- [ ] Handoff model/table exists
- [ ] Notification model/table exists
- [ ] Takeover action works
- [ ] Resume action works
- [ ] Paused mode blocks AI reply/action
- [ ] Notification created while paused
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
