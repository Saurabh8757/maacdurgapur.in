# B2.4 Admin Context Implementation Report

Date: June 19, 2026

## Executive Summary

B2.4 Admin Brand Context has been implemented for the central multi-brand
administration lifecycle.

- Active global Super Administrators can work across all active brands.
- Brand-scoped administrators receive only brands supported by both active
  RBAC assignment and brand membership.
- Legacy `user_type = Admin` status alone does not produce Admin Context.
- The selected brand is stored in a narrowly defined session namespace.
- Existing login, logout, `AdminMiddleware`, public Brand Context and business
  controllers remain unchanged.
- Existing routes remain available even when no Admin Context can be resolved.

## Implemented Files

### New application files

| File | Purpose |
|---|---|
| `app/Services/Brands/AdminBrandContext.php` | Immutable selected-admin-brand context |
| `app/Services/Brands/AdminBrandAccessResolver.php` | Resolves RBAC-authorized active brands |
| `app/Services/Brands/AdminBrandContextResolver.php` | Resolves, validates and stores admin selection |
| `app/Http/Middleware/ResolveAdminBrandContext.php` | Binds context after existing admin admission |
| `app/Http/Controllers/Admin/BrandContextController.php` | Handles authorized brand switching |
| `app/Http/Requests/Admin/SwitchBrandContextRequest.php` | Validates the submitted brand UUID |
| `resources/views/admin/components/brand-switcher.blade.php` | Minimal central-admin brand selector |

### Modified application files

| File | Change |
|---|---|
| `app/Services/Brands/BrandContextManager.php` | Added an independent request-scoped Admin Context slot |
| `app/Services/Authorization/PermissionResolver.php` | Added the approved global Super Admin membership exception |
| `app/Providers/AppServiceProvider.php` | Registered admin access/context services as scoped bindings |
| `config/brands.php` | Added the `admin_brand_context` session namespace configuration |
| `routes/admin.php` | Added middleware to the protected group and one switch route |
| `resources/views/admin/layout/admin_layout.blade.php` | Included the minimal brand selector |

### Test files

| File | Coverage |
|---|---|
| `tests/Unit/Brands/AdminBrandContextResolverTest.php` | Session, default selection, no legacy fallback and switch denial |
| `tests/Unit/Http/Middleware/ResolveAdminBrandContextTest.php` | Context binding and response pass-through |
| `tests/Feature/Admin/AdminBrandContextRouteTest.php` | Exact route inventory and middleware placement |
| `tests/Feature/Admin/AdminBrandSwitchTest.php` | Authorized switch, 403, validation and rendered selector |
| `tests/Unit/Brands/BrandContextManagerTest.php` | Public/Admin Context independence |

## Final Request Lifecycle

1. The existing global trusted-host lifecycle executes.
2. The existing `web` middleware starts the session.
3. The unchanged `AdminMiddleware` verifies legacy admin admission.
4. `ResolveAdminBrandContext` asks the admin resolver for an authorized
   context.
5. The resolver loads active RBAC assignments.
6. An active global Super Admin receives all active brands.
7. Other users receive only active brands with both membership and an active
   brand-scoped role.
8. A valid session selection is reused without rewriting the session.
9. Otherwise, the authorized default selection strategy runs.
10. A resolved context is bound to the request-scoped
    `BrandContextManager`.
11. Existing controllers and views continue normally.
12. If no authorized context exists, the request continues without Admin
    Context.

No route is restricted by Admin Context in B2.4.

## Selection Priority

Only authorized active brands participate:

1. Valid stored session selection.
2. Accessible `brand_user.is_default` brand.
3. Only accessible brand.
4. Active primary brand for global Super Admin.
5. Deterministic first accessible brand.
6. No context when no authorized brand exists.

There is no automatic MAAC compatibility context for a legacy administrator.

## Session Storage

The implementation writes one session namespace:

```text
admin_brand_context
```

It contains exactly:

```text
brand_id
brand_uuid
user_id
source
selected_at
```

Allowed source values:

```text
user_default
single_accessible
primary_brand
deterministic_fallback
explicit_switch
```

Properties:

- No Eloquent model is serialized.
- The stored user, brand ID, brand UUID, source and current access are
  revalidated.
- Valid stored context is reused without a session rewrite.
- Stale or unauthorized context is forgotten.
- Logout behavior was not modified; existing session invalidation removes the
  namespace.

## RBAC and PermissionResolver

The existing membership check remains mandatory for ordinary brand-scoped
roles.

Membership is bypassed only when all of the following are true:

- `role.code = super_admin`
- Role scope is `global`
- Role status is active
- Assignment scope is `global`
- Assignment `brand_id` is null
- Assignment status and time window are active

This exception does not bypass:

- Permission existence.
- Permission status.
- Role permission grants.
- Assignment activity dates.
- Unknown or ungranted permissions.

Actual bootstrap administrator validation:

