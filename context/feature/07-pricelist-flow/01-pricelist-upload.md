# 01 Pricelist Upload

Read `CLAUDE.md` before starting.

## Goal

Allow tenant admin to upload and manage pricelist PDF assets safely.

## Scope

- Tenant assets table/model
- Pricelist settings
- Upload UI/backend
- File type validation
- File ownership validation
- Active/latest asset resolver
- Local storage path handling

## Out of Scope

- AI pricelist decision flow
- Sending file to WhatsApp
- Text-first/file-first response logic
- Invoice upload

## Technical Requirements

- Only tenant owner/admin can upload for their tenant.
- File must be validated as PDF if configured for PDF-only.
- Asset must include tenant_id.
- Inactive asset must not be selected.
- Latest active asset selected by resolver.
- Never send or expose another tenant's file.

## Files Allowed

- Knowledge/Asset module
- Tenant settings/preferences module if needed
- Upload route/controller/service
- Storage config if needed
- Tests for upload and ownership

## Do Not Modify

- AI engine
- WhatsApp gateway
- Booking flow

## Implementation Notes

- Store metadata in database.
- Store file in local storage.
- Use secure file serving route if files are not public.
- Do not trust user-provided path.

## Tests / Checks

- Upload PDF works.
- Non-PDF rejected if PDF-only.
- Asset belongs to tenant.
- Tenant A cannot access Tenant B asset.
- Inactive asset not selected.
- Latest active asset selected.

## Check when done

- [ ] Tenant asset model/table exists
- [ ] Upload works
- [ ] File validation works
- [ ] Ownership enforced
- [ ] Resolver returns latest active tenant asset
- [ ] Tests pass
- [ ] `context/progress-tracker.md` updated
