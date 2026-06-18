# Sprint 1 Security Execution Plan

Status: Awaiting approval  
Prepared: June 18, 2026  
Scope: Security containment only  
Implementation state: No security changes applied

## 1. Objective

Execute Sprint 1 of the approved implementation backlog without changing
business behavior, redesigning the UI, or beginning any later CMS work.

This sprint is limited to:

- Security containment
- Document-root hardening
- Route hardening
- Upload protection
- Production environment hardening
- Baseline security headers
- Exposure verification and remediation reporting

The sprint explicitly excludes:

- CMS architecture or implementation
- Database redesign
- Homepage Builder
- Lead CRM
- SEO Manager
- Analytics
- Laravel framework upgrades
- General application refactoring
- UI redesign

## 2. Confirmed Current Exposure

Read-only verification found that the current local Apache configuration serves
`C:\xampp\htdocs` and exposes the repository at `/maacdurgapur`.

The following resources returned HTTP 200 during the security assessment:

- `.env`
- `db_dump.sql`
- `composer.json`
- `package.json`
- `storage/logs/laravel.log`
- Uploaded CV PDFs under `upload/file/cv/`

Additional confirmed conditions:

- `APP_ENV=local`
- `APP_DEBUG=true`
- `public/.htaccess` is missing.
- Root `.htaccess` forwards requests through the repository root.
- Public `/clear-cache` and `/config-cache` routes execute Artisan commands.
- Root `upload/` contains both public marketing images and private CV or
  certificate files.

The primary control is to expose only `public/` through the web server.
File-deny rules are defense-in-depth and are not a substitute for the correct
document root.

## 3. Change Control Rules

Before implementation begins:

1. Obtain explicit approval for this plan.
2. Recheck Git status.
3. Preserve all pre-existing user changes.
4. Back up every server configuration file before editing.
5. Record baseline HTTP status for public and sensitive routes.
6. Confirm the intended local and production hostnames.
7. Confirm the real production URL before changing `APP_URL`.
8. Confirm authorization to rotate exposed credentials.

Implementation will use small batches. After each batch, the implementation
report will include:

- Modified files
- Exact security impact
- Validation results
- Rollback instructions
- Any residual risk

No subsequent batch will begin if the current batch fails its validation gate.

## 4. Complete Proposed Change Inventory

The following is the full planned file and configuration inventory. No files
listed here, other than this execution plan, have yet been changed.

### 4.1 Repository files that will be modified

#### 1. `.htaccess`

Reason:

- The repository root is currently inside the global XAMPP document root.
- Existing rules forward requests through the root `index.php`.
- Existing real files bypass rewriting and are served directly.

Planned change:

- Remove its role as the application front controller.
- Deny direct HTTP access to the project root as defense-in-depth.
- Preserve a clearly documented local fallback only if the approved server
  configuration requires it.

Security effect:

- Prevents `/maacdurgapur/.env`, SQL dumps, source manifests, storage files and
  root uploads from being served through the legacy path.

#### 2. `routes/web.php`

Reason:

- It defines duplicate `/clear-cache` routes.
- It defines `/config-cache`.
- These unauthenticated routes execute privileged Artisan operations.

Planned change:

- Remove only the public Artisan closures.
- Remove the now-unused import if one exists.
- Leave all business and marketing routes unchanged.

Security effect:

- Eliminates public cache and configuration mutation endpoints.

#### 3. `.env`

Reason:

- The reviewed environment uses local mode and debug output.
- Production-safe URL and cookie settings are not active.

Planned change:

- Set production values only after the production URL is confirmed:
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_URL=<approved HTTPS production URL>`
  - `SESSION_SECURE_COOKIE=true`
- Update rotated credentials only after explicit authorization and service
  coordination.

Security effect:

- Prevents debug stack traces and sensitive runtime details from being exposed.
- Enforces secure session-cookie transport over HTTPS.

Important:

- Secret values will never be printed in reports.
- The application key will not be rotated blindly because doing so invalidates
  sessions and may make existing encrypted values unreadable.

#### 4. `.env.example`

Reason:

- It currently advertises local/debug defaults.
- New deployments could reproduce insecure settings.

Planned change:

- Document safe production-oriented examples without secrets.
- Add explicit placeholders for:
  - `APP_ENV`
  - `APP_DEBUG`
  - `APP_URL`
  - `SESSION_SECURE_COOKIE`
- Add comments or documentation directing developers to use local overrides
  only in non-production environments.

Security effect:

- Reduces the risk of future insecure environment provisioning.

#### 5. `config/session.php`

Reason:

- Session security depends on environment values.
- Sprint 1 requires an explicit production-safe cookie baseline.

Planned change:

- Retain environment-driven secure-cookie behavior.
- Add or verify environment-driven SameSite and cookie settings if required.
- Do not alter session business behavior or lifetime unless security testing
  requires it.

Security effect:

- Makes production cookie security explicit and deployable.

This file will remain unchanged if the existing environment-driven behavior is
confirmed sufficient after implementation review.

#### 6. `app/Http/Kernel.php`

Reason:

- Baseline security headers need to be applied consistently.

Planned change:

- Register a dedicated security-header middleware in the global HTTP stack.
- Do not alter existing authentication or business middleware.

Security effect:

- Ensures security headers apply to public, admin and error responses.

#### 7. `app/Http/Middleware/SecurityHeaders.php` — new file

Reason:

- Security headers should be centralized, testable and environment-aware.

Planned behavior:

- Add:
  - `X-Content-Type-Options: nosniff`
  - `Referrer-Policy`
  - `Permissions-Policy`
  - Frame protection
- Add HSTS only in approved HTTPS production conditions.
- Add Content Security Policy in report-only mode initially.
- Avoid breaking existing CDN assets during the report-only phase.

Security effect:

- Reduces MIME confusion, referrer leakage, clickjacking and unnecessary browser
  capability exposure.
- Provides CSP telemetry before enforcement.

#### 8. `config/security.php` — new file

Reason:

- Header behavior requires explicit, environment-aware configuration.
- CDN and external asset origins must be allowlisted without hardcoding policy
  inside middleware.

Planned contents:

- Header enablement flags
- HSTS configuration
- CSP report-only configuration
- Approved script, style, image, font, media and connection origins
- Permissions Policy
- Frame policy

Security effect:

- Makes security policy reviewable and deployable per environment.

#### 9. `public/.htaccess` — new file

Reason:

- The canonical public directory currently lacks Laravel rewrite rules.
- The new document root requires a public front-controller configuration.

Planned change:

- Add Laravel-compatible rewriting to `public/index.php`.
- Disable indexes.
- Preserve authorization headers.
- Redirect trailing slashes safely.
- Deny dotfiles and sensitive extensions as defense-in-depth.
- Prevent accidental direct access to unexpected backup or archive files.

Security effect:

- Makes `public/` the correct, hardened Laravel web root.

#### 10. `public/upload/.htaccess` — new file

Reason:

- Public uploads must never execute PHP, CGI, server-side includes or handler
  files.
- Current public upload contents include an archive and HTML file.

Planned change:

- Disable indexes.
- Disable script execution and handlers.
- Deny PHP-like and other executable extensions.
- Allow only approved static media types where practical.

Security effect:

- Reduces the impact of a malicious or misclassified upload.

#### 11. `public/upload/SERVICE.zip`

Reason:

- This archive is publicly downloadable and unnecessary for normal site
  rendering.

Planned change:

- Remove it from the deployable public tree after confirming it is not required.
- Preserve an approved private backup only if operationally necessary.

Security effect:

- Eliminates unnecessary archive exposure and reduces public attack surface.

#### 12. `public/upload/SERVICE/index.html`

Reason:

- This standalone uploaded HTML file is not part of the Laravel application and
  may be directly served.

Planned change:

- Remove or relocate it after confirming no production dependency.

Security effect:

- Prevents independent uploaded HTML from creating an unmanaged public surface.

#### 13. `.gitignore`

Reason:

- Database dumps and private upload directories must not be recommitted.

Planned change:

- Ignore database dump patterns.
- Ignore private CV/certificate storage paths where compatible with the final
  storage arrangement.
- Ignore environment backup files.
- Preserve intentional public media tracking until the Media Manager sprint.

Security effect:

- Reduces recurrence of sensitive-data commits.

#### 14. `SECURITY_REMEDIATION_REPORT.md` — new file

Reason:

- Sprint 1 requires a durable record of findings, changes, validation and
  residual risk.

Planned contents:

- Initial risk assessment
- Modified files and server configuration
- Before/after HTTP verification
- Credential-rotation status without secret values
- Log-review findings
- Remaining risks
- Rollback and operational runbook

Security effect:

- Creates traceability and evidence for remediation approval.

### 4.2 Files that may be modified only if needed after validation

#### 15. `config/filesystems.php`

Reason:

- Private files must resolve to storage outside the public document root.

Conditional planned change:

- Add an explicit private disk if the current local disk is not sufficiently
  clear for CV/certificate storage.
- Do not redesign upload business logic.

This file will not be changed if existing private storage configuration is
sufficient for containment.

#### 16. Existing public image files under `upload/images/`

Reason:

- Root `upload/images/` contains marketing assets referenced by stored database
  paths.
- These assets will no longer be directly available after document-root
  hardening.

Conditional planned action:

- Copy only approved public image assets to `public/upload/images/`.
- Do not copy `upload/file/`, CVs, certificates or other private documents.
- Do not overwrite differing existing public files without checksum review.

Security effect:

- Preserves required public media without re-exposing private files.

This is a controlled asset relocation, not a business-logic change.

### 4.3 Server configuration outside the repository

These changes require separate execution approval because they are outside the
workspace and may require elevated privileges.

#### 17. `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

