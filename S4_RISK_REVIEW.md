# Sprint S4 Settings UI — Risk Review

**Project:** MAAC Durgapur Multi-Brand CMS  
**Status:** Awaiting approval  
**Prepared:** 19 June 2026  
**Scope:** Pre-implementation compatibility and risk review  

## 1. Executive Summary

The current admin panel can visually host the proposed Settings UI, but S4
must not begin as a simple route-and-view addition.

The principal blocker is authorization:

- Admin login accepts only users whose `user_type` is `Admin`.
- `AdminMiddleware` permits only `user_type = Admin`.
- No implemented RBAC tables, role assignments, permission checks, or Settings
  policies currently exist.
- Brand membership exists, but membership alone does not authorize Settings
  actions.

Implementing Settings behind the existing `user_type` check would grant every
legacy administrator unrestricted access to every Settings action and would
contradict the approved RBAC architecture.

The safe approach is:

1. Implement a minimum RBAC authorization foundation.
2. Preserve `AdminMiddleware` temporarily for existing modules.
3. Add brand and permission enforcement specifically to Settings routes.
4. Introduce Settings navigation without changing public reads.
5. Keep `site_info` fully authoritative until a later approved cutover.

## 2. Risk Classification

| Severity | Meaning |
|---|---|
| Critical | Could create unauthorized access, data leakage, or admin lockout |
| High | Could break admin availability or create cross-brand data corruption |
| Medium | Could cause confusing behavior, maintainability problems, or avoidable regressions |
| Low | Cosmetic, operational, or future-hardening concern |

## 3. Existing Admin Layout Compatibility

### Current state

The shared admin layout uses:

- AdminLTE
- Bootstrap 4
- jQuery and jQuery UI
- Font Awesome
- Toastr
- SweetAlert2
- Validation Engine
- DataTables
- Summernote
- Global modal containers
- `@stack('scripts')`

The layout already supports:

- Standard content pages
- Cards, forms, toggles, selects, and modal dialogs
- Page-specific scripts
- Sidebar navigation
- Server-rendered validation and flash messages

### Compatibility assessment

The Settings UI can be implemented using the current stack without adding a
new frontend framework.

Recommended compatible controls:

- Bootstrap form controls
- Bootstrap custom switches
- Native number, email, URL, and color inputs
- AdminLTE cards and alerts
- SweetAlert2 confirmations
- Toastr or existing flash messages

### Risks

#### R1 — Heavy global asset loading

**Severity:** Medium

Every admin page loads dashboard charts, maps, DataTables, Summernote, date
pickers, demo scripts, and other plugins regardless of need.

Impact:

- Settings forms inherit unnecessary JavaScript.
- Plugin initialization may interfere with dynamic controls.
- Page load and debugging become more difficult.

Required S4 response:

- Do not refactor global asset loading during S4.
- Avoid initializing unused plugins on Settings pages.
- Use `@push('scripts')` only for narrowly scoped Settings behavior.
- Do not use Summernote because rich-text settings are not part of S4.

#### R2 — Duplicate global identifiers and modal assumptions

**Severity:** Medium

The current layout provides fixed modal IDs:

- `modal-default`
- `AjaxModel`
- `confirmDelete`

The Settings UI needs confirmations for reset override and unsaved changes.
Reusing generic modal IDs risks event collisions.

Required change:

- Use Settings-specific IDs or SweetAlert2.
- Do not reuse `confirmDelete` for reset-override behavior.

#### R3 — Hardcoded MAAC branding

**Severity:** High for multi-brand usability; Low for immediate availability

The layout hardcodes:

- Page title: `MAAC Durgapur`
- Favicon path
- Preloader image
- Footer copyright
- Sidebar logo and `MAAC` label

An AKSHA or Space-E-Fic Settings page would still appear as MAAC.

Required S4 response:

- Display the selected brand prominently in the Settings page and brand
  switcher.
- Do not replace layout branding from draft Settings.
- Keep global admin-shell branding unchanged during S4 unless separately
  approved.
- Record full admin-shell multi-brand branding as a later UI task.