```text
Super Admin: yes
Accessible brands: maac, aksha, space_e_fic
brands.view on MAAC: allowed
brands.view on AKSHA: allowed
brands.view on Space-E-Fic: allowed
Default Admin Context: maac
Switch to AKSHA: valid
Switch to Space-E-Fic: valid
```

## Route Inventory

Final route count:

```text
70
```

Breakdown:

- Existing routes retained: 69
- New protected switch route: 1
- Protected admin routes: 51
- Protected routes carrying Admin Context middleware: 51
- Login routes carrying Admin Context middleware: 0

New route:

```text
POST /v1/cpanel/admin/brand-context
Name: admin::brand_context.switch
```

Middleware order:

```text
web
AdminMiddleware
ResolveAdminBrandContext
revalidate
```

The switch route:

- Requires the existing authenticated Admin session.
- Validates a UUID.
- Returns HTTP 403 for an inaccessible brand.
- Redirects only to the fixed admin dashboard.
- Does not accept an arbitrary return URL.
- Adds no flash message or additional session payload.

## Admin UI

A minimal select control was added to the existing navbar.

- It displays only active brands returned by the access resolver.
- It marks the current Admin Context selection.
- It submits a CSRF-protected POST request.
- It does not redesign the existing layout.
- The authenticated dashboard rendering test confirms MAAC and AKSHA options
  render correctly for an authorized Super Admin context.

## Automated Validation

Command:

```text
php artisan test
```

Result:

```text
55 passed
```

Validated behavior includes:

- Exact five-key session payload.
- Default MAAC selection through authorized membership.
- Stored selection reuse without rewrite.
- No legacy Admin fallback.
- Unauthorized switch rejection.
- Invalid UUID validation.
- Public and Admin Context independence.
- Middleware response pass-through.
- Exactly 70 routes.
- Exactly 51 protected Admin Context routes.
- Admin login and public route exclusion.
- Authenticated selector rendering.
- Existing B2.1, B2.2 and B2.3 tests.

All changed PHP files passed syntax validation.
Blade views compiled successfully.

## Live HTTP Validation

| Request | Result |
|---|---:|
| Canonical homepage | 200 |
| Admin login | 200 |
| Guest admin dashboard | 302 to existing admin login |
| Localhost compatibility homepage | 200 |
| Loopback compatibility homepage | 200 |
| Unknown host | 404 |

The guest dashboard redirect confirms existing `AdminMiddleware` behavior is
unchanged.

## Asset Validation

The following sampled assets returned HTTP 200:

- Frontend CSS
- Frontend JavaScript
- Favicon
- Homepage background video
- Admin jQuery
- AdminLTE stylesheet

## Database Impact

- Migrations created: 0
- Migrations executed: 0
- Seeders created: 0
- Seeders executed: 0
- Schema changes: 0
- Application data writes: 0

Migration status remains unchanged through batch 5.

## Explicitly Unchanged

Diff validation confirmed no changes to:

- `app/Http/Middleware/AdminMiddleware.php`
- `app/Http/Controllers/Admin/Login/AdminLoginController.php`
- `app/Http/Controllers/Admin/Profile/ProfileController.php`
- `app/Http/Middleware/ResolvePublicBrandContext.php`
- Database migrations
- Database seeders

Login regeneration and logout invalidation behavior remain unchanged.

## Security Properties

- Legacy Admin status does not grant a brand.
- Session brand IDs cannot grant access by themselves.
- Brand UUID and user ID are verified with the selected brand.
- Inaccessible switches return HTTP 403.
- Brand-scoped roles require membership.
- Super Admin bypass is narrowly limited to the active global system role.
- Context selection does not replace permission authorization.
- Admin Context is request-scoped and separate from Public Context.

## Rollback Strategy

1. Remove `ResolveAdminBrandContext` from the protected admin group.
2. Remove the `admin::brand_context.switch` route.
3. Remove the brand switcher include from the admin layout.
4. Restore the previous `BrandContextManager`.
5. Restore the previous membership check in `PermissionResolver`.
6. Remove Admin Context services, controller, form request, component and
   tests.
7. Remove the `brands.admin_context` configuration.
8. Clear route, configuration and compiled-view caches.
9. Invalidate active sessions or forget the `admin_brand_context` namespace.
10. Confirm route count returns from 70 to 69.

No database rollback is required.

## Residual Risks

- Admin Context remains shadow-mode data; existing business controllers are
  not yet scoped by the selected brand.
- A user without valid RBAC access can still enter legacy admin routes when
  `AdminMiddleware` admits them, but receives no Admin Context.
- The selector performs an authorized-brand read during layout rendering.
- Future Settings, Media and CMS integrations must explicitly consume
  `requireAdminContext()` only after enforcement behavior is approved.

## Final Status

B2.4 is complete and validated. No subsequent Admin Context enforcement phase
has been started.
