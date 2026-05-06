# 01 Structured Knowledge Management

Read `CLAUDE.md` before starting.

## Goal

Create structured tenant knowledge as the source of truth for packages, prices, discounts, FAQ, and booking link.

## Scope

- Service catalog
- Products
- Packages
- Package items
- Prices
- Discounts
- FAQ
- Booking settings
- Version/effective date fields
- Tenant isolation
- Basic admin CRUD or service-level CRUD depending current UI readiness

## Out of Scope

- Unstructured semantic retrieval
- AI prompt generation
- Pricelist file upload
- Calendar integration
- Booking readiness algorithm

## Technical Requirements

- Structured data is source of truth.
- Tenant-specific packages must not be hardcoded.
- Active version resolver must respect:
  - `version`
  - `effective_from`
  - `effective_until`
  - `is_active`
- Tenant A cannot access Tenant B knowledge.
- Expired/future price must not be used by default.

## Files Allowed

- Knowledge module
- Booking settings model/table if needed
- Related migrations/models/services/tests
- Tenant admin routes/UI only if the feature spec is expanded by user

## Do Not Modify

- AI interpretation layer
- Decision engine
- WhatsApp gateway
- Invoice module

## Implementation Notes

- Add resolver services before connecting to AI.
- Keep data model clear and normalized enough for grounding.
- Use aliases for package matching later if included.
- Do not store PDFs here; PDF metadata belongs to tenant assets/pricelist unit.

## Tests / Checks

- Active package resolved.
- Active price resolved.
- Expired price not used.
- Future price not used yet.
- Tenant isolation works.
- Missing booking link returns null.

## Check when done

- [ ] Knowledge tables/models exist
- [ ] Active version resolver works
- [ ] Price resolver respects effective date
- [ ] Tenant isolation tested
- [ ] Booking link resolver returns current tenant link only
- [ ] No AI logic implemented in this unit
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