#### R4 — Repeated `site_info` queries in layout

**Severity:** Medium

Both `admin_layout.blade.php` and `leftmenu.blade.php` call:

```php
\App\Helper\admin\siteInformation::siteInfo()
```

The helper executes four separate queries per call. This can produce eight
queries on every admin page, even though the returned `$info` is not used in
the inspected layout/sidebar markup.

Required S4 response:

- Do not replace these calls with draft Settings.
- Prefer removing the unused calls only as a separately reviewed, low-risk
  cleanup or leave them untouched during the initial S4 batch.
- Measure Settings page query count.

#### R5 — Mixed flash-message implementations

**Severity:** Low

The project contains both:

- `resources/views/messages.blade.php`
- `resources/views/admin/layout/message.blade.php`

Existing modules use inconsistent keys and presentation.

Required change:

- Select one existing admin-compatible partial for S4.
- Use Laravel validation errors for field failures.
- Do not introduce a third flash-message pattern.

### Layout conclusion

No layout-level blocker exists. The Settings UI should use the current
AdminLTE/Bootstrap stack and minimize shared-layout changes.

## 4. Existing `AdminMiddleware` Compatibility

### Current behavior

`AdminMiddleware`:

1. Checks whether an authenticated user exists.
2. Reads `Auth::user()->user_type`.
3. Allows only `Admin`.
4. Redirects all other users to `/admin-login`.

### Risks

#### R6 — No permission enforcement

**Severity:** Critical

Any user with `user_type = Admin` can access every route inside the protected
admin group.

Impact:

- No distinction between Super Admin, Brand Admin, Content Editor, Media
  Manager, or other approved roles.
- No distinction between view, edit, publish, or sensitive Settings access.

Required change:

- Retain `AdminMiddleware` temporarily as a broad legacy admin-shell gate.
- Add Settings-specific permission policies/middleware after it.
- Never treat passage through `AdminMiddleware` as authorization to edit
  Settings.

#### R7 — No brand enforcement

**Severity:** Critical

`AdminMiddleware` does not inspect `brand_user`.

Impact:

- A valid legacy Admin could manually request another brand's Settings route.
- Hiding that brand from a switcher would not prevent direct access.

Required change:

- Add `ResolveAdminBrand`.
- Check active brand membership for every Settings request.
- Scope all Settings queries to the resolved brand.

#### R8 — Redirect instead of proper authorization response

**Severity:** Medium

Authenticated but unauthorized users are redirected to the login page rather
than receiving HTTP 403.

Impact:

- Authorization failures look like expired authentication.
- AJAX or future API clients may receive HTML redirects.
- Security monitoring cannot clearly distinguish unauthenticated and denied
  requests.

Required S4 response:

- Settings policies return/trigger HTTP 403 for authenticated denials.
- Leave legacy-module redirect behavior unchanged during S4.

#### R9 — Duplicate web middleware

**Severity:** Low

`routes/admin.php` is loaded through `Route::middleware('web')`, while the
protected route group also explicitly includes `web`.

Laravel generally resolves this without breaking requests, but it is
unnecessary and complicates middleware reasoning.

Required S4 response:

- Do not perform a broad route refactor.
- Avoid adding another `web` declaration to nested Settings groups.

### Middleware conclusion

`AdminMiddleware` may remain as a compatibility gate, but it is insufficient
for S4 authorization.

## 5. Existing `user_type` Authorization Conflicts

### Current behavior

The user schema supports:

- `Admin`
- `Employee`
- `User`

Admin login explicitly requires:

```text
user_type = Admin
```

### Risks

#### R10 — Approved roles cannot authenticate

**Severity:** Critical

Future roles such as:

- Brand Admin
- Content Editor
- Media Manager
- Student Counsellor
- Placement Coordinator
- Marketing Manager

cannot use the admin login if represented as `Employee` or another user type.

#### R11 — Mapping roles into `user_type` is not viable

**Severity:** Critical

The existing enum cannot represent the approved role model and should not be
expanded with every new role.

