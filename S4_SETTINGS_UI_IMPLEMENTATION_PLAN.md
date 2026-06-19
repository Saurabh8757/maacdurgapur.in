# Sprint S4 — Settings UI Implementation Plan

**Project:** MAAC Durgapur Multi-Brand CMS  
**Status:** Awaiting approval  
**Prepared:** 19 June 2026  
**Scope:** Settings administration UI and draft editing only

## 1. Objective

Build the first secure administrative interface for the Settings schema created
in Sprint S2.

S4 will allow authorized administrators to:

- Select an assigned brand
- Browse settings by group
- View effective and inherited values
- Create and update draft values
- Reset brand overrides
- Validate typed setting input
- View draft status

S4 will not make Settings authoritative on the public website.

## 2. Scope

### Included

- Trusted admin brand context
- Settings navigation
- Settings group pages
- Definition-driven form rendering
- Draft value creation and update
- Brand/global inheritance display
- Reset override action
- Form Request validation
- Controller and policy layer
- RBAC permission integration
- Activity-ready domain events
- Feature tests
- Controlled activation of approved non-media definitions

### Excluded

- Public frontend settings consumption
- Publication workflow
- Approval workflow
- Rollback UI
- Version comparison UI
- Media Picker
- Media-valued settings
- Existing `site_info` migration
- Existing upload migration
- Homepage Builder integration
- Settings services for public caching
- Changes to public Blade templates

## 3. Current Foundation

Available from completed sprints:

- `brands`
- `brand_domains`
- `brand_user`
- `setting_groups`
- `setting_definitions`
- `setting_values`
- `setting_value_versions`
- `settings_publications`
- `settings_publication_items`
- Media schema foundation

Current state:

- 13 setting groups
- 64 setting definitions
- All definitions inactive
- Zero setting values
- Zero publications
- Existing `site_info` remains authoritative

## 4. Implementation Boundaries

S4 must not:

- Read or copy values from `site_info`
- Modify public website output
- Publish draft values
- Create media records
- Upload files
- Activate media-dependent definitions
- Add arbitrary setting keys through the UI
- Use `user_type == Admin` as the final Settings authorization rule

## 5. Planned File Inventory

### Controllers

1. `app/Http/Controllers/Admin/Settings/SettingsController.php`
2. `app/Http/Controllers/Admin/Settings/SettingsDraftController.php`
3. `app/Http/Controllers/Admin/Settings/BrandContextController.php`

### Form Requests

4. `app/Http/Requests/Admin/Settings/UpdateSettingsRequest.php`
5. `app/Http/Requests/Admin/Settings/ResetSettingOverrideRequest.php`
6. `app/Http/Requests/Admin/Settings/SwitchBrandRequest.php`

### Services

7. `app/Services/Settings/SettingValueCaster.php`
8. `app/Services/Settings/SettingValidator.php`
9. `app/Services/Settings/SettingsResolver.php`
10. `app/Services/Settings/SettingsDraftService.php`

These services apply only to admin draft behavior in S4. Public resolution,
publication, cache invalidation, and rollback services remain deferred.

### Policies

11. `app/Policies/BrandPolicy.php`
12. `app/Policies/SettingDefinitionPolicy.php`
13. `app/Policies/SettingValuePolicy.php`

### Middleware

14. `app/Http/Middleware/ResolveAdminBrand.php`

### Events

15. `app/Events/Settings/SettingsDraftUpdated.php`
16. `app/Events/Settings/SettingOverrideReset.php`

### Configuration

17. `config/settings.php`

### Views

18. `resources/views/admin/pages/settings/index.blade.php`
19. `resources/views/admin/pages/settings/group.blade.php`
20. `resources/views/admin/pages/settings/partials/field.blade.php`
21. `resources/views/admin/pages/settings/partials/inheritance.blade.php`
22. `resources/views/admin/pages/settings/partials/validation_summary.blade.php`
23. `resources/views/admin/pages/settings/partials/draft_status.blade.php`
24. `resources/views/admin/pages/settings/fields/text.blade.php`
25. `resources/views/admin/pages/settings/fields/textarea.blade.php`
26. `resources/views/admin/pages/settings/fields/number.blade.php`
27. `resources/views/admin/pages/settings/fields/toggle.blade.php`
28. `resources/views/admin/pages/settings/fields/select.blade.php`
29. `resources/views/admin/pages/settings/fields/color.blade.php`
30. `resources/views/admin/pages/settings/fields/url.blade.php`
31. `resources/views/admin/pages/settings/fields/email.blade.php`

