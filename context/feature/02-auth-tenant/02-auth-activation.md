# 02 Auth, Tenant, and Activation

Read `CLAUDE.md` before starting.

## Goal

Implement the minimum tenant activation flow: superadmin creates tenant, activation link is generated, tenant sets password, and tenant becomes active/trial according to policy.

## Scope

- User role support for superadmin and tenant admin
- Tenants table
- Tenant-user relationship
- Activation token table
- Tenant creation by superadmin
- Activation link generation
- Activation token expiry
- Token one-time use
- Set password screen/action
- Basic tests

## Out of Scope

- Full plan billing
- WhatsApp connection
- AI workflow
- Dashboard analytics
- Staff roles
- Invoice/payment

## Technical Requirements

- Activation token must be secure/random.
- Token must expire.
- Token must only be usable once.
- Tenant admin must belong to a tenant.
- Tenant data must be isolated.
- Superadmin can create tenant.
- Tenant admin cannot create another tenant.

## Files Allowed

- Auth module
- Tenancy module
- Activation module
- Migrations for users/tenants/tenant_users/activation_tokens
- Controllers/services/actions for activation
- Routes needed for activation
- Tests for activation flow

## Do Not Modify

- WhatsApp Gateway
- AI Sales Engine
- Knowledge module
- Booking flow

## Implementation Notes

- Keep controllers thin.
- Put activation logic in `ActivationService` or action class.
- Record used_at on token after success.
- Reject expired or used token.
- Decide whether tenant status becomes `trial` or `active` based on current project rule. If unclear, record in open questions.

## Tests / Checks

- Superadmin can create tenant.
- Activation token generated.
- Activation link can set password.
- Expired token rejected.
- Used token cannot be reused.
- Tenant admin can login after activation.
- Tenant isolation basics pass.

## Check when done

- [ ] Tenant can be created by superadmin
- [ ] Activation token generated securely
- [ ] Activation token expires
- [ ] Activation token one-time use enforced
- [ ] Tenant admin can set password
- [ ] Tenant status updated correctly
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