Required change:

- Treat `user_type` as a temporary legacy account-category field.
- Store real authorization in RBAC assignments.
- Do not add role names to the enum for S4.

#### R12 — Broad Admin privilege remains

**Severity:** Critical

If existing Admin accounts automatically become Super Admins, a compromised or
unreviewed account gains unrestricted multi-brand Settings access.

Required change:

- Review existing Admin accounts.
- Assign explicit initial roles and brand scope.
- Grant global Settings access only to a reviewed bootstrap administrator.
- Do not infer Super Admin solely from `user_type`.

### Required minimum RBAC bridge

Before S4 Settings routes are enabled, implement:

- Permission catalogue entries required by S4
- Role records
- Role-permission assignments
- User-role assignments with brand/global scope
- Permission resolution
- Policy enforcement
- Reviewed bootstrap assignment for the existing administrator

If the project chooses a smaller temporary authorization bridge, it must still
be data-driven, brand-scoped, deny-by-default, and replaceable by the approved
RBAC system. A hardcoded email, user ID, or `Admin` bypass is prohibited.

### Authorization conclusion

This is a hard implementation blocker.

## 6. Existing Left Menu Integration Risks

### Current behavior

The sidebar:

- Is completely hardcoded.
- Shows the same menu to every Admin.
- Uses `Request::segment(4)` and `segment(5)` for active state.
- Has no permissions or brand context.
- Displays hardcoded MAAC branding.
- Reads the authenticated user directly in Blade.

### Risks

#### R13 — Visibility mistaken for authorization

**Severity:** Critical if implemented incorrectly

A permission-aware menu is useful, but hidden items do not secure routes.

Required change:

- Render Settings navigation only when the user may view Settings.
- Independently enforce controller/policy authorization.

#### R14 — Segment-based active state is fragile

**Severity:** Medium

For routes such as:

```text
/v1/cpanel/admin/settings/{brand}/group/{group}
```

the current segment checks become difficult to maintain.

Required change:

- Use route-name checks such as `request()->routeIs(...)` for the Settings
  navigation.
- Leave existing menu items unchanged during S4.

#### R15 — No nested menu precedent

**Severity:** Medium

The current sidebar is a flat list. Settings needs grouped or nested
navigation.

Required change:

- Use AdminLTE's supported treeview markup.
- Confirm collapse/expand behavior with existing `data-widget="treeview"`.
- Keep the first version compact: one Settings parent item with group links.

#### R16 — Brand switcher placement

**Severity:** High

Placing a brand switcher only inside Settings could lead to inconsistent brand
context when future modules adopt it. Placing it globally could confuse
existing non-brand-scoped modules.

Recommended S4 approach:

- Place the switcher in the admin header.
- Label it as the current Settings/CMS brand context.
- Do not apply selected brand to legacy modules in S4.
- Ensure switching brands returns to a permitted Settings page.

#### R17 — Null or missing profile image

**Severity:** Low

The sidebar builds a profile-image URL directly from
`Auth::user()['profile_picture']`. This is existing behavior and not introduced
by S4, but Settings work should not destabilize it.

Required S4 response:

- Do not refactor profile rendering as part of Settings.

### Left-menu conclusion

Integration is feasible with a single permission-aware Settings tree and
route-name-based active state.

## 7. Existing Admin Route Conflicts

### Current route shape

Protected admin prefix:

```text
/v1/cpanel/admin
```

Name prefix:

```text
admin::
```

Existing relevant routes:

- `/information`
- `/information-add`
- `/information-save`
- `/information-edit/{id}`
- `/contact`
- `/heading`

### Risks

#### R18 — Naming collision

**Severity:** Medium

Generic names such as `information` and `contact` coexist with future Settings
groups that represent similar data.

Required change:

- Use the dedicated `admin::settings.*` name namespace.
- Use `/settings/...` paths.
- Do not reuse `information`, `contact`, or CMS route names.

#### R19 — Route ordering and dynamic parameters

**Severity:** Medium

Dynamic routes such as `/settings/{brand}` can accidentally consume reserved
paths if future static routes are placed after them.

