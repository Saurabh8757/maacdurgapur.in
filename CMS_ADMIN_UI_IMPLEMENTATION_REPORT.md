# CMS Admin UI Implementation Report

**Date:** June 20, 2026  
**Scope:** AdminLTE UI for the existing FAQ, Course, Feature, and Showcase CMS APIs

## Executive Result

The CMS Admin UI layer is implemented without changing any existing CMS API
controller, API route, database table, migration, frontend page, Settings
module, Media Manager, Blog module, or SEO module.

The implementation adds:

- A permission-aware **Content Management** sidebar group.
- Four read-only web page controllers.
- Twelve GET-only Admin UI routes.
- Index, create, and edit screens for FAQs, Courses, Features, and Showcase.
- Search, pagination, status, sort order, creation date, edit, and delete UI.
- Showcase publish UI.
- AJAX form submission through the existing CMS APIs.
- Server validation error rendering from the existing FormRequests.
- RBAC enforcement through `CmsAuthorizationService`.

The application currently cannot complete live authenticated browser validation
because the active database has no brand, brand-domain, role-permission, or
user-role rows. The approved trusted-host and RBAC controls therefore fail
closed as designed. No database records were added to bypass this condition.

## Architecture and API Preservation

The existing CMS API endpoints remain the only write path:

- `/v1/cpanel/admin/cms/faqs`
- `/v1/cpanel/admin/cms/courses`
- `/v1/cpanel/admin/cms/features`
- `/v1/cpanel/admin/cms/showcase-projects`
- `/v1/cpanel/admin/cms/showcase-projects/{id}/publish`

The new Blade screens use a separate non-conflicting prefix:

- `/v1/cpanel/admin/content-management/...`

This separation was necessary because the existing API already owns the GET
index endpoints under `/v1/cpanel/admin/cms/...`. Replacing those responses
with HTML would have changed existing API behavior.

All create, update, delete, and publish requests from the UI are sent to the
existing API endpoints. Existing write services, audit logging, brand scoping,
FormRequests, and API controllers remain authoritative.

## Sidebar Inventory

Modified:

- `resources/views/admin/layout/leftmenu.blade.php`

Added group:

- Content Management
  - FAQs
  - Courses
  - Features
  - Showcase

Visibility is calculated exclusively through `CmsAuthorizationService`:

- `cms.faqs.view`
- `cms.courses.view`
- `cms.features.view`
- `cms.showcase.view`

The parent group is hidden when the user has no module-level view permission.
Each child is independently hidden when its view permission is denied.

## Controller Inventory

Created:

- `app/Http/Controllers/Admin/Cms/Pages/CmsFaqPageController.php`
- `app/Http/Controllers/Admin/Cms/Pages/CmsCoursePageController.php`
- `app/Http/Controllers/Admin/Cms/Pages/CmsFeaturePageController.php`
- `app/Http/Controllers/Admin/Cms/Pages/CmsShowcasePageController.php`

Read-service parity added for FAQs:

- `app/Services/Cms/CmsFaqReadService.php`

Controller responsibilities are limited to:

- Authorizing page access.
- Reading active-brand records.
- Applying in-memory search and pagination.
- Rendering Blade views.

The controllers contain no create, update, delete, publish, transaction, or
direct database write operation.

## Route Inventory

Created twelve GET-only routes:

| Module | Route name | URI |
|---|---|---|
| FAQs | `admin::content.faqs.index` | `/v1/cpanel/admin/content-management/faqs` |
| FAQs | `admin::content.faqs.create` | `/v1/cpanel/admin/content-management/faqs/create` |
| FAQs | `admin::content.faqs.edit` | `/v1/cpanel/admin/content-management/faqs/{faq}/edit` |
| Courses | `admin::content.courses.index` | `/v1/cpanel/admin/content-management/courses` |
| Courses | `admin::content.courses.create` | `/v1/cpanel/admin/content-management/courses/create` |
| Courses | `admin::content.courses.edit` | `/v1/cpanel/admin/content-management/courses/{course}/edit` |
| Features | `admin::content.features.index` | `/v1/cpanel/admin/content-management/features` |
| Features | `admin::content.features.create` | `/v1/cpanel/admin/content-management/features/create` |
| Features | `admin::content.features.edit` | `/v1/cpanel/admin/content-management/features/{feature}/edit` |
| Showcase | `admin::content.showcase.index` | `/v1/cpanel/admin/content-management/showcase` |
| Showcase | `admin::content.showcase.create` | `/v1/cpanel/admin/content-management/showcase/create` |
| Showcase | `admin::content.showcase.edit` | `/v1/cpanel/admin/content-management/showcase/{showcase}/edit` |

Route validation:

- Total application routes: **115**
- New CMS Admin UI routes: **12**
- Existing CMS API routes: **25**
- All UI routes: **GET/HEAD only**
- All UI routes retain `AdminMiddleware`, `ResolveAdminBrandContext`, and the
  existing protected admin route-group middleware.

## Blade Inventory

Required pages created:

- `resources/views/admin/cms/faqs/index.blade.php`
- `resources/views/admin/cms/faqs/create.blade.php`
- `resources/views/admin/cms/faqs/edit.blade.php`
- `resources/views/admin/cms/courses/index.blade.php`
- `resources/views/admin/cms/courses/create.blade.php`
- `resources/views/admin/cms/courses/edit.blade.php`
- `resources/views/admin/cms/features/index.blade.php`
- `resources/views/admin/cms/features/create.blade.php`
- `resources/views/admin/cms/features/edit.blade.php`
- `resources/views/admin/cms/showcase/index.blade.php`
- `resources/views/admin/cms/showcase/create.blade.php`
- `resources/views/admin/cms/showcase/edit.blade.php`

