# 01 Login UI

Read `CLAUDE.md` before starting.

## Goal

Build the login UI for the SaaS WhatsApp AI Sales Agent dashboard.

## Scope

- Login page layout
- Email field
- Password field
- Submit button
- Error message area
- Loading/disabled state if framework supports it
- Responsive layout
- Visual style aligned with `context/ui-context.md`

## Out of Scope

- Backend auth logic
- Password reset
- Tenant activation
- Role-based redirect logic
- Superadmin dashboard
- Tenant dashboard

## Design Requirements

- Clean SaaS dashboard style.
- Clear product identity: WhatsApp AI Sales Agent.
- Form should be centered or placed in a professional split layout.
- Use consistent typography and spacing.
- Show validation errors clearly.
- Avoid hardcoded random colors if design tokens already exist.
- Must not look like unstyled Laravel default UI unless intentionally accepted.

## Files Allowed

- Login Blade/Inertia/Livewire page depending on the selected stack
- Auth view route if needed
- CSS/assets related to login page
- Shared layout only if necessary

## Do Not Modify

- Backend auth service
- Tenant activation flow
- Database migrations
- WhatsApp module
- AI engine module

## Implementation Notes

- Keep business logic out of the view.
- Keep controller thin.
- Reuse existing layout/components if available.
- If auth scaffolding is not selected yet, add an open question in `progress-tracker.md` before implementing backend behavior.

## Tests / Checks

- Login page renders.
- Email/password fields exist.
- Submit button exists.
- Error state can be displayed.
- Layout is responsive.

## Check when done

- [ ] Login page renders without error
- [ ] UI follows `context/ui-context.md`
- [ ] No backend auth behavior implemented in this unit
- [ ] No unrelated dashboard pages created
- [ ] No random hardcoded visual style introduced
- [ ] Relevant route/view check passes
- [ ] `context/progress-tracker.md` updated