Required change:

- Register static routes, such as brand-context switching, outside or before
  dynamic Settings routes.
- Constrain brand route parameters by slug/UUID binding.
- Use scoped bindings for group and definition.

#### R20 — Legacy `site_info` writes remain available

**Severity:** High

If both legacy Site Information and new Settings drafts are editable, two
administrative sources may appear to control the same conceptual fields.

Impact:

- Administrators may edit Settings drafts and expect public changes.
- Another administrator may update `site_info`, creating divergence.

Required S4 response:

- Clearly label Settings as “Draft configuration — not live.”
- Keep the legacy Site Information route unchanged and authoritative.
- Avoid exposing duplicate fields in the main Settings navigation unless their
  draft-only behavior is explicit.
- Do not disable legacy writes until public cutover is approved.

#### R21 — Mutating GET routes elsewhere

**Severity:** Medium, not an S4 blocker

The admin group contains GET logout and deletion routes. S4 must not copy this
pattern.

Required change:

- Use PUT/DELETE/POST for Settings mutations.

### Route conclusion

No URI collision currently exists. Strict route naming and dynamic-binding
constraints are required.

## 8. Existing Session Handling Conflicts

### Current session behavior

- Default `web` session guard
- File session driver
- 120-minute lifetime
- Session encryption disabled
- HTTP-only cookie
- `SameSite=Lax`
- Session cookie path `/`
- Session domain environment-controlled
- No explicit login session regeneration
- Logout does not invalidate the session or regenerate the CSRF token
- `AuthenticateSession` middleware is disabled

### Risks

#### R22 — Login session fixation protection

**Severity:** High

`Auth::attempt()` succeeds without an explicit session regeneration in the
controller.

Required change before expanding admin privileges:

- Regenerate the session after successful authentication.

This is authentication hardening adjacent to S4 and should be completed before
broader RBAC roles can access the admin panel.

#### R23 — Incomplete logout invalidation

**Severity:** High

Logout calls only `Auth::logout()`.

Required change:

- Invalidate the session.
- Regenerate the CSRF token.
- Convert logout to a POST action in an approved security batch or S4
  prerequisite.

#### R24 — Brand context stored in session

**Severity:** High

A session key such as selected brand can become stale after:

- Brand membership revocation
- Brand suspension
- Role change
- User suspension

Required change:

- Use a namespaced key such as `admin.brand_id`.
- Revalidate membership and brand status on every Settings request.
- Clear invalid context and deny the request.
- Do not trust session presence as authorization.

#### R25 — Shared session across public and admin surfaces

**Severity:** Medium

The same `web` guard/session serves both public and admin requests.

Required S4 response:

- Store only a non-sensitive brand identifier in session.
- Avoid storing permissions or complete brand records in session.
- Permission caches, if later introduced, need explicit invalidation.

#### R26 — Local HTTP versus production secure cookie

**Severity:** Medium

Local S4 testing uses HTTP. Production must use secure cookies under HTTPS, but
the user has not authorized HTTPS work in this sprint.

Required S4 response:

- Do not change environment/session cookie settings.
- Document production secure-cookie verification as a deployment gate.

#### R27 — Unsaved-form behavior during brand switching

**Severity:** Medium

Brand switching can discard unsaved Settings input.

Required change:

- Detect dirty form state client-side.
- Require confirmation before switching.
- Server remains authoritative and does not autosave during switching.

### Session conclusion

Session-based brand context is acceptable if revalidated on every request.
Login regeneration and proper logout invalidation should precede expanded RBAC
access.

## 9. Existing `site_info` Coexistence Strategy

### Current behavior

`site_info` remains the active source for:

- Site name
- Site logo
- Favicon
- Copyright

The helper queries it directly for:

- Admin login
- Admin layout
- Admin sidebar
- Potential public consumers

The legacy Site Information controller can still create and edit rows.

### Required coexistence model

During S4:

```text
site_info
  -> remains live/authoritative

setting_values
  -> draft-only administrative data
  -> no public or shared-layout consumption
```

