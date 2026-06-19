# Brand Context Resolution Plan

**Project:** MAAC Durgapur Multi-Brand CMS  
**Status:** Awaiting approval  
**Prepared:** 19 June 2026  
**Scope:** Automatic request-hostname-to-brand resolution

## 1. Objective

Resolve the active public brand from the incoming request hostname.

Current mapping:

```text
maacdurgapur.local -> MAAC
```

Future mappings:

```text
AKSHA domain       -> AKSHA
Space-E-Fic domain -> Space-E-Fic
```

The resolved brand becomes the authoritative request context for:

- Public Settings
- Public media access
- Homepage Builder rendering
- Brand-scoped public content
- Brand-aware analytics
- Admin context validation where applicable

This plan does not authorize implementation.

## 2. Core Design Principle

Public brand context and admin editing context are related but distinct.

### Public context

Resolved from the trusted request hostname:

```text
HTTP Host -> brand_domains -> brands
```

### Admin context

Resolved from:

1. The trusted hostname's brand, where the admin surface is brand-specific; or
2. An explicitly selected brand that the authenticated user is authorized to
   access.

An admin-selected brand must never alter the public hostname mapping.

## 3. Current Data Foundation

Existing tables:

- `brands`
- `brand_domains`
- `brand_user`
- `user_roles`

Current relevant data:

- MAAC brand is active.
- `maacdurgapur.local` maps to MAAC.
- AKSHA and Space-E-Fic brand records exist.
- Their production/local domains are not yet configured.

The hostname mapping belongs in `brand_domains`, not `.env`, route files, or
hardcoded conditionals.

## 4. Planned File Inventory

### New service files

1. `app/Services/Brands/BrandContextResolver.php`
2. `app/Services/Brands/BrandContext.php`
3. `app/Services/Brands/HostnameNormalizer.php`
4. `app/Services/Brands/BrandDomainCache.php`

### New middleware

5. `app/Http/Middleware/ResolveBrandContext.php`
6. `app/Http/Middleware/RequireBrandContext.php`
7. `app/Http/Middleware/ResolveAdminBrandContext.php`

### New exception

8. `app/Exceptions/BrandContextNotFoundException.php`

### New configuration

9. `config/brands.php`

### Tests

10. `tests/Unit/Brands/HostnameNormalizerTest.php`
11. `tests/Unit/Brands/BrandContextResolverTest.php`
12. `tests/Feature/Brands/PublicBrandResolutionTest.php`
13. `tests/Feature/Brands/AdminBrandResolutionTest.php`
14. `tests/Feature/Brands/UnknownHostTest.php`
15. `tests/Feature/Brands/BrandCacheInvalidationTest.php`

### Existing files likely modified

16. `app/Http/Kernel.php`
17. `app/Providers/AppServiceProvider.php`
18. `app/Exceptions/Handler.php`
19. `app/Http/Middleware/TrustProxies.php`, only if proxy requirements demand it
20. `routes/web.php`
21. `routes/admin.php`

No public controller or Blade template should be changed until its consuming
module is approved.

## 5. `BrandContext` Design

`BrandContext` is an immutable request-scoped value object.

Proposed properties:

```text
brand
domain
hostname
scheme
source
isPreview
isPrimaryDomain
shouldRedirectToPrimary
```

Possible source values:

- `hostname`
- `admin_selection`
- `approved_fallback`
- `test_override`

The object must not:

- Query the database
- Mutate session state
- Infer authorization
- Contain secrets

It represents resolved context, not access permission.

## 6. Hostname Normalization

The resolver must normalize the request host before lookup.

Normalization steps:

1. Obtain the framework-trusted host.
2. Convert to lowercase.
3. Remove a trailing dot.
4. Remove the port.
5. Normalize internationalized domains to a consistent ASCII form when
   supported.
6. Reject invalid hostname syntax.
7. Reject control characters and header-injection patterns.

Examples:

```text
MAACDURGAPUR.LOCAL:80 -> maacdurgapur.local
maacdurgapur.local.   -> maacdurgapur.local
```

The implementation must use Laravel/Symfony's parsed request host rather than
manually trusting the raw `Host` header.

## 7. Trusted Proxy and Host Security

### Host validation

`TrustHosts` is currently disabled.

Before production activation:

- Enable trusted-host validation or implement an equivalent allowlist derived
  from active `brand_domains`.
- Include explicitly required infrastructure/health hosts.
- Reject unrecognized Host headers before generating absolute URLs.

### Proxy handling

If production uses a reverse proxy or load balancer:

- Configure trusted proxies explicitly.
- Trust forwarded host/protocol headers only from those proxies.
- Do not trust arbitrary `X-Forwarded-Host`.

