# MAAC Durgapur CRM + CMS Professional Audit

Audit date: June 25, 2026  
Branch: `production-refactor`  
Framework: Laravel 9.52.16 / PHP 8.3.22  
Scope: application code, routes, middleware, services, models, Blade templates, public files, configuration, dependencies, tests, assets, and repository hygiene.

## Executive Summary

The application is functional and contains a strong newer foundation for brand context, CMS authorization, trusted-host validation, security headers, request throttling, and content sanitization. However, it is not yet enterprise-ready because the newer brand-aware authorization design is not consistently applied to legacy admin modules.

The highest remaining risk is cross-brand access: the admin route group resolves a selected brand, but legacy Lead, Follow-up, Notification, Blog, Lead Form, Recruiter, Placement, Course, Service, and older content controllers commonly query records globally. `AdminMiddleware` treats every user with `user_type = Admin` as globally privileged, while the newer permission services implement substantially finer access controls.

Three safe remediation groups were applied:

1. Hardened mass assignment, profile access/upload validation, logout semantics, webhook validation, and public diagnostics.
2. Made dynamic form flags compatible with Laravel configuration caching.
3. Blocked direct web access to repository-root diagnostic PHP files and backup archives without deleting them.

No database schema, migration, deployment flow, production server, main branch, Lead model, Brand model, or NotificationService changes were made.

## Scores

| Area | Score | Assessment |
|---|---:|---|
| Overall code quality | 61/100 | Mixed modern and legacy architecture |
| Security | 58/100 | Good baseline controls; authorization gaps remain |
| Performance | 64/100 | Useful caching exists; repeated queries and unbounded reads remain |
| Maintainability | 55/100 | Large controllers, duplication, stale tests, and style debt |
| Laravel best practices | 59/100 | New CMS follows good patterns; legacy modules do not |
| Production readiness | 57/100 | Deployable with safeguards, not enterprise-ready |

Risk rating: **High until legacy admin authorization and brand isolation are completed.**

## Implemented Fixes

### Security hardening

Commit: `00a5ba3 Harden admin input and diagnostic endpoints`

- Replaced broad request mass assignment in Blog Category, Lead Form, Recruiter, and Placement Showcase writes.
- Restricted profile display to the authenticated user.
- Added profile image MIME, image, and size validation.
- Changed logout from GET to CSRF-protected POST.
- Made WhatsApp webhook authentication fail closed when its token is absent or invalid.
- Used constant-time token comparison and stopped storing rejected webhook payloads.
- Restricted `public/audit.php` and `public/test_dashboard.php` to the local environment.

### Configuration-cache compatibility

Commit: `dac7d59 Preserve dynamic form flags in config cache`

- Moved dynamic form environment reads into `config/brands.php`.
- Replaced runtime `env()` calls in `PageController` and `AppServiceProvider` with `config()`.
- Verified MAAC, AKSHA, and SPACE-E-FIC flags remain enabled after `config:cache`.

### Repository-root web exposure

Commit: `5f5bb93 Block root diagnostics and backup archives`

- Denied direct Apache access to all PHP files except the root front controller.
- Denied `.zip`, `.tar`, `.gz`, and `.7z` backup/archive downloads.
- Preserved all existing files and deployment routing.

## Top 20 Security Improvements