### Risks

#### R28 — Administrator expectation mismatch

**Severity:** High

Users may assume saving a Settings draft changes the site.

Required change:

- Prominent “Draft only — not live” banner.
- No Publish button.
- Save confirmation must say “Draft saved.”
- Overview must state that the legacy site remains unchanged.

#### R29 — Conflicting duplicate edits

**Severity:** High

The same conceptual value can differ between `site_info` and a Settings draft.

Required S4 response:

- Do not automatically synchronize either direction.
- Do not compare or import values in S4.
- Treat divergence as expected until migration/cutover.
- Display no misleading “effective public value” from Settings.

#### R30 — Layout accidentally consumes draft data

**Severity:** Critical

If the admin layout is changed to resolve new Settings, drafts could alter
branding or break the shell before publication exists.

Required change:

- Admin layout and public templates continue reading existing sources.
- Brand switcher labels come from the `brands` table, not draft Settings.
- Do not inject SettingsResolver globally in S4.

#### R31 — Legacy duplicates remain

**Severity:** Medium

`site_info.key` is not unique and the current helper uses `value()`, so
duplicate rows can produce ambiguous results.

Required S4 response:

- Do not “fix” or deduplicate `site_info` during S4.
- Capture its checksum before and after implementation.
- Handle legacy cleanup in the later migration/cutover plan.

### Coexistence conclusion

The systems can safely coexist only if S4 is visibly and technically
draft-only.

## 10. Consolidated Risk Register

| ID | Risk | Severity | S4 treatment |
|---|---|---|---|
| R1 | Heavy global admin assets | Medium | Avoid new dependencies and broad refactor |
| R2 | Modal/event collisions | Medium | Settings-specific controls |
| R3 | Hardcoded MAAC admin shell | High | Explicit current-brand indicator |
| R4 | Repeated `site_info` queries | Medium | Measure; no draft replacement |
| R5 | Mixed message patterns | Low | Reuse one existing pattern |
| R6 | No permission enforcement | Critical | Minimum RBAC prerequisite |
| R7 | No brand enforcement | Critical | Brand middleware and scoped policies |
| R8 | Redirects for denied users | Medium | Settings returns 403 |
| R9 | Duplicate web middleware | Low | Avoid further nesting |
| R10 | Non-Admin roles cannot log in | Critical | Authentication/RBAC bridge |
| R11 | `user_type` cannot represent roles | Critical | Keep as legacy category only |
| R12 | Automatic Super Admin inference | Critical | Reviewed explicit assignment |
| R13 | Menu visibility mistaken for security | Critical | Policies on every action |
| R14 | Segment-based active state | Medium | Route-name checks |
| R15 | No nested-menu precedent | Medium | AdminLTE treeview |
| R16 | Brand switcher context ambiguity | High | Settings/CMS context only |
| R17 | Profile image assumption | Low | Do not touch |
| R18 | Generic route-name collision | Medium | Dedicated Settings namespace |
| R19 | Dynamic-route capture | Medium | Route ordering and constraints |
| R20 | Two editable sources | High | Draft-only labeling |
| R21 | Mutating GET precedent | Medium | Do not copy |
| R22 | No login session regeneration | High | Fix before expanded access |
| R23 | Incomplete logout invalidation | High | Fix before expanded access |
| R24 | Stale session brand | High | Revalidate every request |
| R25 | Shared public/admin session | Medium | Store identifier only |
| R26 | Local HTTP cookie difference | Medium | Deployment gate |
| R27 | Unsaved changes on switch | Medium | Dirty-form confirmation |
| R28 | Draft/live expectation mismatch | High | Clear draft-only language |
| R29 | Divergent duplicate values | High | No synchronization in S4 |
| R30 | Draft accidentally affects layout | Critical | No global Settings injection |
| R31 | Legacy duplicate keys | Medium | Preserve and checksum |

## 11. Required Changes Before or During S4

### Mandatory prerequisites