### Tests

32. `tests/Unit/Settings/SettingValueCasterTest.php`
33. `tests/Unit/Settings/SettingValidatorTest.php`
34. `tests/Unit/Settings/SettingsResolverTest.php`
35. `tests/Feature/Admin/Settings/SettingsAccessTest.php`
36. `tests/Feature/Admin/Settings/SettingsDraftTest.php`
37. `tests/Feature/Admin/Settings/SettingsBrandIsolationTest.php`
38. `tests/Feature/Admin/Settings/SettingsInheritanceTest.php`
39. `tests/Feature/Admin/Settings/SettingsValidationTest.php`

### Existing files likely modified

40. `routes/admin.php`
41. `app/Http/Kernel.php`
42. `app/Providers/AuthServiceProvider.php`
43. `resources/views/admin/layout/admin_layout.blade.php`
44. `resources/views/admin/layout/leftmenu.blade.php`
45. `database/seeders/SettingDefinitionSeeder.php`

No migration is planned for S4 unless implementation discovery identifies a
schema defect that cannot safely be handled in application logic. Any such
migration requires a separate approval gate.

## 6. Route Plan

Route prefix:

```text
/v1/cpanel/admin/settings
```

Route-name prefix:

```text
admin::settings.
```

Middleware:

```text
web
AdminMiddleware
revalidate
admin.brand
```

Planned routes:

| Method | URI | Name | Action |
|---|---|---|---|
| GET | `/settings/{brand}` | `settings.index` | Settings overview |
| GET | `/settings/{brand}/group/{group}` | `settings.group` | Group editor |
| PUT | `/settings/{brand}/draft` | `settings.draft.update` | Save draft |
| DELETE | `/settings/{brand}/draft/{definition}/override` | `settings.override.reset` | Remove brand override |
| POST | `/admin-context/brand` | `brand-context.switch` | Change selected admin brand |

Rules:

- All mutations use CSRF-protected non-GET methods.
- Route binding must verify active brand and definition/group records.
- Direct URL access must enforce the same permission as navigation.
- A group route must not expose inactive definitions.

## 7. Brand Context

`ResolveAdminBrand` will:

1. Resolve the brand route parameter or selected session brand.
2. Confirm the brand is active.
3. Confirm the user has active `brand_user` membership.
4. Share the brand with controllers and views.
5. Reject unauthorized access with HTTP 403.

It must not silently substitute MAAC when the requested AKSHA or Space-E-Fic
brand is unauthorized.

The brand switcher will display only assigned brands.

## 8. RBAC Requirements

Minimum permissions:

- `brands.view`
- `settings.brand.view`
- `settings.brand.edit`
- `settings.global.view`
- `settings.global.edit`
- `settings.sensitive.view`
- `settings.sensitive.edit`

S4 requires the minimum RBAC enforcement layer before routes are exposed.

If the full RBAC schema is not yet implemented, S4 implementation must pause at
the authorization gate rather than substitute broad legacy admin access.

Policy responsibilities:

### `BrandPolicy`

- View assigned brand
- Switch into assigned brand
- Access global scope only when explicitly permitted

### `SettingDefinitionPolicy`

- View active definition
- Edit definition values
- Enforce legal/theme/sensitive category restrictions

### `SettingValuePolicy`

- Create or update draft
- Reset brand override
- Prevent published/history mutation

## 9. Definition Activation Plan

All 64 definitions currently remain inactive.

S4 will activate only definitions supported by implemented controls and
validated business rules.

### Proposed activation set

#### General

- Site Name
- Short Name
- Tagline
- Copyright Text
- Default Locale
- Timezone

#### Contact

- Primary Email
- Primary Phone
- WhatsApp Number
- Address
- Map URL

#### Theme

- Color definitions
- Button Style
- Border Radius
- Shadow Preset

#### Typography

