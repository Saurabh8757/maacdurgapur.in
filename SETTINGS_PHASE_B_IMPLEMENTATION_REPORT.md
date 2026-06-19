# Settings UI Phase B Implementation Report

Date: June 19, 2026

## Scope Completed

Settings Phase B - Authorization and Scope was implemented exactly within the
approved boundary.

Delivered:

- Immutable Settings scope contract
- Brand scope resolution through Admin Brand Context
- Global scope resolution with no brand identity
- Central Settings RBAC authorization service
- Sensitive-definition layered authorization
- Locale validation and default resolution
- Request-scoped service bindings
- Unit and integration tests

No migrations, seeders, routes, controllers, UI, drafts, versions,
publications, or audit events were added.

## File Inventory

### New application files

| File | Purpose |
|---|---|
| `app/Services/Settings/SettingsScope.php` | Immutable brand/global scope value object |
| `app/Services/Settings/SettingsScopeResolver.php` | Resolves scope from Admin Brand Context |
| `app/Services/Settings/SettingsAuthorizationService.php` | Maps Settings operations to existing RBAC permissions |
| `app/Services/Settings/SettingsLocaleResolver.php` | Validates supported locales and defaults |
| `app/Services/Settings/Exceptions/SettingsScopeException.php` | Missing, mismatched or unsupported scope |
| `app/Services/Settings/Exceptions/SettingsAuthorizationException.php` | Structured Settings permission denial |
| `app/Services/Settings/Exceptions/UnsupportedSettingsLocaleException.php` | Unsupported locale rejection |

### Modified files

| File | Change |
|---|---|
| `app/Providers/AppServiceProvider.php` | Registered the three Phase B services as scoped bindings |
| `config/brands.php` | Added first-release Settings locale configuration |

### Test files

| File | Coverage |
|---|---|
| `tests/Unit/Settings/SettingsScopeTest.php` | Scope identity, null global brand and immutability |
| `tests/Unit/Settings/SettingsScopeResolverTest.php` | Multi-brand Admin Context and no fallback |
| `tests/Unit/Settings/SettingsAuthorizationServiceTest.php` | Permission mapping, denial and sensitive definitions |
| `tests/Unit/Settings/SettingsLocaleResolverTest.php` | Locale validation and definition behavior |
| `tests/Unit/Settings/SettingsPhaseBContainerBindingTest.php` | Request-scoped bindings and configuration |
| `tests/Feature/Settings/SettingsPhaseBIntegrationTest.php` | Real RBAC data and no-write behavior |

No files outside the approved Phase B inventory were modified, excluding this
required implementation report.

## Settings Scope Contract

### Brand scope

```text
type = brand
scope_key = brand:{brand_uuid}
brand_id = Admin Context brand ID
brand_uuid = Admin Context brand UUID
locale = resolved supported locale
```

Brand scope:

- Requires an authenticated user.
- Requires an Admin Brand Context.
- Verifies that the context belongs to the authenticated user.
- Accepts no brand ID or UUID input.
- Has no MAAC fallback.

### Global scope

```text
type = global
scope_key = global
brand_id = null
brand_uuid = null
locale = global supported locale
```

Global scope never inherits the selected Admin Context brand.

## Admin Brand Context Integration

`SettingsScopeResolver` consumes:

```text
BrandContextManager::requireAdminContext()
```

Validated contexts:

- MAAC
- AKSHA
- Space-E-Fic

Missing context throws `SettingsScopeException`. A context bound to a different
user is also rejected.

Public Brand Context was not modified or consulted.

## RBAC Integration

All Settings authorization delegates to the existing:

```text
PermissionResolver::check()
```

The Phase B service does not query roles, grants, memberships or permissions
independently.

### Permission mapping

| Operation | Brand permission | Global permission |
|---|---|---|
| View | `settings.brand.view` | `settings.global.view` |
| Edit | `settings.brand.edit` | `settings.global.edit` |
| Submit | `settings.brand.submit` | Not available |
| Approve/reject | `settings.brand.approve` | `settings.global.publish` |
| Publish | `settings.brand.publish` | `settings.global.publish` |
| Rollback | `settings.brand.rollback` | `settings.global.publish` |
| Sensitive view | `settings.sensitive.view` | `settings.sensitive.view` |
| Sensitive edit | `settings.sensitive.edit` | `settings.sensitive.edit` |
| Read definitions | Scope view permission | Scope view permission |

