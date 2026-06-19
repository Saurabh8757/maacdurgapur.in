# Brand Context Phase B2.1 Implementation Report

**Project:** MAAC Durgapur  
**Completed:** 19 June 2026  
**Phase:** B2.1 — Context Manager, Cache Service, Configuration, and Scoped Binding

## 1. Outcome

Phase B2.1 was implemented successfully.

Implemented:

- `BrandContextManager`
- `BrandDomainCache`
- Configuration-based cache TTLs
- Request-scoped container binding for `BrandContextManager`
- Request-scoped container binding for `BrandContextResolver`
- Shared stateless binding for `BrandDomainCache`
- Resolver delegation to the cache abstraction
- Unit, cache, and scope-lifecycle tests

No middleware, routes, exceptions, admin integration, public integration, or
database state changed.

## 2. Files Added

### Configuration

- `config/brands.php`

### Services

- `app/Services/Brands/BrandContextManager.php`
- `app/Services/Brands/BrandDomainCache.php`

### Unit tests

- `tests/Unit/Brands/BrandContextManagerTest.php`
- `tests/Unit/Brands/BrandDomainCacheTest.php`
- `tests/Unit/Brands/BrandContextScopeBindingTest.php`

### Documentation

- `BRAND_CONTEXT_B21_IMPLEMENTATION_REPORT.md`

## 3. Files Modified

- `app/Services/Brands/BrandContextResolver.php`
  - Delegates positive, negative, and invalidation cache operations to
    `BrandDomainCache`.
  - Retains exact hostname lookup and request-local resolution behavior.

- `app/Providers/AppServiceProvider.php`
  - Registers the cache abstraction.
  - Registers the context manager and resolver as scoped services.

- `tests/Unit/Brands/BrandContextResolverTest.php`
  - Uses the new cache abstraction.

## 4. Explicitly Unchanged

- `app/Http/Kernel.php`
- `routes/web.php`
- `routes/admin.php`
- `app/Exceptions/Handler.php`
- `app/Http/Middleware/AdminMiddleware.php`
- Admin login flow
- Public controllers and views
- Admin UI
- Database schema and records

Git diff inspection confirmed no change to these runtime integration points.

## 5. `BrandContextManager`

The manager stores public Brand Context for one lifecycle scope.

Methods:

```text
setPublicContext(BrandContext)
publicContext(): ?BrandContext
requirePublicContext(): BrandContext
```

Safety behavior:

- No context is present by default.
- Rebinding the same brand is idempotent.
- Binding a different brand within the same request scope throws.
- Requiring an absent context throws.
- No static or global mutable state is used.

No middleware currently calls the manager.

## 6. `BrandDomainCache`

Responsibilities:

- Generate namespaced hostname keys
- Read scalar cache payloads
- Cache resolved brand/domain snapshots
- Cache unresolved hostname sentinels
- Invalidate one hostname
- Expose configured TTLs for diagnostics/testing

Cache key:

```text
brand-domain:v1:{normalized-hostname}
```

The cache service does not normalize hosts itself. It accepts only hostnames
already processed by `HostnameNormalizer`.

## 7. Configuration-Based TTLs

Configuration:

```text
config/brands.php
```

Values:

```text
Positive cache TTL: 600 seconds
Negative cache TTL: 60 seconds
Prefix: brand-domain:v1:
```

The service provider reads these values when constructing
`BrandDomainCache`.

The resolver no longer embeds TTL constants.

## 8. Container Binding

### Shared cache abstraction

```text
BrandDomainCache -> singleton
```

The service is stateless apart from its configured cache repository and TTL
values.

### Request-scoped services

```text
BrandContextManager  -> scoped
BrandContextResolver -> scoped
```

Within one lifecycle:

- Repeated container resolution returns the same manager.
- Repeated container resolution returns the same resolver.

After scoped instances are forgotten:

- A new manager is created with no context.
- A new resolver is created with an empty request-local cache.
- The shared `BrandDomainCache` instance remains the same.

This design is compatible with traditional PHP requests and protects future
long-running worker/Octane-style lifecycles from context leakage when Laravel
terminates scope correctly.

## 9. Resolver Compatibility

The resolver retains:

- Request hostname normalization
- Request-local memoization
- Exact active-domain lookup
- Active-brand validation
- Positive mapping cache
- Negative mapping cache
- Explicit hostname invalidation
- No MAAC fallback