Reason:

- No project-specific virtual host currently exists.
- Apache's global document root is `C:\xampp\htdocs`.

Planned change:

- Add a project virtual host whose document root is:
  `C:/xampp/htdocs/maacdurgapur/public`
- Set:
  - `Options -Indexes +FollowSymLinks`
  - `AllowOverride All`
  - `Require all granted`
- Add project-specific access/error logs if approved.

Security effect:

- Enforces the correct public boundary locally.

#### 18. `C:\xampp\apache\conf\extra\httpd-ssl.conf`

Reason:

- The current default SSL virtual host serves `C:/xampp/htdocs`.

Conditional planned change:

- Add or update the project TLS virtual host to use the same `public/` document
  root.
- Do not modify unrelated XAMPP SSL behavior unless the project is actually
  served locally over HTTPS.

Security effect:

- Prevents HTTPS from bypassing the HTTP document-root containment.

#### 19. Production Apache/cPanel domain configuration

Reason:

- Repository `.htaccess` cannot enforce the document root by itself.

Planned change:

- Set each production domain's document root to the deployed Laravel `public/`
  directory.
- Configure equivalent directory and TLS rules.

Security effect:

- Provides the primary production containment boundary.

#### 20. `C:\Windows\System32\drivers\etc\hosts`

Reason:

- A dedicated local hostname may be required to exercise the project virtual
  host.

Conditional planned change:

- Map an approved local hostname such as `maacdurgapur.local` to `127.0.0.1`.

Security effect:

- Enables reliable local domain-based verification.

This file will not be changed if testing can use an existing hostname.

### 4.4 Sensitive files targeted for containment or removal

These are not application-source modifications.

#### 21. `db_dump.sql`

- Remove from deployment artifacts.
- Remove from the working tree only with explicit confirmation that an approved
  encrypted backup exists.
- Add recurrence protection through `.gitignore`.
- Git-history rewriting is out of scope unless separately approved.

#### 22. `upload/file/cv/`

- Keep outside the public document root.
- Relocate to approved private storage if still operationally required.
- Never copy into `public/`.

#### 23. `upload/file/certi/`

- Keep outside the public document root.
- Relocate to approved private storage if still operationally required.
- Never copy into `public/`.

#### 24. `storage/`

- No business data will be deleted.
- The directory becomes inaccessible by virtue of the document-root boundary.
- Direct deny verification will be performed.

