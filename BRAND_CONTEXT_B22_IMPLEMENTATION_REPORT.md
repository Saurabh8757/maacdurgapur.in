# Brand Context B2.2 Implementation Report

Date: June 19, 2026

## Scope Completed

B2.2 trusted-host enforcement was integrated into Laravel's global request
lifecycle.

- Active domains in `brand_domains` are accepted through the existing
  `BrandContextResolver`.
- `maacdurgapur.local` remains the canonical active MAAC domain.
- `localhost` and `127.0.0.1` remain available only in the local environment.
- Unknown, inactive, invalid, and malformed hosts return HTTP 404 before route
  dispatch.
- Optional non-brand operational hosts can be configured through
  `BRAND_OPERATIONAL_HOSTS`.
- No routes, controllers, frontend files, database schema, or database records
  were changed.

## Modified Files

| File | Change |
|---|---|
| `app/Http/Kernel.php` | Enabled host validation globally after proxy handling and before request profiling |
| `app/Http/Middleware/TrustHosts.php` | Added exact operational-host and active brand-domain validation |
| `config/brands.php` | Added operational and local compatibility host configuration |
| `tests/Feature/Brands/TrustedHostRequestTest.php` | Added active brand-domain request validation |
| `tests/Feature/Brands/UnknownHostRequestTest.php` | Added unknown, suffix-bypass, invalid, malformed, and environment-isolation tests |
| `tests/Unit/Http/Middleware/TrustHostsTest.php` | Added local compatibility-host tests |
| `tests/Feature/ExampleTest.php` | Updated the scaffold test to reflect the approved unknown-host 404 policy |
| `BRAND_CONTEXT_B22_IMPLEMENTATION_REPORT.md` | Added this report |

## Request Flow

1. `TrustProxies` processes the request using the existing proxy policy.
2. `TrustHosts` reads and normalizes the request hostname.
3. Explicit operational hosts are accepted.
4. In the local environment only, `localhost` and `127.0.0.1` are accepted.
5. Other hosts are resolved through the cached `BrandContextResolver`.
6. Active brand/domain matches continue to the application.
7. Unresolved or invalid hosts terminate with HTTP 404.

The middleware does not bind `BrandContextManager`; that remains B2.3 scope.

## Database Validation

No database changes were performed.

The canonical domain prerequisite was confirmed:

| Domain | Domain status | Brand | Brand status |
|---|---|---|---|
| `maacdurgapur.local` | active | `maac` | active |

## Automated Test Results

Command:

```text
php artisan test
```

Result:

```text
34 passed
```

Coverage includes:

- Active brand-domain acceptance.
- Unknown host rejection.
- Valid-domain suffix attack rejection.
- Invalid IP-host rejection.
- Malformed hostname rejection.
- Localhost compatibility.
- Loopback IPv4 compatibility.
- Local compatibility disabled outside the local environment.
- Existing B2.1 resolver, cache, manager, and request-scope tests.

All modified PHP files passed `php -l`.

## Route Validation

`php artisan route:list --json` completed successfully.

- Registered routes before B2.2: 69
- Registered routes after B2.2: 69
- Route files modified: 0

## Live HTTP Validation

| Request | Result |
|---|---:|
| `http://maacdurgapur.local/` | 200 |
| `http://maacdurgapur.local/admin-login` | 200 |
| `http://localhost/maacdurgapur/` | 200 |
| `http://localhost/maacdurgapur/admin-login` | 200 |
| `http://127.0.0.1/maacdurgapur/` | 200 |
| `http://127.0.0.1/maacdurgapur/admin-login` | 200 |
| Laravel request with `Host: unknown.local` | 404 |
| Laravel request with `Host: invalid_host` | 404 |

No login submission, session, middleware, or authorization behavior was
changed.

## Asset Validation

Sampled local assets returned HTTP 200:

- Frontend CSS.
- Frontend images and logos.
- Uploaded service images.
- Admin Font Awesome CSS.
- Admin jQuery.
- Admin Bootstrap JavaScript.
- Admin Toastr CSS and JavaScript.
- Homepage MP4 background video.

The canonical homepage rendered with:

- 45 image elements and no completed broken images.
- 9 external/local script elements.
- 5 stylesheet elements.
- 2 video elements, both at ready state 4 without media errors.

An external cdnjs asset could not be reached from the restricted CLI
environment. This is external-network availability and is unrelated to host
validation.

## Apache Validation

No Apache configuration was changed.

Commands:

```text
httpd.exe -t
httpd.exe -S
```

Results:

- Syntax: `Syntax OK`
- Default HTTP virtual host: `localhost`
- Canonical application virtual host: `maacdurgapur.local`
- Canonical document root remains the project's `public/` directory.

## Security Impact

- Prevents arbitrary Host headers from reaching Laravel routes.
- Prevents hostname-suffix bypasses such as
  `maacdurgapur.local.attacker.test`.
- Stops malformed and unknown hosts before sessions, CSRF handling,
  controllers, or route actions.
- Uses active database domain and brand status as the source of truth.
- Preserves explicitly approved local compatibility without enabling it in
  production.

## Rollback

If B2.2 must be rolled back:

1. Disable `App\Http\Middleware\TrustHosts` in the global middleware stack.
2. Restore the previous `TrustHosts` implementation that used
   `allSubdomainsOfApplicationUrl()`.
3. Remove the `host_validation` section from `config/brands.php`.
4. Remove the B2.2 host-validation test files.
5. Restore the original scaffold expectation in
   `tests/Feature/ExampleTest.php`.
6. Clear configuration cache if production configuration was cached.
7. Re-run homepage, admin-login, route-list, and Apache syntax validation.

No database rollback is required.

## Residual Risks

- Apache can serve the XAMPP dashboard and direct static files before Laravel;
  Laravel middleware cannot validate those requests.
- Production operational/admin hosts must be explicitly supplied through
  `BRAND_OPERATIONAL_HOSTS` before use.
- Brand-host availability depends on the database or a previously populated
  domain cache.
- `APP_URL` remains `http://127.0.0.1:8000`; console-generated absolute URLs
  may therefore differ from the canonical browser domain. It was not changed
  because environment changes were outside B2.2 scope.
- Local compatibility hosts are trusted but intentionally do not receive an
  implicit MAAC Brand Context. Context binding remains B2.3 scope.

## Final Status

B2.2 is complete and validated. B2.3 has not been started.