| # | Severity | File/path | Problem and risk | Recommended fix | Safe? | Impact |
|---:|---|---|---|---|---|---|
| 1 | Critical | `app/Http/Middleware/AdminMiddleware.php`, legacy admin controllers | Every `user_type=Admin` is effectively global; selected-brand and permission context are not enforced. Cross-brand IDOR and privilege escalation are possible. | Add permission middleware/policies and require selected-brand scope for every legacy admin action. | Needs access-matrix approval | Very high |
| 2 | Critical | `app/Http/Controllers/Admin/LeadManagementController.php` | List, detail, mutation, deletion, and exports use unrestricted Lead queries. | Scope all operations to accessible/current brands; separately authorize global super admins. | Needs business confirmation | Very high |
| 3 | Critical | `app/Http/Controllers/Admin/FollowupController.php` | Follow-ups and related Leads can be accessed or mutated by numeric ID without brand authorization. | Resolve follow-ups through a brand-scoped Lead relation and authorize each mutation. | Needs business confirmation | Very high |
| 4 | High | `app/Http/Controllers/Admin/NotificationController.php`, `app/Services/NotificationService.php` | Admin users bypass brand/user notification ownership checks. | Replace `user_type` bypass with global-super-admin assignment and selected-brand checks. | Needs permission tests | High |
| 5 | High | `app/Http/Controllers/Admin/LeadFormController.php` | Any admin can select and mutate fields for any brand by request ID. | Restrict available brands and records through `AdminBrandAccessResolver`. | Yes after tests | High |
| 6 | High | `app/Http/Controllers/Admin/BlogController.php` | Brand selection and record mutations are not tied to accessible brands. | Enforce selected-brand scope and content permissions. | Needs editorial policy | High |
| 7 | High | `app/Http/Controllers/Admin/RecruiterController.php`, `PlacementShowcaseController.php` | Toggle/reorder endpoints accept arbitrary IDs and are not brand-scoped. | Validate arrays and scope every ID to the selected brand. | Yes after tests | High |
| 8 | High | `app/Http/Controllers/Admin/AkshaMajorProgramController.php`, `AkshaSupportingCourseController.php` | Hardcoded/first-brand assignment can write content to the wrong tenant. | Require the selected AKSHA brand context and reject mismatches. | Yes after data review | High |
| 9 | High | Repository root | `storage_backup.zip` is tracked and contains potentially sensitive storage data. Web download is now blocked, but repository exposure remains. | Remove it from Git in a separately approved cleanup and rotate any exposed secrets. | Destructive approval required | High |
| 10 | High | `temp_check.php` | Hardcoded `admin@gmail.com` / `123456` credential probe is tracked. Web access is now blocked, but the credential should be considered compromised if ever valid. | Remove the script and rotate the account password. | Approval required | High |
| 11 | High | `app/Http/Controllers/Api/WhatsappWebhookController.php` | Token validation is improved but is not provider-specific signed-payload verification. | Implement the provider's documented HMAC/signature scheme with timestamp/replay validation. | Requires provider details | High |
| 12 | Medium | `WhatsappWebhookLog` processing | Accepted webhook payloads may contain phone numbers and message content indefinitely. | Minimize/redact payloads and define retention/deletion policy. | Yes | Medium |
| 13 | Medium | `app/Http/Middleware/ContentSecurityPolicyMiddleware.php` | CSP allows `unsafe-inline` and `unsafe-eval`, weakening XSS protection. | Migrate inline JS/CSS to versioned assets and adopt nonce/hash CSP. | Staged migration | High |
| 14 | Medium | `config/session.php` | Secure cookies default to false and session data is not encrypted. | Enforce `SESSION_SECURE_COOKIE=true` in production; assess session encryption. | Environment change | Medium |
| 15 | Medium | `config/cors.php` | API CORS permits all origins, methods, and headers. | Restrict origins and methods to actual consumers. | Requires integration inventory | Medium |
| 16 | Medium | `app/Providers/AppServiceProvider.php` | Slow-query logs include SQL bindings, which can contain PII. | Redact bindings in production or log only fingerprints/timing. | Yes | Medium |
| 17 | Medium | `app/Http/Middleware/RequestProfilerMiddleware.php` | Slow request logs include full URLs, potentially including query-string PII or tokens. | Log route/template and redacted query keys, not full URLs. | Yes | Medium |
| 18 | Medium | Legacy upload controllers/helpers | Upload handling is inconsistent and relies on controller-specific validation. | Centralize validated image/document storage, generated names, and allowed MIME rules. | Incremental | High |
| 19 | Low | `SecurityHeadersMiddleware.php` | HSTS is absent and deprecated `X-XSS-Protection` is enabled. | Add HSTS only after confirming all production traffic is HTTPS; remove deprecated header. | Environment-dependent | Medium |
| 20 | Low | Admin profile route | `/profile/{name}` retains an unused identity parameter after access was restricted. | Preserve route compatibility now; deprecate parameter in a versioned route cleanup. | Yes later | Low |

## Top 20 Performance Improvements