Local XAMPP does not require forwarded-host handling.

## 8. `BrandContextResolver` Design

Primary interface:

```text
resolve(Request $request): BrandContext
```

Supporting interface:

```text
resolveHostname(string $hostname): BrandContext
```

Resolution sequence:

1. Normalize the hostname.
2. Check the request-local resolution cache.
3. Check the shared hostname mapping cache.
4. Query `brand_domains` by exact normalized hostname.
5. Require domain status `active`.
6. Load the related brand.
7. Require brand status `active`.
8. Build immutable `BrandContext`.
9. Store it in request scope.
10. Return it.

The resolver must use exact hostname equality. Wildcard matching is deferred
unless explicitly approved.

## 9. Middleware Design

### `ResolveBrandContext`

Responsibilities:

- Resolve the request brand.
- Bind `BrandContext` into Laravel's container for the current request.
- Add the context to request attributes.
- Share the context with views only after public module integration is
  approved.
- Continue or trigger configured unknown-host behavior.

Suggested alias:

```text
brand.resolve
```

### `RequireBrandContext`

Responsibilities:

- Require a valid active brand context.
- Throw a controlled exception if none exists.

Suggested alias:

```text
brand.required
```

This allows selected operational routes, such as health checks, to exist
without brand context while public pages require it.

### `ResolveAdminBrandContext`

Responsibilities:

1. Resolve hostname brand.
2. Read an optional authenticated admin selection.
3. Confirm selected brand exists and is active.
4. Confirm `brand_user` membership.
5. Confirm RBAC access.
6. Resolve the final admin editing context.

Suggested alias:

```text
admin.brand
```

It must not modify `AdminMiddleware`.

## 10. Request Lifecycle Integration

### Public request

```text
Web server
  -> public/index.php
  -> Laravel global middleware
  -> trusted proxy/host validation
  -> web middleware group
  -> ResolveBrandContext
  -> RequireBrandContext
  -> route/controller
  -> Settings/Homepage/Media consumers
  -> response
```

### Admin request

```text
Web middleware
  -> existing AdminMiddleware
  -> ResolveAdminBrandContext
  -> RBAC policy/permission check
  -> controller
```

### Integration approach

Initial rollout should apply middleware to explicit route groups rather than
immediately adding it to every global request.

Recommended first public group:

```text
Homepage and brand public pages
```

Exclude until reviewed:

- Login routes
- Health checks
- Webhook endpoints
- Static assets
- API routes without brand ownership

## 11. Container Binding

The resolved context should be request-scoped.

Consumers request:

```text
BrandContext
```

They should not repeatedly:

- Inspect the hostname
- Query `brand_domains`
- Read a global static property
- Read brand IDs from request input

One request must resolve to one immutable public brand context.

## 12. RBAC Integration

Brand resolution is not authorization.

### Public requests

No user permission is required to resolve a public brand.

### Admin requests

After context resolution:

1. Confirm active `brand_user` membership.
2. Pass the resolved brand to `PermissionResolver`.
3. Require the relevant permission.

Example:

```text
PermissionResolver.check(
  user,
  settings.brand.view,
  resolvedBrand
)
```

### Super Admin behavior

The current bootstrap Super Admin:

- Has global role permissions.
- Has MAAC membership only.

Current resolver behavior:

- MAAC access: allowed
- AKSHA access: denied until AKSHA membership exists
- Space-E-Fic access: denied until membership exists

Brand context middleware must preserve this behavior.

### Prohibited behavior

- Do not authorize based on domain alone.
- Do not infer membership from Super Admin role.
- Do not infer role from legacy `user_type`.
- Do not accept a posted `brand_id` without checking the resolved context and
  membership.

## 13. Settings Integration

When public Settings integration is approved:

```text
BrandContext.brand
  -> SettingsResolver
  -> published brand/locale values
  -> global fallback
  -> definition default
```

Settings cache keys must use stable brand identity:

```text
brand UUID + locale + publication UUID
```

Do not use:

- Hostname as the only Settings cache key
- Draft values
- Admin-selected context for public pages

Multiple domains may map to the same brand and should receive the same
published Settings bundle unless domain-specific Settings are introduced
later.

## 14. Media Integration

Media consumers receive the resolved brand.

Rules:

- Brand-owned media must match the resolved brand.
- Shared media may be used when explicitly classified and eligible.
- Private media is never made public because its brand matches.
- Public delivery verifies asset status, visibility, and classification.
- Usage tracking records the consuming brand.

Example:

```text
Resolved MAAC request
  -> MAAC media or approved shared media
  -> reject AKSHA-owned private/public media unless explicitly shared
```

