# Brand Context B2.3 Implementation Report

Date: June 19, 2026

## Scope Completed

Public Brand Context resolution was integrated in shadow mode.

- Public routes resolve the active `BrandContext` from the request hostname.
- Resolved context is bound to the request-scoped `BrandContextManager`.
- Unresolved local operational hosts continue without a bound context.
- Responses, rendering, settings, media, homepage behavior, authorization and
  routing remain unchanged.
- No redirects or route restrictions were added.

## Modified Files

| File | Change |
|---|---|
| `app/Http/Middleware/ResolvePublicBrandContext.php` | Added read-only shadow-mode context resolution and manager binding |
| `app/Providers/RouteServiceProvider.php` | Applied the middleware only to `routes/web.php` |
| `tests/Unit/Http/Middleware/ResolvePublicBrandContextTest.php` | Added read-only and pass-through contract tests |
| `tests/Feature/Brands/PublicBrandContextShadowModeTest.php` | Added public/admin/API middleware-scope tests |
| `BRAND_CONTEXT_B23_IMPLEMENTATION_REPORT.md` | Added this implementation report |

## Request Lifecycle

1. `TrustProxies` processes the request.
2. B2.2 `TrustHosts` accepts an operational host or resolves an active brand
   domain.
3. Public routes enter `ResolvePublicBrandContext` before the existing `web`
   middleware group.
4. The existing request-scoped `BrandContextResolver` resolves the context.
5. A resolved context is passed to
   `BrandContextManager::setPublicContext()`.
6. The original request continues through the existing `web` middleware,
   controller and rendering lifecycle.
7. The downstream response is returned without modification.

Admin and API route groups do not execute this middleware.

## Read-Only Guarantees

The middleware:

- Does not write to the database.
- Does not execute model save, update, insert or delete operations.
- Does not access, start or mutate the session.
- Runs before the existing `web` middleware group.
- Does not write cookies.
- Does not mutate request attributes.
- Does not share data with views.
- Does not alter configuration.
- Does not alter settings or media behavior.
- Does not add response headers.
- Does not redirect or abort.
- Does not perform RBAC or authorization checks.

Its only application-state operation is binding a resolved context to the
request-scoped `BrandContextManager`.

The existing resolver may perform its previously approved positive or negative
cache behavior. B2.3 adds no separate cache writes.

## Host Behaviour

| Host | Result |
|---|---|
| `maacdurgapur.local` | MAAC context resolved and bound for public routes |
| Future active brand domain | Matching active brand context will be bound |
| `localhost` in local environment | Request continues without implicit MAAC context |
| `127.0.0.1` in local environment | Request continues without implicit MAAC context |
| Unknown host | B2.2 returns HTTP 404 before B2.3 |

No fallback brand is assigned.

## Route Validation

Route inventory remained unchanged:

```text
69 routes
```

Middleware placement:

| Route | Middleware result |
|---|---|
| Public homepage | `ResolvePublicBrandContext`, then `web` |
| Admin login | Existing `web` middleware only |
| Admin dashboard | Existing admin middleware only |
| API user | Existing API and Sanctum authentication middleware only |

No route file was modified.

## Automated Validation

Command:

```text
php artisan test
```

Result:

```text
40 passed
```

The B2.3 tests verify:

- Successful context binding.
- Unresolved context pass-through.
- Loopback/operational host pass-through.
- Exact downstream response object preservation.
- Response body, status and headers remain unchanged.
- Request attributes remain unchanged.
- No session is attached or accessed by the middleware.
- Public route inclusion.
- Admin and API route exclusion.
- Existing B2.1 and B2.2 behavior remains valid.

All changed PHP files passed syntax validation.

## Live HTTP Validation

| Request | Status | Redirects |
|---|---:|---:|
| `http://maacdurgapur.local/` | 200 | 0 |
| `http://maacdurgapur.local/maac` | 200 | 0 |
| `http://maacdurgapur.local/admin-login` | 200 | 0 |
| `http://localhost/maacdurgapur/` | 200 | 0 |
| `http://127.0.0.1/maacdurgapur/` | 200 | 0 |
| Request with `Host: unknown.local` | 404 | 0 |

## Asset Validation

The following sampled assets returned HTTP 200:

- Frontend stylesheet.
- Frontend JavaScript.
- Favicon image.
- Homepage background video.
- Admin jQuery asset.

No frontend or admin assets were modified.

## Database Impact

- Migrations created: 0
- Database schema changes: 0
- Database record changes: 0
- Seeders executed: 0

The middleware and route integration contain no database-write operations.

## Risks and Residual Considerations

- Public requests depend on the existing resolver and domain cache remaining
  available.
- A cache miss may perform the existing read-only `brand_domains` and `brands`
  query.
- Localhost and loopback requests intentionally have no implicit brand
  context.
- No current rendering code consumes `BrandContextManager`; this is deliberate
  shadow-mode behavior.
- Future consumers must not use `requirePublicContext()` on operational hosts
  until an explicit policy is approved.

## Rollback

1. Remove `ResolvePublicBrandContext` from the public route middleware array in
   `app/Providers/RouteServiceProvider.php`.
2. Remove `app/Http/Middleware/ResolvePublicBrandContext.php`.
3. Remove the B2.3 unit and feature tests.
4. Clear route cache if route caching is enabled.
5. Confirm the route count remains 69.
6. Re-run homepage, local compatibility, admin login and unknown-host checks.

No database rollback, cache migration or content restoration is required.

## Final Status

B2.3 is complete and operating in shadow mode. No later Brand Context phase was
started.