| # | Severity | File/path | Problem and risk | Recommended fix | Safe? | Impact |
|---:|---|---|---|---|---|---|
| 1 | High | `app/Services/AnalyticsService.php` | Brand analytics performs two Lead count queries per brand. | Replace loop queries with grouped conditional aggregates. | Yes | High |
| 2 | High | `AnalyticsService.php` counsellor metrics | Multiple Lead count queries run per counsellor. | Compute grouped assignment/status aggregates in one query. | Yes | High |
| 3 | High | `LeadManagementController::export` | Loads the entire filtered Lead dataset into memory before streaming. | Use `cursor()`/`lazyById()` and stream rows incrementally. | Yes | High |
| 4 | Medium | `resources/views/admin/layout/leftmenu.blade.php` | Executes a follow-up count query directly in every menu render. | Supply a cached/scoped count through a view composer/service. | Yes | Medium |
| 5 | Medium | `resources/views/frontend/layout/app.blade.php` | Executes a Brand lookup directly in Blade. | Pass resolved brand ID from context/controller/composer. | Yes | Medium |
| 6 | Medium | `resources/views/admin/pages/lead_management/show.blade.php` | Queries latest WhatsApp message inside the view. | Eager-load or select the latest relation in the controller. | Yes | Medium |
| 7 | Medium | `PageController` | Repeats active-course queries across many actions. | Cache active courses or use a shared read service. | Yes | Medium |
| 8 | Medium | `PageController::index` | Recruiter cache key is global and not brand-specific. | Include brand ID/version in the cache key. | Yes | Medium |
| 9 | Medium | `PageController::index` | Placement data is uncached while similar recruiter data is cached. | Add short brand-specific cache with model invalidation. | Yes | Medium |
| 10 | Medium | `AppServiceProvider` layout composer | Performs Brand lookup on every frontend layout render. | Cache brand ID/domain context or consume `BrandContextManager`. | Yes | Medium |
| 11 | Medium | Legacy index controllers | Multiple modules use unbounded `get()`/`all()` for admin tables. | Paginate and select only displayed columns. | Usually | Medium |
| 12 | Medium | `NotificationController` | Search uses leading-wildcard LIKE across title/message. | Add bounded date filters; consider full-text search if scale requires it. | Schema-dependent | Medium |
| 13 | Medium | Public assets | `public/` is roughly 50 MB with large/duplicate image assets. | Produce an asset inventory, WebP/AVIF variants, and remove only verified unused files. | Needs visual QA | High |
| 14 | Medium | Blade/JS architecture | Large inline scripts prevent effective CSP and long-term browser caching. | Move scripts to Vite-managed files and split by page. | Staged | High |
| 15 | Low | `AppServiceProvider` | Global DB listener and query counter run on every request. | Enable detailed profiling only in local/staging; keep lightweight production timing. | Yes | Medium |
| 16 | Low | `RequestProfilerMiddleware` | Generates and serializes stack traces for every request over three seconds. | Sample traces or limit them to non-production/diagnostic mode. | Yes | Low |
| 17 | Low | `AnalyticsService` | Several dashboard metrics use independent queries that can share aggregates. | Consolidate status/date metrics and cache as one dashboard snapshot. | Yes | Medium |
| 18 | Low | Cache invalidation | Every Lead/Follow-up/WhatsApp save clears all analytics cache keys. | Invalidate only affected metric groups or use tagged/versioned caches. | Yes | Medium |
| 19 | Low | Route/config/view caches | Commands work, but test execution after cached local config can ignore PHPUnit environment overrides. | Clear config cache before tests in CI scripts. | Yes | Medium |
| 20 | Low | Composer | Optimized autoload works but reports Git safe-directory ownership noise. | Correct repository ownership or configure safe.directory in the deployment user context. | Operational | Low |

## Top 20 General Improvements