`settings.definitions.manage` is not used because definition management remains
read-only.

## Definition Authorization

`authorizeDefinition()` enforces:

- Definition status must be active.
- Brand scope requires `is_brand_overridable = true`.
- Ordinary scope permission must pass.
- Sensitive view additionally requires `settings.sensitive.view`.
- Sensitive edit additionally requires `settings.sensitive.edit`.

Sensitive permission alone is insufficient.

No Setting Definition row was changed.

## Locale Configuration

Added configuration:

```text
supported_locales = [en]
default_locale = en
```

Behavior:

- Locale values are trimmed, lowercased and normalized from `_` to `-`.
- Unsupported locales throw a dedicated exception.
- Brand scope defaults to `brands.default_locale`.
- Global scope defaults to the configured Settings locale.
- Non-translatable definitions normalize to the scope default.
- Translatable definitions may use a supported requested locale.

No locale is persisted during Phase B.

## Container Lifecycle

The following services are request-scoped:

- `SettingsScopeResolver`
- `SettingsAuthorizationService`
- `SettingsLocaleResolver`

Tests confirmed:

- Repeated resolution inside one request scope returns the same instance.
- A new request scope receives a fresh instance.
- Existing Brand Context bindings remain unchanged.

## Database Impact

```text
Migrations created: 0
Migrations executed: 0
Seeders created: 0
Seeders executed: 0
Database writes: 0
Migration batch: 6
```

Pre- and post-validation table counts:

```text
setting_audit_logs = 0
setting_values = 0
setting_value_versions = 0
settings_publications = 0
settings_publication_items = 0
```

## site_info Integrity

Before Phase B:

```text
Rows: 12
SHA-256: a7dc9733d37ca04bb54dee73d5003a31072dbff508654949fe7b70255cd731a4
```

After Phase B:

```text
Rows: 12
SHA-256: a7dc9733d37ca04bb54dee73d5003a31072dbff508654949fe7b70255cd731a4
```

Result:

```text
UNCHANGED
```

## Automated Test Results

Focused Phase B tests:

```text
40 passed
```

Complete suite:

```text
103 passed
```

The integration tests confirmed:

- Bootstrap Super Admin can view Settings for MAAC.
- Bootstrap Super Admin can view Settings for AKSHA.
- Bootstrap Super Admin can view Settings for Space-E-Fic.
- Bootstrap Super Admin can view global Settings.
- Legacy Admin status without RBAC is denied.
- Phase B authorization checks create no Settings or audit records.

All changed and created PHP files passed syntax validation.
Git diff validation passed.

## Route and HTTP Validation

| Check | Result |
|---|---:|
| Route count | 70 |
| Canonical homepage | HTTP 200 |
| Admin login | HTTP 200 |
| Localhost compatibility homepage | HTTP 200 |
| Unknown host | HTTP 404 |

No routes, controllers, middleware or UI files were changed.

## Rollback Strategy

No database rollback is required.

Application rollback:

1. Remove the seven Phase B service/exception files.
2. Remove their three scoped bindings from `AppServiceProvider`.
3. Remove the `brands.settings` locale configuration.
4. Remove the six Phase B test files.
5. Clear configuration and application caches if enabled.
6. Confirm route count remains 70.
7. Confirm migration batch remains 6.
8. Confirm Settings and audit table counts remain unchanged.
9. Verify the `site_info` checksum.
10. Verify homepage and admin login responses.

Phase A audit infrastructure remains installed.

## Residual Risks

- Phase B exposes reusable authorization services but no routes consume them
  until Phase C.
- Global approval and rollback currently map to
  `settings.global.publish` because separate permissions do not exist.
- First-release supported locale remains English only.
- Definition activation remains outside the UI and Phase B does not activate
  the currently inactive seeded definitions.

## Final Status

Settings Phase B is complete and validated.

Phase C and all mutation/workflow phases were not started.