#### 25. `composer.json` and `package.json`

- These remain required repository files.
- They will not be deleted.
- They become inaccessible through the correct document root and root deny
  rules.

## 5. Files Explicitly Excluded

Sprint 1 will not intentionally modify:

- Database migrations
- Models or business controllers
- CMS architecture files
- Homepage Builder files
- CRM files
- SEO files
- Analytics files
- Course, placement, testimonial or FAQ business behavior
- Existing homepage design
- Existing CSS or JavaScript except where required to verify asset relocation
- Existing user changes in:
  - `public/frontend/css/style.css`
  - `public/frontend/vedio/waterfall_desktop.mp4`
  - `resources/views/frontend/pages/index.blade.php`

If a containment change would require modifying excluded business or UI files,
implementation will stop and request separate approval.

## 6. Execution Batches

## Batch 1 — Route and Repository-Level Containment

### Goals

- Remove public Artisan execution.
- Add hardened public rewrite rules.
- Block direct legacy repository-root access.
- Protect public upload directories from script execution.

### Planned files

- `.htaccess`
- `routes/web.php`
- `public/.htaccess`
- `public/upload/.htaccess`

### Implementation steps

1. Recheck Git status and capture baseline diffs.
2. Remove only the cache/config Artisan route closures.
3. Add canonical Laravel rewrite rules under `public/`.
4. Change root `.htaccess` from application routing to access denial.
5. Add static-only upload protection.
6. Run route listing and inspect the generated route set.
7. Validate Apache configuration syntax before any restart.

### Impact

- Public operational endpoints disappear.
- Legacy direct access through `/maacdurgapur/<private-file>` is blocked.
- Public uploads cannot execute server-side scripts.
- The application requires the project-specific public document root before
  normal routes can be fully verified.

### Validation gate

- `php artisan route:list` contains no `clear-cache` or `config-cache`.
- Apache syntax check passes.
- Upload-deny test fixtures are not required in production; configuration is
  inspected and tested with a harmless temporary file only if approved.
- No business route changes appear in the route diff.

### Rollback

1. Restore the previous `.htaccess` from its recorded backup or Git diff.
2. Remove `public/.htaccess` if newly created.
3. Remove `public/upload/.htaccess` if newly created.
4. Restore `routes/web.php`.
5. Re-run `php artisan route:list`.

Security caveat:

- Rolling back root denial must occur only after an alternative secure document
  root is active. It must not restore public secret exposure.

## Batch 2 — Web-Server Document Root

### Goals

- Make `public/` the actual HTTP and HTTPS boundary.
- Remove dependence on the repository-root URL.

### Planned files/configuration