Media URLs must not reveal storage paths or bypass access checks.

## 15. Homepage Builder Integration

The public homepage lookup becomes:

```text
Resolved brand
  -> active homepage for brand
  -> published revision
  -> ordered enabled sections
```

Rules:

- Never fall back from an AKSHA hostname to the MAAC homepage silently.
- Preview revisions require separate authenticated preview context.
- A missing published homepage uses controlled fallback behavior.
- Homepage cache keys use brand and published revision identifiers.

Current hardcoded homepage remains untouched until Homepage Builder rollout is
approved.

## 16. Future Domain Configuration

AKSHA and Space-E-Fic require `brand_domains` rows before activation.

Each domain record specifies:

- Hostname
- Scheme
- Primary flag
- Preview flag
- Redirect-to-primary behavior
- Active status

Production domains should be inserted through:

- An approved deployment seeder/configuration command, or
- A future secured brand-domain admin module

They must not be hardcoded inside middleware.

## 17. Fallback Behavior

Fallback must be explicit by environment and route type.

### Local development

Approved options:

1. Exact configured local hostname mapping
2. Optional explicit development fallback to the primary brand

Recommended default:

- `maacdurgapur.local` resolves to MAAC.
- Unknown local hosts fail clearly rather than silently mapping to MAAC.

This exposes missing host configuration early.

### Production public requests

Unknown hostname:

```text
Return HTTP 404 or 421
```

Recommended:

- HTTP 404 with a generic branded-neutral page unless infrastructure supports
  421 consistently.
- No database or framework details.
- No redirect to MAAC.

Inactive domain:

- HTTP 404 or configured canonical redirect only when explicitly approved.

Inactive/archived brand:

- HTTP 404 or maintenance response.
- Never fall back to another brand.

### Missing homepage

If the hostname and brand resolve but no published homepage exists:

- Use a brand-specific controlled maintenance/coming-soon response, or
- Use the current approved legacy brand page during migration.

Do not use another brand's homepage.

### Admin fallback

If no selected admin brand exists:

1. Use hostname brand when the user has membership and permission.
2. Otherwise use the user's default assigned brand.
3. If multiple brands exist and none is default, require selection.
4. Never choose the first database row.

## 18. Canonical Domain Redirects

When a domain has:

```text
redirect_to_primary = true
```

The resolver may return the brand context plus a canonicalization instruction.

A separate middleware performs the redirect.

Rules:

- Preserve safe path and query string.
- Use configured primary scheme and hostname.
- Avoid redirect loops.
- Do not redirect POST/PUT/PATCH/DELETE automatically.
- Preview domains do not redirect unless explicitly configured.

Brand resolution and canonical redirect should remain separate concerns.

## 19. Cache Strategy

### Request-local cache

Every request resolves a hostname at most once.

Key:

```text
normalized hostname
```

### Shared cache

Cache mapping:

```text
hostname
  -> domain ID
  -> brand ID/UUID
  -> domain flags
  -> active statuses
```

Suggested key:

```text
brand-domain:v1:{normalized-hostname}
```

### Cache duration

Recommended:

- 5–15 minutes initially
- Longer only after reliable invalidation exists

### Negative caching

Unknown hostnames may be cached briefly:

```text
30–60 seconds
```

This reduces database load from invalid Host traffic without delaying newly
added domains for long.

### Invalidation

Invalidate when:

- Brand domain created, updated, activated, deactivated, or deleted
- Brand status changes
- Primary domain changes
- Redirect behavior changes

Until a domain-management service exists, deployment procedures must clear
brand-domain cache after database changes.

### Cache safety

- Cache only public identifiers and flags.
- Do not cache Eloquent models long-term.
- Do not cache user permissions in the domain cache.
- Admin membership and permission checks remain separate.
- Unknown-host cache entries must not become MAAC fallback entries.

## 20. Database Query Strategy

Preferred query:

```text
brand_domains.hostname = normalized hostname
AND brand_domains.status = active
JOIN brands
AND brands.status = active
```

Existing indexes:

- Unique `brand_domains.hostname`
- `brand_domains.brand_id + status`
- `brands.status`

One cache miss should require one joined/eager-loaded query.

## 21. Error Handling and Observability

Log safe metadata:

- Normalized hostname
- Outcome
- Domain ID when found
- Brand UUID/code when found
- Resolution source
- Request correlation ID

Do not log:

- Cookies
- Authorization headers
- Full query strings containing personal data
- Raw forwarded-host headers from untrusted sources

Metrics:

- Resolution success by brand
- Unknown-host count
- Inactive-domain attempts
- Cache hit/miss rate
- Canonical redirects
- Brand-context resolution latency
- Admin brand-access denials