The only structural change is that cache mechanics are delegated to
`BrandDomainCache`.

## 10. Unit-Test Validation

Command:

```powershell
vendor\bin\phpunit --testsuite Unit
```

Result:

```text
Tests: 25
Assertions: 48
Status: Passed
```

### Context manager tests

- Stores and returns context
- Requires context safely
- Same-brand rebinding is allowed
- Different-brand rebinding is rejected
- Missing context is rejected

### Cache tests

- Positive cache uses 600 seconds
- Negative cache uses 60 seconds
- Prefix is applied
- Read and forget operations use the intended key
- Invalid non-array cache content is ignored

### Request-scope tests

- Manager is identical within one scope
- Manager is replaced after scope termination
- New manager contains no prior context
- Resolver is identical within one scope
- Resolver is replaced after scope termination
- Shared cache abstraction survives scoped resets
- Container reads approved TTL configuration

### Existing B1 tests

All hostname, context, resolution, cache reuse, and invalidation tests continue
to pass.

## 11. Full Test Suite

Command:

```powershell
php artisan test
```

Result:

```text
Tests: 26 passed
```

This includes the existing feature homepage response test.

## 12. Syntax and Quality Validation

PHP linting passed for:

- Configuration
- New services
- Modified resolver/provider
- New and modified Unit tests

Git whitespace validation passed.

## 13. Database Integrity

No migration or database write was performed.

Validation:

```text
Maximum migration batch: 5
brand_domains rows: 1
site_info SHA-256:
c47abb52d0b0d35702842ce5d64a29f45121d24bd34514e8aada8b4673ef2558
```

The checksum matches the pre-B2.1 value.

## 14. Application Availability

| Resource | Result |
|---|---|
| Homepage | HTTP 200 |
| Admin login | HTTP 200 |
| Frontend CSS | HTTP 200 |
| Frontend JavaScript | HTTP 200 |

No request currently resolves or binds Brand Context automatically.

## 15. Runtime Behavior

### Public behavior

Unchanged:

- No unknown-host 404 integration
- No hostname middleware
- No context-required routes
- No Settings, Media, or Homepage consumption

### Admin behavior

Unchanged:

- No admin brand context
- No new session key
- No RBAC middleware integration
- No navigation changes
- Existing `AdminMiddleware` unchanged

### Cache behavior

The cache abstraction is registered but no normal request path invokes the
resolver. Therefore B2.1 does not add hostname cache reads or writes to public
or admin requests.

Unit tests use isolated in-memory or mocked cache repositories.

## 16. Risks and Controls

### Provider binding exists before middleware integration

Risk:

- Future code can resolve the services from the container.

Control:

- No current route/controller does so.
- No behavior changes until B2.2/B2.3 integration.

### Scoped lifecycle dependency

Risk:

- Long-running processes require proper scope termination.

Control:

- Laravel scoped bindings are used.
- Scope-reset behavior is unit tested.

### Cache payload compatibility

Risk:

- Future payload schema changes could conflict with old entries.

Control:

- Versioned `brand-domain:v1:` prefix.
- Explicit invalidation support.

## 17. Rollback

Remove:

```text
config/brands.php
app/Services/Brands/BrandContextManager.php
app/Services/Brands/BrandDomainCache.php
tests/Unit/Brands/BrandContextManagerTest.php
tests/Unit/Brands/BrandDomainCacheTest.php
tests/Unit/Brands/BrandContextScopeBindingTest.php
BRAND_CONTEXT_B21_IMPLEMENTATION_REPORT.md
```

Revert:

- `app/Services/Brands/BrandContextResolver.php`
  - Restore direct cache repository and embedded 600/60 TTL defaults.

- `app/Providers/AppServiceProvider.php`
  - Remove Brand Context service bindings.

- `tests/Unit/Brands/BrandContextResolverTest.php`
  - Restore direct in-memory cache construction.

No middleware, route, session, database, public UI, or admin UI rollback is
required.

## 18. Deferred Work

Not started:

- Dynamic trusted-host validation
- Unknown-host exception/404 handling
- Public resolution middleware
- Admin brand context
- Route integration
- Settings integration
- Media integration
- Homepage Builder integration
- Operational cache invalidation command