- Approved font selectors
- Base Font Size
- Heading Scale
- Line Height

#### Header

- Sticky Header
- CTA label and URL
- Announcement toggle and text

#### Footer

- Summary
- Newsletter toggle
- Legal text

#### Loader

- Enabled
- Type
- Timing
- Background color
- Reduced-motion behavior

#### Global Visual Settings

- Reduced-motion fallback
- Section spacing
- Image treatment
- Video autoplay policy
- Parallax intensity
- Animation toggles
- Mobile effects mode

#### Forms, Social and Legal

- Existing non-media definitions

Definitions must remain inactive when:

- Validation rules are incomplete
- Approved select options are unresolved
- The field depends on Media Manager
- The setting would affect public output without a later publication/cutover
  plan

Activation is idempotent and updates catalogue status only. It creates no
setting value.

## 10. Draft Data Model

S4 writes only rows with:

```text
status = draft
```

Scope:

```text
brand_id = selected brand
scope_key = brand:{brand_uuid}
locale = brand default locale
```

Global editing is hidden unless explicitly authorized.

On first save:

1. Create the draft `setting_values` row.
2. Store the canonical typed JSON value.
3. Create version number 1.

On later save:

1. Lock or optimistic-check the current draft.
2. Update the draft value.
3. Create the next immutable version.
4. Record the editor and change summary.

No row is promoted to `published` in S4.

## 11. Inheritance Display

The editor displays:

- Brand draft value
- Brand published value, when one exists in later sprints
- Global published value, when one exists
- Definition default
- Effective source badge

Possible badges:

- Draft override
- Brand value
- Global value
- Definition default
- Not configured

Reset Override:

- Deletes or archives only the editable brand draft.
- Does not copy global/default content into the brand.
- Does not modify historical versions.
- Requires confirmation.

## 12. Validation Rules

### Shared rules

- Only active definitions are accepted.
- Unknown setting keys are rejected.
- Definition IDs and keys must match.
- Brand must be authorized.
- Locale must match supported brand locale.
- Brand override must be permitted.
- Undeclared request fields are ignored or rejected.

### String

- Required when configured
- Maximum length from definition
- Invalid control characters removed

### Text

- Maximum length
- Normalized line endings
- Blade output remains escaped

### Integer and decimal

- Strict numeric validation
- Minimum and maximum enforcement
- Precision enforcement

### Boolean

- Canonical true/false conversion

### Color

- Six-digit hexadecimal format
- No CSS expressions or arbitrary styles

### URL

- Approved schemes only
- Reject JavaScript and data URLs

### Email and phone

- Email format and length validation
- Phone normalization without destructive assumptions

### Enum and font

- Value must exist in trusted definition options
- Fonts restricted to an approved catalogue

### Cross-field validation

- Loader minimum duration cannot exceed maximum duration.
- Loader maximum duration must stay within the approved limit.
- Announcement text is required when enabled.
- CTA label and URL must be configured consistently.

## 13. Service Responsibilities

### `SettingValueCaster`

- Convert form input to canonical types.
- Convert stored JSON to field values.
- Avoid `"false"`/`"0"` boolean ambiguity.

### `SettingValidator`

- Build trusted validation from active definitions.
- Add cross-field validation.
- Return field-specific errors.

### `SettingsResolver`

For the admin UI only:

1. Brand draft
2. Brand published value
3. Global published value
4. Definition default

It returns value and source metadata.

### `SettingsDraftService`

- Transactional draft save
- Version-number allocation
- Actor attribution
- Concurrency protection
- Override reset
- Event dispatch after commit

## 14. Admin UI Structure

```text
Settings
├── Overview
├── General
├── Theme
├── Typography
├── Header
├── Footer
├── Contact
├── Social Media
├── Forms
├── Loader
├── Global Visual Settings
└── Legal
```

### Overview

- Current brand
- Available groups
- Draft count per group
- Last editor
- Configuration completeness
- Notice that drafts are not live

### Group editor

- Group description
- Definition-driven fields
- Effective source indicator
- Draft/unsaved state
- Validation summary
- Save Draft
- Reset Override

No Publish button appears in S4.

### Brand switcher