1. Implement minimum data-driven RBAC required by Settings.
2. Create explicit initial role/permission assignments.
3. Implement brand membership enforcement.
4. Regenerate the session after successful admin login.
5. Invalidate session and regenerate CSRF token at logout.

### Mandatory S4 changes

1. Add Settings-specific policies.
2. Add `ResolveAdminBrand` middleware.
3. Add permission-aware Settings navigation.
4. Use route-name-based active navigation.
5. Add a clearly labeled brand switcher.
6. Revalidate brand access on every request.
7. Keep Settings draft-only.
8. Keep `site_info` authoritative.
9. Use non-GET mutation routes.
10. Add cross-brand and direct-route security tests.

### Deferred changes

- Full admin-shell rebranding per brand
- Global asset optimization
- `site_info` query cleanup
- Legacy route modernization outside Settings
- Public Settings resolver
- Publication and rollback UI
- Media Picker
- `site_info` migration

## 12. Blockers

### Blocker B1 — RBAC is not implemented

**Status:** Hard blocker

S4 Settings routes must not be exposed using only `AdminMiddleware`.

Resolution:

- Implement the minimum approved RBAC tables/resolver/assignments or complete
  the RBAC implementation sprint before S4.

### Blocker B2 — Login supports only legacy Admin accounts

**Status:** Hard blocker for approved non-Admin roles

Resolution:

- Decide and implement the compatibility rule by which RBAC-assigned admin
  users authenticate without expanding the `user_type` enum into role names.

### Blocker B3 — Initial privilege assignment is not reviewed

**Status:** Hard blocker

Resolution:

- Identify the bootstrap administrator.
- Assign explicit scope and Settings permissions.
- Do not infer global access from all existing Admin accounts.

### Blocker B4 — Definition activation list needs final authorization

**Status:** Implementation gate

The S4 plan proposes activating non-media definitions, but activation controls
which fields are exposed for draft editing.

Resolution:

- Approve the exact activation list.

### Non-blockers

- Existing AdminLTE layout
- Existing route prefix
- Existing file-based sessions
- Existing `site_info`, provided it remains authoritative
- Hardcoded MAAC shell branding, provided current brand is clearly displayed

## 13. Recommended Implementation Order

### Step 1 — Authentication hardening

- Session regeneration after login
- Proper logout invalidation
- Preserve existing login availability

### Step 2 — Minimum RBAC foundation

- Required roles and permissions
- User-role assignments
- Brand/global scope
- Reviewed bootstrap administrator
- Permission resolver

### Step 3 — Brand authorization context

- `ResolveAdminBrand`
- Session key
- Per-request membership validation
- Brand-switch endpoint

### Step 4 — Settings policies and route skeleton

- Policies
- Form Requests
- Route namespace
- Authorization tests
- No UI exposure until tests pass

### Step 5 — Draft services

- Typed casting
- Validation
- Draft save/versioning
- Override reset
- Concurrency handling

### Step 6 — Settings UI

- Overview
- Group forms
- Inheritance source
- Draft-only messaging
- Brand switcher

### Step 7 — Definition activation

- Activate only approved supported definitions
- Confirm zero automatic setting values
- Keep media definitions absent/inactive

### Step 8 — Regression and integrity validation

- `site_info` checksum
- Zero publications
- Zero media changes
- Homepage/admin login HTTP 200
- Existing admin module checks
- Cross-brand denial tests

## 14. Go/No-Go Recommendation

### Current recommendation

**No-Go for direct S4 UI implementation.**

Proceed first with:

1. Authentication session hardening
2. Minimum RBAC foundation
3. Reviewed bootstrap role assignment

After those gates pass, the existing admin layout, sidebar, routes, and session
framework are compatible with a draft-only Settings UI.

## 15. Approval Gate

Approval is required for:

1. Minimum RBAC prerequisite scope
2. Login compatibility approach for RBAC users
3. Bootstrap administrator assignment
4. Authentication hardening before S4
5. Exact definition activation list
6. Draft-only `site_info` coexistence strategy
7. Recommended implementation order

No S4 implementation should begin until the blockers are resolved and this
risk review is approved.