## 22. Security Risks

### Host-header poisoning

Control:

- Trusted host validation
- Normalized exact-match lookup
- Correct proxy trust
- No fallback-generated absolute URLs

### Cross-brand data leakage

Control:

- Pass resolved brand into every scoped query
- Do not accept client brand IDs as authority
- Add cross-brand feature tests

### Stale domain cache

Control:

- Versioned cache keys
- Explicit invalidation
- Modest initial TTL

### Open redirects

Control:

- Redirect only to configured active primary domain
- Construct destination from stored normalized fields
- Reject arbitrary return hosts

### Admin session context tampering

Control:

- Store only brand ID/UUID
- Revalidate every request
- Require membership and permission

## 23. Testing Plan

### Hostname normalization

- Lowercase conversion
- Port removal
- Trailing-dot removal
- Invalid host rejection
- Internationalized-domain normalization

### Public resolution

- `maacdurgapur.local` resolves MAAC
- Unknown hostname returns configured failure
- Inactive domain fails
- Inactive brand fails
- Multiple domains can resolve one brand

### Admin resolution

- MAAC bootstrap administrator resolves MAAC
- Same user cannot select AKSHA
- Posted or session-tampered brand is denied
- Revoked membership invalidates context immediately

### Consumer isolation

- MAAC context cannot load AKSHA Settings
- MAAC context cannot load AKSHA media
- MAAC context cannot load AKSHA homepage

### Cache

- Cache hit avoids repeat query
- Domain update invalidates mapping
- Negative cache expires
- Brand deactivation invalidates access

### Regression

- Homepage remains HTTP 200 during shadow rollout
- Admin login remains HTTP 200
- Login behavior unchanged
- `AdminMiddleware` unchanged
- `site_info` checksum unchanged

## 24. Rollout Order

### Phase B1 — Resolver foundation

- Value object
- Hostname normalizer
- Resolver
- Unit tests
- No route integration

### Phase B2 — Shadow resolution

- Run resolver on selected requests
- Compare expected hostname mapping
- Do not change controller behavior
- Log only safe mismatch metadata

### Phase B3 — Explicit public route group

- Apply middleware to homepage/brand pages
- Keep legacy rendering
- Confirm resolved MAAC context

### Phase B4 — Admin context

- Add authenticated admin context middleware
- Preserve `AdminMiddleware`
- Enforce membership and RBAC

### Phase B5 — Module consumption

Separately approved integrations:

1. Settings
2. Media
3. Homepage Builder
4. Analytics

### Phase B6 — Production domains

- Add approved AKSHA and Space-E-Fic domains
- Validate DNS/TLS/web-server configuration
- Warm/invalidate cache
- Run isolation tests

## 25. Rollback Strategy

### Resolver rollback

- Remove middleware from route groups.
- Public requests return to legacy behavior.
- Preserve `brand_domains` data.

### Cache rollback

- Disable shared mapping cache.
- Resolve directly from database.
- Do not delete domain records.

### Module rollback

- Each consuming module retains its own legacy/read fallback during rollout.
- Disabling brand resolution must not silently serve MAAC for other domains.

### No database rollback expected

The current schema already supports resolution. No migration is planned.

## 26. Implementation Blockers

Before implementation:

1. Approve unknown-host HTTP behavior.
2. Approve whether local unknown hosts fail or use an explicit development
   fallback.
3. Confirm production proxy/load-balancer topology.
4. Approve trusted-host strategy.
5. Define AKSHA and Space-E-Fic domain names before their activation.
6. Decide whether the admin panel is hosted on every brand domain or one
   central admin domain.
7. Approve admin default-brand selection behavior.

## 27. Definition of Done

Brand context resolution is complete when:

1. `maacdurgapur.local` resolves exactly to MAAC.
2. Unknown hosts do not silently resolve to MAAC.
3. Inactive domains and brands do not resolve.
4. One immutable context is available per request.
5. Public consumers use hostname context, not posted brand IDs.
6. Admin consumers additionally enforce membership and RBAC.
7. MAAC cannot read AKSHA or Space-E-Fic data.
8. Settings, Media, and Homepage consumers use the same context contract.
9. Mapping cache invalidates safely.
10. Host-header and proxy security tests pass.
11. Legacy fallback is explicit and reversible.

## 28. Approval Gate

Approval is required for:

1. Middleware/service file inventory
2. Unknown-host response
3. Local fallback policy
4. Trusted-host/proxy approach
5. Admin context selection rules
6. Cache TTL and invalidation
7. Rollout sequence

No implementation should begin until this plan is approved.