- Persistent in admin header
- Assigned brands only
- Current brand clearly highlighted
- Switching with unsaved changes requires confirmation

## 15. Security Requirements

- Server-side policy checks on every action
- CSRF protection
- No mass assignment of definition metadata
- No arbitrary setting keys
- No raw HTML setting control in S4
- No sensitive infrastructure secrets
- No upload inputs
- No public preview URLs
- No public settings endpoint
- No query or validation error exposing hidden values
- Draft changes logged without sensitive value leakage

## 16. Database Safety

S4 should create draft rows only through tested UI actions.

Before implementation:

- Create database backup
- Record migration batch and table counts
- Record `site_info` checksum
- Confirm all `setting_values` and version tables are empty

After implementation:

- Confirm no publication rows were created
- Confirm no `site_info` changes
- Confirm no media rows
- Confirm no public output changes

## 17. Test Plan

### Unit tests

- Type casting
- Validation rule construction
- Effective-value resolution
- Cross-field validation

### Feature tests

- Authorized brand access
- Unauthorized brand denial
- Inactive definition hidden/rejected
- Draft creation
- Draft update and version increment
- Override reset
- CSRF protection
- Validation errors
- Cross-brand tampering
- Direct route authorization
- Global scope denial

### Regression checks

- Homepage HTTP 200
- Admin login HTTP 200
- Existing admin modules remain reachable
- `site_info` checksum unchanged
- Media tables remain empty
- No settings publication created

## 18. Implementation Batches

### Batch S4.1 — Authorization and brand context

- Implement minimum RBAC prerequisites
- Policies
- Brand middleware
- Brand switcher backend

Acceptance:

- Unauthorized brands return 403.
- Navigation cannot bypass policy.

### Batch S4.2 — Draft services and validation

- Caster
- Validator
- Resolver
- Draft service
- Unit tests

Acceptance:

- Draft/version transactions pass.
- No publication rows are created.

### Batch S4.3 — Controllers and routes

- Read controllers
- Draft mutation controller
- Brand-context controller
- Form Requests
- Route authorization

Acceptance:

- Feature authorization tests pass.

### Batch S4.4 — Admin UI

- Navigation
- Overview
- Group forms
- Field partials
- Inheritance display
- Draft state

Acceptance:

- Forms are keyboard operable.
- Validation is displayed clearly.
- No Publish or media upload action exists.

### Batch S4.5 — Definition activation

- Activate only approved supported definitions
- Keep unsupported/media definitions inactive
- Confirm zero automatic values

Acceptance:

- Activated count matches approved inventory.
- No public output changes.

### Batch S4.6 — Regression and handoff

- Full test suite
- Live route verification
- Database integrity checks
- Implementation report

## 19. Rollback Plan

### Application rollback

1. Disable Settings routes/navigation.
2. Revert S4 controllers, requests, policies, middleware, services, and views.
3. Restore definitions to inactive.
4. Preserve draft values and version history for investigation unless explicit
   deletion is approved.

### Data rollback

S4 should not require schema rollback.

If test draft data must be removed:

- Export affected rows.
- Delete only identified S4 draft values and their versions.
- Do not delete definitions or groups.
- Do not modify `site_info`.

### Emergency availability rollback

Because public reads remain on `site_info`, disabling the Settings admin
feature should not affect the public website.

## 20. Definition of Done

S4 is complete when:

1. Authorized users can access assigned-brand Settings pages.
2. Unauthorized brands are inaccessible.
3. Active definitions render from schema metadata.
4. Typed draft values save correctly.
5. Each draft change creates a version.
6. Brand inheritance source is visible.
7. Reset Override behaves safely.
8. No setting is published.
9. No media upload or integration exists.
10. No `site_info` row changes.
11. No frontend output changes.
12. No homepage integration changes.
13. Security and cross-brand tests pass.
14. Existing site and admin login remain HTTP 200.
15. An S4 implementation report documents all changes and rollback steps.

## 21. Approval Gate

Before implementation, approve:

1. Exact file inventory
2. Minimum RBAC dependency approach
3. Definition activation inventory
4. Draft-only workflow
5. No publication controls in S4
6. No media controls in S4
7. Batch order and rollback plan

No S4 code should be applied until this plan is approved.