1. Complete brand-aware authorization for all legacy admin modules.
2. Add feature tests proving cross-brand records return 403/404.
3. Replace `user_type` authorization with roles and permissions.
4. Introduce Form Request classes for legacy write endpoints.
5. Break `PageController::counselling` into validation, Lead application, and notification orchestration services.
6. Break `LeadManagementController` filtering/export logic into reusable query objects/services.
7. Remove database queries from Blade templates.
8. Eliminate runtime `env()` access outside configuration; the known dynamic-form cases are fixed.
9. Reconcile or remove stale Settings tests referencing deleted classes/modules.
10. Configure SQLite-compatible tests or a dedicated isolated test database.
11. Replace brittle total-route-count assertions with named-route behavior assertions.
12. Address the Pint baseline: 171 files reported style issues across 329 checked files.
13. Add static analysis (Larastan/PHPStan) in CI after baseline cleanup.
14. Add a deterministic frontend build check (`npm ci && npm run build`) in CI.
15. Run Composer and npm vulnerability audits in a network-enabled trusted environment.
16. Remove approved root diagnostics, backups, screenshots, and generated route dumps from Git.
17. Add deployment preflight checks for APP_ENV, APP_DEBUG, HTTPS cookies, queue, mail, and webhook secrets.
18. Add database index review using production-like query plans; no schema changes were made in this audit.
19. Standardize media upload and deletion lifecycle to avoid orphaned files.
20. Add structured audit logging for privileged admin mutations.

## Test and Verification Results

### Passed

- PHP syntax checks for every modified PHP file.
- Apache configuration syntax: `Syntax OK`.
- `composer dump-autoload --optimize`.
- `php artisan optimize`.
- `php artisan route:list` (189 routes).
- `php artisan config:cache`.
- `php artisan route:cache`.
- `php artisan view:cache`.
- Focused brand/host middleware suite: 9 tests passed.
- Earlier focused brand suite: 29 tests / 64 assertions passed.
- Dynamic-form config cache inspection confirmed all three enabled flags survived caching.
- Production-environment execution guards for both public diagnostics returned without running diagnostics.
- Git `diff --check` passed for implemented changes.

### Known failures and limitations

- Full suite baseline: 53 passed, 88 failed.
- Many failures reference removed/stale Settings classes, routes, or methods.
- Database-dependent tests cannot connect to local MySQL at `127.0.0.1:3306`.
- A route-count assertion expects 72 routes while the application currently registers 189.
- Tests must run after `optimize:clear`; a cached local configuration prevents PHPUnit environment overrides from taking effect.
- Composer/npm advisory checks could not be completed because network access was unavailable.
- The local Apache service was not running, so live HTTP denial checks were unavailable; Apache syntax validation passed.

## Remaining Technical Debt

- Authorization is implemented in parallel systems: legacy `user_type` checks and newer role/permission services.
- Controllers contain validation, persistence, notifications, WhatsApp calls, and presentation decisions.
- Legacy modules use inconsistent naming, formatting, validation, and upload patterns.
- Root repository contains diagnostic scripts, generated reports, route dumps, screenshots, SQL files, and a large tracked storage archive.
- Test reliability is low until stale Settings tests and database isolation are corrected.
- Dependency versions are old enough to require a controlled Laravel/PHP compatibility upgrade plan.
- The exact `mews/purifier` version is pinned instead of using a reviewed compatible range.
- CSP modernization is blocked by extensive inline JavaScript and CSS.
- No verified CI pipeline enforces tests, formatting, dependency audits, or frontend builds.

## Deployment Safety

- No production server or deployment configuration was modified.
- No branch was created, renamed, merged, or rewritten.
- `main` was not modified.
- No force push or history rewrite was performed.
- No database schema or migration was changed.
- Changes are isolated in logical commits on `production-refactor`.
- No push was performed during this audit.

Before deployment:

1. Review all three implementation commits.
2. Confirm production uses Apache 2.4 and permits `.htaccess` overrides.
3. Set `APP_ENV=production`, `APP_DEBUG=false`, and `SESSION_SECURE_COOKIE=true`.
4. Confirm all dynamic form environment flags before running `config:cache`.
5. Confirm a non-empty WhatsApp webhook token and plan provider-signature validation.
6. Back up the live database independently of the repository.
7. Run smoke tests for public pages, Lead submission, admin login/logout, notifications, FAQ, showcase, and media uploads.

## Final Assessment

The applied changes materially reduce immediate exposure without altering business behavior. The application should not be represented as enterprise-grade until brand isolation and authorization are consistently enforced across legacy admin modules and the test suite becomes trustworthy.
