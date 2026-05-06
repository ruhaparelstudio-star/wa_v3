# 02 Tenant Dashboard Inbox

Read `CLAUDE.md` before starting.

## Goal

Build the first usable tenant conversation inbox and conversation detail view.

## Scope

- Tenant inbox route/page
- Conversation list
- Conversation detail panel
- Message timeline
- Lead context summary panel
- Agent mode badge
- Handoff status badge
- Takeover/resume buttons if services exist

## Out of Scope

- Realtime WebSocket updates
- Advanced filters/search
- Full decision trace viewer
- Manual outbound reply sending unless explicitly requested

## Design Requirements

- Use split layout on desktop.
- Stack layout on mobile.
- Show important status clearly:
  - lead temperature
  - current stage
  - active goal
  - agent mode
  - handoff status
- Keep chat timeline readable.

## Files Allowed

- Tenant dashboard routes/controllers/views
- Conversation UI components
- Tests for access and rendering

## Do Not Modify

- AI decision engine
- WhatsApp gateway
- Knowledge management

## Implementation Notes

- Tenant admin can only see own tenant conversations.
- Superadmin access depends on current access model; if unclear, add open question.
- Do not expose raw decision trace unless advanced trace page exists.

## Tests / Checks

- Tenant admin can see own conversations.
- Tenant admin cannot see other tenant conversations.
- Empty inbox state works.
- Conversation messages render in order.
- Agent mode badge visible.

## Check when done

- [ ] Inbox route/page exists
- [ ] Tenant isolation enforced
- [ ] Conversation list renders
- [ ] Conversation detail renders
- [ ] Empty state works
- [ ] UI follows context
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
