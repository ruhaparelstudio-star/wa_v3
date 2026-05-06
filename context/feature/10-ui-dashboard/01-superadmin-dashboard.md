# 01 Superadmin Dashboard

Read `CLAUDE.md` before starting.

## Goal

Build the first usable superadmin dashboard for managing tenants and seeing platform health basics.

## Scope

- Superadmin dashboard route/page
- Tenant list
- Create tenant entry point
- Tenant status display
- Plan display/assignment placeholder or real assignment if plan module exists
- Activation link/resend action display if activation module exists
- Basic WA status overview placeholder if WA module exists

## Out of Scope

- Full analytics dashboard
- Token usage charts
- Full billing/payment
- Tenant admin dashboard

## Design Requirements

- Follow `context/ui-context.md`.
- Use clear table/card layout.
- Show status badges.
- Keep actions explicit and safe.
- Avoid exposing raw IDs unless useful for admin/debug.

## Files Allowed

- Superadmin routes/controllers/views
- Platform/Tenancy UI files
- Tenant list components
- Tests for access control/rendering

## Do Not Modify

- Tenant AI engine
- WhatsApp Gateway
- Conversation pipeline

## Implementation Notes

- Superadmin can see all tenants.
- Tenant admin must not access this page.
- Keep dashboard lightweight at this stage.

## Tests / Checks

- Superadmin can access dashboard.
- Tenant admin cannot access dashboard.
- Tenant list renders.
- Tenant status visible.
- Empty state renders.

## Check when done

- [ ] Superadmin dashboard route exists
- [ ] Access control enforced
- [ ] Tenant list renders
- [ ] Status badges render
- [ ] Empty state works
- [ ] UI follows context
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