- `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
- Conditional `C:\xampp\apache\conf\extra\httpd-ssl.conf`
- Conditional local hosts file
- Production/cPanel domain configuration

### Implementation steps

1. Back up each server configuration file.
2. Add the project virtual host using `public/` as `DocumentRoot`.
3. Add a matching `<Directory>` rule with indexes disabled.
4. Add or verify the TLS virtual host.
5. Validate with:
   - `httpd.exe -t`
   - `httpd.exe -S`
6. Restart Apache during an approved window.
7. Verify the new hostname and public routes.
8. Apply the equivalent production domain configuration.

### Impact

- PHP source, environment, storage and root upload files fall outside the web
  namespace.
- Asset URLs generated by Laravel point naturally into `public/`.
- The old `/maacdurgapur` path is intentionally blocked.

### Validation gate

- Homepage and admin login load through the approved hostname.
- HTTP and HTTPS report the intended document root.
- Sensitive files return 403 or 404.
- Public CSS and JavaScript return correct content types.

### Rollback

1. Restore the backed-up virtual-host files.
2. Validate Apache syntax.
3. Restart Apache.
4. Keep repository-root denial in place.
5. If application service must be restored temporarily, use a separate secure
   virtual host or controlled maintenance page rather than re-exposing the
   project root.

## Batch 3 — Environment and Credential Hardening

### Goals

- Disable debug behavior.
- Enforce production environment and secure cookies.
- Rotate exposed credentials through controlled coordination.

### Planned files

- `.env`
- `.env.example`
- Conditional `config/session.php`

### Implementation steps

1. Confirm the production HTTPS URL.
2. Record current environment keys without printing secret values.
3. Update production environment and debug settings.
4. Enable secure session cookies.
5. Rotate database and mail credentials.
6. Evaluate application-key rotation impact before action.
7. Invalidate active sessions after credential/security changes.
8. Clear and rebuild configuration cache through CLI only.
9. Update `.env.example` with safe placeholders.

### Impact

- Detailed exception output is disabled.
- Session cookies are restricted to HTTPS.
- Previously exposed service credentials become unusable.
- Existing sessions may be invalidated.

### Validation gate

- Application reports production mode through a safe CLI check.
- Debug exception details are not returned over HTTP.
- Database connectivity succeeds.
- Mail configuration passes a non-delivery or approved test.
- Session cookies include expected security attributes.

### Rollback

1. Restore only non-secret environment structure from the encrypted backup.
2. Reapply the previous service credential only if the rotated credential
   cannot be made operational and the security owner explicitly approves.
3. Never restore `APP_DEBUG=true` in production.
4. Never restore an exposed credential as a long-term rollback.
5. Clear and rebuild configuration cache after environment rollback.

## Batch 4 — Public/Private Upload Separation

### Goals

- Preserve approved public marketing media.
- Ensure CVs and certificates remain private.
- Remove unnecessary public archive and HTML files.

### Planned files

- Approved copies into `public/upload/images/`
- `public/upload/SERVICE.zip`
- `public/upload/SERVICE/index.html`
- `db_dump.sql`
- `.gitignore`
- Conditional `config/filesystems.php`

### Implementation steps

1. Inventory and checksum root and public upload trees.
2. Classify assets:
   - Approved public images
   - Private documents
   - Unused archives/HTML
   - Duplicates
3. Copy only approved public images required by current database paths.
4. Do not copy `upload/file/`.
5. Remove or privately archive `SERVICE.zip` and unmanaged `index.html`.
6. Remove the SQL dump from deployable artifacts after backup confirmation.
7. Add ignore rules preventing recurrence.
8. Verify every currently rendered public image.
9. Verify all private paths return 403 or 404.

### Impact

- Marketing images remain available after document-root cutover.
- Private applicant files are no longer directly addressable.
- Public archive and unmanaged HTML attack surface is removed.
- Repository/deployment hygiene improves.

### Validation gate

- Homepage, MAAC page and admin images render.
- All referenced public assets return expected image MIME types.
- CV and certificate paths fail closed.
- No private document exists under `public/`.
- No source file is overwritten without checksum review.

### Rollback

1. Restore approved public media from the recorded backup.
2. Do not restore private files beneath `public/`.
3. Restore the SQL dump only to encrypted private backup storage, never to the
   deployed web tree.
4. Revert `.gitignore` only if its patterns block legitimate public assets.

## Batch 5 — Security Headers

### Goals

- Apply a consistent browser security baseline.
- Introduce CSP safely in report-only mode.

### Planned files

- `app/Http/Middleware/SecurityHeaders.php`
- `app/Http/Kernel.php`
- `config/security.php`
- `.env.example` for relevant policy toggles if required

### Implementation steps

1. Inventory all current external script, style, font, image and media origins.
2. Define the initial report-only CSP.
3. Add environment-aware security-header middleware.
4. Register it globally.
5. Enable HSTS only for production HTTPS.
6. Confirm admin and public pages retain required functionality.
7. Review CSP reports before any later enforcement.

### Impact

- Browser defenses improve without changing business logic.
- CSP violations are observable without initially blocking resources.
- Framing, MIME and referrer behavior become explicit.

### Validation gate

- Headers appear on public, admin, JSON and error responses.
- HSTS appears only under approved HTTPS production conditions.
- No duplicate or contradictory frame policy is emitted.
- Public and admin JavaScript continue functioning.
- CSP remains report-only during Sprint 1.

### Rollback

1. Remove middleware registration from `Kernel.php`.
2. Remove or disable the header middleware through configuration.
3. Retain document-root and route containment.
4. Do not roll back unrelated security batches.

## Batch 6 — Exposure Review, Verification and Reporting

### Goals

- Prove containment.
- Document credential and personal-data exposure response.
- Deliver the sprint security report.

### Planned files

- `SECURITY_REMEDIATION_REPORT.md`
- No business source files

### Implementation steps

1. Run the complete sensitive-path HTTP matrix.
2. Run public and admin smoke tests.
3. Review Apache access logs for historical requests to:
   - `.env`
   - SQL dumps
   - storage logs
   - CV/certificate paths
   - manifests
4. Record findings without copying personal data into reports.
5. Record credential rotation completion.
6. Record residual risks and deferred Sprint 2+ work.
7. Produce final Git diff and modified-file inventory.

### Impact

- Provides remediation evidence and operational handoff.
- Identifies whether privacy or incident escalation is required.

### Validation gate

- All Sprint 1 acceptance criteria pass.
- No critical sensitive path returns HTTP 200.
- Security report is complete.
- Existing business routes and UI smoke tests pass.

### Rollback

- Reporting itself requires no rollback.
- Correct factual errors through a new report revision.
- Preserve evidence and never delete security audit records to conceal failed
  checks.

## 7. Credential Rotation Plan

Because `.env` was publicly accessible, assume all contained secrets may be
compromised.

Rotation order:

1. Inventory integrations without displaying values.
2. Create new database credential.
3. Update application environment.
4. Verify database connection.
5. Revoke old database credential.
6. Rotate mail credential and verify configuration.
7. Rotate third-party credentials.
8. Review application-key usage.
9. If application-key rotation is approved:
   - Confirm encrypted-data inventory.
   - Schedule session invalidation.
   - Rotate key.
   - Validate encrypted integrations/data.
10. Invalidate sessions and remember tokens.

Application-key rotation is a security decision requiring explicit approval
because it may make existing encrypted values unrecoverable.

## 8. Security Header Baseline

Proposed baseline:

```text
X-Content-Type-Options: nosniff
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
X-Frame-Options: SAMEORIGIN
Content-Security-Policy-Report-Only: <approved policy>
Strict-Transport-Security: max-age=<approved>; includeSubDomains
```

HSTS requirements:

- Production HTTPS must be fully operational.
- Every affected subdomain must support HTTPS before `includeSubDomains`.
- `preload` will not be enabled during Sprint 1.

CSP begins in report-only mode because the existing frontend uses:

- CDN scripts and styles
- Google Fonts
- Inline scripts and styles
- GSAP, Three.js and Swiper

Enforced CSP requires later nonce/hash work and is not part of this containment
sprint.

## 9. Upload Protection Policy

### Public media

Allowed purpose:

- Images
- Fonts
- Approved videos
- Approved static downloadable marketing documents

Controls:

- No script execution
- No directory listing
- Approved MIME/extension allowlist
- No unmanaged HTML
- No public archives unless explicitly required

### Private media

Includes:

- CVs
- Certificates
- Applicant documents
- Lead attachments
- Administrative exports

Controls:

- Stored outside `public/`
- No direct URL
- Authenticated and authorized delivery
- Audit logging
- Appropriate response headers

Sprint 1 provides containment. A full Media Manager implementation remains
reserved for Sprint 3.

## 10. Validation Matrix

### Sensitive paths

Every path must return 403 or 404:

```text
/.env
/db_dump.sql
/composer.json
/composer.lock
/package.json
/package-lock.json
/artisan
/storage/logs/laravel.log
/storage/framework/sessions/
/vendor/
/routes/web.php
/upload/file/cv/<known-test-file>
/upload/file/certi/<known-test-file>
/clear-cache
/config-cache
```

### Public application

Must remain operational:

```text
/
/maac
/aksha
/space-e-fic
/fcq
/showcase
/blog
/faq
/terms-and-condition
/admin-login
POST /career-counselling
```

Known pre-existing broken routes will be documented but not repaired during
Sprint 1.

### Assets

Verify:

- CSS has `text/css`.
- JavaScript has an appropriate JavaScript MIME type.
- Images have correct image MIME types.
- Videos remain streamable.
- No requested asset is served as a Laravel HTML error response.

### Headers

Verify on:

- Homepage
- Admin login
- JSON endpoint
- 404 response
- HTTPS response

### Environment

Verify without exposing secrets:

- Production mode
- Debug disabled
- Correct application URL
- Secure session cookie
- Database connection
- Configuration cache state

## 11. Testing Checklist

### Automated/static

- [ ] PHP syntax checks pass for changed PHP files.
- [ ] Route listing succeeds.
- [ ] No public Artisan routes remain.
- [ ] Existing route count changes only by the removed operational routes.
- [ ] Security middleware tests pass.
- [ ] Sensitive-path feature tests pass where applicable.
- [ ] Git diff contains no secret values.
- [ ] Git diff contains no personal data.

### Web server

- [ ] `httpd.exe -t` passes.
- [ ] `httpd.exe -S` shows the intended virtual host.
- [ ] HTTP document root is `public/`.
- [ ] HTTPS document root is `public/` where applicable.
- [ ] Directory listing is disabled.
- [ ] Public upload script execution is blocked.

### Runtime

- [ ] Public pages smoke-test successfully.
- [ ] Admin login renders.
- [ ] Counselling submission still functions.
- [ ] CSS/JS/images/videos load.
- [ ] Sensitive paths return 403 or 404.
- [ ] Debug details do not appear.
- [ ] Session cookie attributes are correct.
- [ ] Security headers are present.
- [ ] CSP is report-only.

### Operational

- [ ] Database credential rotated.
- [ ] Mail credential rotated.
- [ ] Third-party credentials reviewed.
- [ ] Application-key rotation decision recorded.
- [ ] Existing sessions invalidated where required.
- [ ] Access logs reviewed.
- [ ] Privacy escalation decision documented.

## 12. Definition of Done

Sprint 1 is complete only when:

1. Only `public/` is web-accessible.
2. All confirmed sensitive resources return 403 or 404.
3. CVs and certificates are not stored under public storage.
4. Debug mode is disabled in production.
5. Public Artisan routes are removed.
6. HTTP and HTTPS document roots are verified.
7. Public upload directories cannot execute scripts.
8. Baseline security headers are active.
9. CSP is operating in report-only mode.
10. Identified exposed credentials are rotated or have a documented,
    security-approved exception.
11. Public pages, admin login and counselling capture pass smoke tests.
12. No CMS, database redesign, Homepage Builder, CRM, SEO or Analytics work has
    been introduced.
13. `SECURITY_REMEDIATION_REPORT.md` is complete.
14. The final diff is reviewed and approved.

## 13. Approval Gates

### Gate A — Plan approval

Approves:

- Proposed file inventory
- Batch sequence
- Security controls
- Validation and rollback approach

No implementation begins before Gate A.

### Gate B — Server configuration approval

Required before editing:

- XAMPP Apache configuration
- SSL virtual-host configuration
- Local hosts file
- Production/cPanel domain settings

### Gate C — Credential rotation approval

Required before:

- Database credential rotation
- Mail credential rotation
- Third-party token rotation
- Application-key rotation

### Gate D — Sensitive artifact removal approval

Required before deleting or relocating:

- `db_dump.sql`
- CVs
- Certificates
- Public archives
- Unmanaged uploaded HTML

### Gate E — Sprint completion approval

Requires:

- Successful validation matrix
- Security remediation report
- Final diff
- Residual-risk review

## 14. Expected Batch Reporting Format

After each approved implementation batch, report:

```text
Batch:
Status:

Modified files:
- file

Impact:
- security effect
- compatibility effect

Validation:
- command/check and result

Rollback:
1. exact rollback step

Residual risk:
- remaining concern
```

## 15. Approval Requested

Approval of this document authorizes implementation of Batch 1 only.

Server configuration, credential rotation and sensitive-file deletion will
still use their separate approval gates before execution.