Reusable partials created:

- `resources/views/admin/cms/faqs/_form.blade.php`
- `resources/views/admin/cms/courses/_form.blade.php`
- `resources/views/admin/cms/features/_form.blade.php`
- `resources/views/admin/cms/showcase/_form.blade.php`
- `resources/views/admin/cms/partials/page-assets.blade.php`
- `resources/views/admin/cms/partials/validation-errors.blade.php`

UI assets created:

- `public/admin/dist/css/cms_admin.css`
- `public/admin/dist/js/cms_admin.js`

Layout integration:

- `resources/views/admin/layout/admin_layout.blade.php`
  - Added the standard CSRF meta token used by the existing authenticated web
    session and the new API-backed forms.

## Index Page Verification

Every index screen includes:

- Search input.
- Ten-item pagination.
- Status badge.
- Sort-order column.
- Created-date column.
- Edit action, permission-gated.
- Delete action, permission-gated.

The Showcase index additionally includes:

- Publish action, gated by `cms.showcase.publish`.
- Publish action hidden for already-published projects.

## Form and Validation Verification

Create and edit pages submit to the existing API endpoints with:

- Same-origin credentials.
- CSRF token.
- `Accept: application/json`.
- Laravel method spoofing for PUT, DELETE, and PATCH operations.

No validation rules were copied into page controllers or JavaScript.

Authoritative validation remains in:

- `CmsFaqRequest`
- `CmsCourseRequest`
- `CmsFeatureRequest`
- `CmsShowcaseProjectRequest`

HTTP 422 field errors returned by these FormRequests are displayed in the form
and associated fields receive an invalid state.

## RBAC Verification

`CmsAuthorizationService` is used for:

- Sidebar visibility.
- Index page access (`view`).
- Create page access (`create`).
- Edit page access (`edit`).
- Edit action visibility (`edit`).
- Delete action visibility (`delete`).
- Showcase publish action visibility (`publish`).

The existing API services and FormRequests independently re-enforce create,
edit, delete, and publish permissions when a write request is submitted.

No `user_type` shortcut, hard-coded Super Admin bypass, custom permission
logic, automatic role assignment, or database permission grant was added.

## Validation Results

### Passed

- PHP syntax for all four page controllers.
- PHP syntax for `CmsFaqReadService`.
- PHP syntax for the CMS UI feature test.
- PHP syntax for `routes/admin.php`.
- JavaScript syntax for `cms_admin.js`.
- Blade compilation for all new templates.
- CMS UI feature tests: **3 passed**.
  - UI route inventory and API preservation.
  - Authorized rendering of all twelve screens and sidebar group.
  - Unauthorized page access returns HTTP 403.
- CSS HTTP delivery: **200**.
- JavaScript HTTP delivery: **200**.
- Existing CMS API route count preserved at **25**.
- No migration or database schema change was introduced.
- CMS content table row counts remained unchanged.

Test command:

```text
php artisan test tests\Feature\Admin\Cms\CmsAdminUiTest.php
```

Result:

```text
Tests: 3 passed
```

### Existing Suite Baseline Issues

Older Settings and Brand Context tests contain hard-coded route counts of 72
and depend on database user ID 6. Before this UI task, the current application
already exposed 103 routes and the active test database did not contain user
ID 6. Those tests therefore remain stale and were not rewritten as part of this
CMS UI scope.

## Browser and Screenshot Evidence

Browser validation attempted:

- `http://maacdurgapur.local/v1/cpanel/admin/content-management/faqs`

Observed result:

- HTTP 404 from the approved trusted-host/brand-context containment path.

Database prerequisite snapshot at validation time:

| Table | Rows |
|---|---:|
| `brands` | 0 |
| `brand_domains` | 0 |
| `role_permissions` | 0 |
| `user_roles` | 0 |
| `cms_faqs` | 0 |
| `cms_courses` | 0 |
| `cms_features` | 0 |
| `cms_showcase_projects` | 0 |

Screenshot evidence:

- `storage/app/cms-ui-evidence/cms-ui-trusted-host-blocker.png`

Because brand-domain resolution fails before authentication, live screenshots
of the authenticated CMS screens cannot be produced safely in the current
database state. The screens were instead rendered and verified through the
request-level feature test with isolated mocked authorization and read data.

## Exact Activation Prerequisites

To make the Content Management sidebar visible in the live admin panel, the
environment must already contain:

1. An active MAAC brand.
2. An active `maacdurgapur.local` row in `brand_domains`.
3. A valid admin brand membership or global Super Admin assignment.
4. Active role-permission grants for the required `cms.*` permissions.
5. An authenticated Admin user with that RBAC assignment.

These are data/bootstrap prerequisites, not UI code changes. They were not
created by this implementation.

## Rollback

Rollback the CMS Admin UI by removing:

- The four page controllers.
- `CmsFaqReadService`.
- The `content-management` route group and its four imports.
- The Content Management sidebar block and CMS permission variables.
- The CSRF meta tag added to the admin layout.
- `resources/views/admin/cms/`.
- `public/admin/dist/css/cms_admin.css`.
- `public/admin/dist/js/cms_admin.js`.
- `tests/Feature/Admin/Cms/CmsAdminUiTest.php`.
- `storage/app/cms-ui-evidence/cms-ui-trusted-host-blocker.png`.
- This report.

Then run:

```text
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

No database rollback is required because this implementation added no
migration and changed no database record.

## Final Status

**Code implementation:** Complete  
**API behavior preservation:** Verified  
**RBAC enforcement:** Verified  
**Server validation integration:** Verified  
**Authenticated live browser validation:** Blocked by missing brand-domain and
RBAC bootstrap data in the active environment

