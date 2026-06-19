# Brand Context Phase B1 Implementation Report

**Project:** MAAC Durgapur  
**Completed:** 19 June 2026  
**Phase:** B1 — Resolver Foundation  
**Scope:** Request-independent brand context classes and Unit tests

## 1. Outcome

Phase B1 was implemented successfully.

Implemented:

- `BrandContext`
- `HostnameNormalizer`
- `BrandContextResolver`
- Unit tests for all three components
- Ten-minute positive cache behavior
- Sixty-second negative cache behavior
- Exact hostname lookup contract
- Explicit cache invalidation

No middleware, route, controller, provider, public-page, admin-page, or database
integration was added.

## 2. Files Added

### Application classes

- `app/Services/Brands/BrandContext.php`
- `app/Services/Brands/HostnameNormalizer.php`
- `app/Services/Brands/BrandContextResolver.php`

### Unit tests

- `tests/Unit/Brands/BrandContextTest.php`
- `tests/Unit/Brands/HostnameNormalizerTest.php`
- `tests/Unit/Brands/BrandContextResolverTest.php`

### Documentation

- `BRAND_CONTEXT_B1_IMPLEMENTATION_REPORT.md`

## 3. Explicitly Unchanged

- `app/Http/Kernel.php`
- `routes/web.php`
- `routes/admin.php`
- `app/Providers/AppServiceProvider.php`
- `app/Exceptions/Handler.php`
- `AdminMiddleware`
- Admin login behavior
- Frontend templates and assets
- Admin UI
- Database migrations and records

Git diff inspection confirmed that only the new B1 services, Unit tests, and
this report were added.

## 4. `BrandContext`

`BrandContext` is a request-context value object containing:

- Resolved `Brand`
- Resolved `BrandDomain`
- Normalized hostname
- Effective request scheme
- Resolution source

It exposes:

- `brand()`
- `domain()`
- `hostname()`
- `scheme()`
- `source()`
- `isPreview()`
- `isPrimaryDomain()`
- `shouldRedirectToPrimary()`

It performs no database queries, cache writes, authorization, or session
mutation.

## 5. `HostnameNormalizer`

Normalization behavior:

1. Trims surrounding whitespace.
2. Rejects empty hosts.
3. Rejects paths, queries, user information, control characters, and unsafe
   separators.
4. Removes a valid numeric port.
5. Removes a trailing dot.
6. Converts internationalized hostnames to ASCII when the runtime supports it.
7. Converts the hostname to lowercase.
8. Rejects IPv4/IPv6 address hosts.
9. Enforces total hostname and label lengths.
10. Enforces valid domain-label syntax.

Examples:

```text
MAACDURGAPUR.LOCAL:80 -> maacdurgapur.local
maacdurgapur.local.   -> maacdurgapur.local
```

The service does not provide a MAAC fallback.

## 6. `BrandContextResolver`

Public methods:

```text
resolve(Request): ?BrandContext
resolveHostname(string, ?string): ?BrandContext
forgetHostname(string): void
```

Resolution behavior:

1. Normalize the hostname.
2. Check request-local memory.
3. Check shared cache.
4. Perform exact active-domain lookup.
5. Require an active related brand.
6. Cache a scalar context snapshot.
7. Hydrate a request-local `BrandContext`.

Unknown or inactive hosts return:

```text
null
```

Future middleware will translate this unresolved result to the approved HTTP
404 response. B1 does not change HTTP behavior.

## 7. Database Lookup Contract

The default resolver lookup requires:

```text
brand_domains.hostname = normalized hostname
brand_domains.status = active
brands.status = active
```

The relationship is eager-loaded.

Lookup is exact. Wildcards and implicit primary-brand fallback are not
supported.

## 8. Cache Strategy

### Cache key

```text
brand-domain:v1:{normalized-hostname}
```

### Positive cache

```text
TTL: 600 seconds
```

The cached value is a scalar snapshot of approved domain and brand fields.
Eloquent models are not serialized into the shared cache.

### Negative cache

```text
TTL: 60 seconds
```

Unknown and inactive mappings use an explicit unresolved sentinel.

### Request-local cache

Each resolver instance performs at most one shared-cache/database resolution
per normalized hostname.

### Invalidation

`forgetHostname()` clears both:

- Request-local context
- Shared cache mapping

No production cache entry was created by the Unit tests because they use an
isolated in-memory cache repository.

## 9. Unit-Test Validation

Command:

```powershell
vendor\bin\phpunit --testsuite Unit
```

Result:

```text
Tests: 15
Assertions: 30
Status: Passed
```

Coverage includes:

- Case normalization
- Port removal
- Trailing-dot removal
- Invalid-host rejection
- IP-host rejection
- BrandContext accessors and flags
- Active domain/brand resolution
- Inactive domain rejection
- Inactive brand rejection
- Positive cache reuse
- Negative cache reuse across resolver instances
- Request-cache reuse
- Explicit cache invalidation

Tests use:

- In-memory cache
- Injected lookup callbacks
- In-memory model instances

They do not query or modify the project database.

## 10. Syntax and Quality Validation

All six B1 PHP files passed:

```text
php -l
```

Git whitespace validation passed.

## 11. Database Integrity

No migration or database write was performed.

Post-B1 state:

```text
Maximum migration batch: 5
brand_domains rows: 1
user_roles rows: 1
site_info SHA-256:
c47abb52d0b0d35702842ce5d64a29f45121d24bd34514e8aada8b4673ef2558
```

These values match the pre-B1 application state.

## 12. Application Availability

| Resource | Result |
|---|---|
| Homepage | HTTP 200 |
| Admin login | HTTP 200 |
| Frontend CSS | HTTP 200 |
| Frontend JavaScript | HTTP 200 |

No runtime component invokes the new resolver.

## 13. Public and Admin Behavior

### Public

- No middleware integration
- No route integration
- No 404 behavior change
- No brand-aware page rendering
- No Settings, Media, or Homepage consumption

### Admin

- No admin brand context
- No RBAC integration changes
- No navigation changes
- No `AdminMiddleware` changes
- No login changes

## 14. Security Characteristics

- Exact hostname match only
- No MAAC fallback
- Invalid and IP-based hosts rejected
- Inactive domains/brands unresolved
- Cache stores no user permissions or session data
- Lookup injection exists only as a test seam; production defaults to the
  active database mapping query
- Host-header trust remains a future middleware/TrustHosts concern

## 15. Rollback

Remove:

```text
app/Services/Brands/BrandContext.php
app/Services/Brands/HostnameNormalizer.php
app/Services/Brands/BrandContextResolver.php
tests/Unit/Brands/BrandContextTest.php
tests/Unit/Brands/HostnameNormalizerTest.php
tests/Unit/Brands/BrandContextResolverTest.php
BRAND_CONTEXT_B1_IMPLEMENTATION_REPORT.md
```

No route, middleware, provider, cache, session, or database rollback is
required.

## 16. Deferred Work

Not started:

- Phase B2 shadow resolution
- Middleware
- Container request-scope binding
- Trusted-host enforcement
- HTTP 404 integration
- Admin context selection
- RBAC route enforcement
- Settings integration
- Media integration
- Homepage Builder integration
