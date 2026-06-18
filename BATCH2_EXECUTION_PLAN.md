# Batch 2 Execution Plan — Apache Document Root

Status: Awaiting approval  
Prepared: June 19, 2026  
Scope: Apache virtual hosts and document-root cutover only  
Implementation state: No Batch 2 changes applied

## 1. Objective

Replace Batch 1's transitional repository-root compatibility routing with the
correct Laravel deployment boundary:

```text
DocumentRoot → C:/xampp/htdocs/maacdurgapur/public
```

The same principle must be applied to production:

```text
Production domain document root → <application-path>/public
```

After Batch 2:

- Apache serves only files beneath `public/`.
- Laravel requests enter through `public/index.php`.
- Repository source, `.env`, storage, dependencies, private documents and SQL
  dumps are outside the web namespace by construction.
- Browser-visible URLs do not contain `/public`.
- The transitional root `.htaccess` is no longer part of normal request
  processing.

## 2. Scope

### Included

- Local XAMPP Apache virtual-host configuration
- Local HTTP document-root configuration
- Local HTTPS configuration if HTTPS is used for this project
- Local hostname mapping if required
- Production Apache/cPanel document-root configuration
- Production HTTP and HTTPS alignment
- Apache configuration validation
- Application and asset smoke testing
- Sensitive-path containment verification
- Cutover and rollback runbooks

### Excluded

- `.env` hardening
- Credential rotation
- Database changes
- PHP or Laravel business logic
- CMS
- Admin-panel redesign
- Frontend redesign
- Media migration
- Security headers
- RBAC
- CRM
- SEO
- Analytics
- Dependency upgrades

No repository file will be modified unless a compatibility issue is discovered
that cannot be resolved in Apache configuration. Such a discovery will stop the
batch and require separate approval.

## 3. Current State

### Local Apache

Current main configuration:

```text
Main DocumentRoot: C:/xampp/htdocs
AllowOverride: All
Options: Indexes FollowSymLinks Includes ExecCGI
Virtual hosts file: included
Project-specific virtual host: absent
```

Current TLS configuration uses a default virtual host with:

```text
DocumentRoot: C:/xampp/htdocs
ServerName: www.example.com:443
```

### Application

The application currently runs through:

```text
http://127.0.0.1/maacdurgapur/
→ repository-root .htaccess
→ root index.php compatibility shim
→ public/index.php
→ Laravel
```

Batch 1 protects sensitive paths through root deny rules and compatibility asset
rewrites. This preserves availability but remains a denylist-based transitional
design.

### Target local flow

```text
http://maacdurgapur.local/
→ dedicated Apache virtual host
→ C:/xampp/htdocs/maacdurgapur/public
→ public/.htaccess
→ public/index.php
→ Laravel
```

## 4. Proposed Local Hostname

Recommended:

```text
maacdurgapur.local
```

Optional TLS hostname:

```text
maacdurgapur.local
```

The final hostname must be approved before implementation.

The existing URL:

```text
http://127.0.0.1/maacdurgapur/
```

should either:

1. Return 404 through the existing root containment, or
2. Redirect to the approved local hostname through a server-level rule.

Recommendation: retain 404 locally during the first validation window. A
redirect can be introduced later only if required.

## 5. Exact Configuration Files Likely to Change

These files are outside the repository and require elevated approval.

### 5.1 `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

Reason:

- The file is already included by `httpd.conf`.
- It contains no active project virtual host.

Proposed change:

```apache
<VirtualHost *:80>
    ServerName maacdurgapur.local
    DocumentRoot "C:/xampp/htdocs/maacdurgapur/public"

    <Directory "C:/xampp/htdocs/maacdurgapur/public">
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/maacdurgapur-error.log"
    CustomLog "logs/maacdurgapur-access.log" combined
</VirtualHost>
```

Security effect:

- Makes `public/` the only project directory Apache exposes for this hostname.
- Removes dependency on root `.htaccess` for normal requests.
- Disables directory listing.

### 5.2 `C:\Windows\System32\drivers\etc\hosts`

Reason:

- The local hostname must resolve to XAMPP.

Proposed entry:

```text
127.0.0.1 maacdurgapur.local
```

This file requires administrator privileges.

### 5.3 `C:\xampp\apache\conf\extra\httpd-ssl.conf`

Conditional.

Reason:

- The current default TLS virtual host points to `C:/xampp/htdocs`.
- If the project is tested locally over HTTPS, HTTP and HTTPS must use the same
  public boundary.

Recommended approach:

- Do not repurpose the existing default `www.example.com` TLS host.
- Add a dedicated `*:443` project virtual host only after a valid local
  certificate is available.

Proposed conceptual configuration:

```apache
<VirtualHost *:443>
    ServerName maacdurgapur.local
    DocumentRoot "C:/xampp/htdocs/maacdurgapur/public"

    <Directory "C:/xampp/htdocs/maacdurgapur/public">
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on
    SSLCertificateFile "<approved-certificate-path>"
    SSLCertificateKeyFile "<approved-key-path>"

    ErrorLog "logs/maacdurgapur-ssl-error.log"
    CustomLog "logs/maacdurgapur-ssl-access.log" combined
</VirtualHost>
```

No certificate will be generated or installed without separate approval.

### 5.4 Production domain configuration

Exact file depends on the hosting platform.

For cPanel:

- Change the domain/subdomain document root to the deployed `public/`
  directory.
- Do not edit generated Apache files directly.
- Use cPanel domain configuration or the hosting provider's approved mechanism.

For managed Apache:

- Modify the domain's HTTP and HTTPS virtual-host definitions.
- Set both document roots to `<deployment-path>/public`.
- Add an explicit `<Directory>` block.

For Nginx, if production differs from the current Apache evidence:

- Set `root` to `<deployment-path>/public`.
- Route non-files to `/index.php`.
- Deny hidden files.

Production platform and absolute deployment path must be confirmed before
implementation.

## 6. Repository Files Expected to Remain Unchanged

Batch 2 should not modify:

- `.htaccess`
- `public/.htaccess`
- `public/upload/.htaccess`
- `public/index.php`
- Root `index.php`
- `.env`
- Routes
- Controllers
- Views
- Assets
- Database files

The existing `public/.htaccess` already provides the Laravel public rewrite
rules required by the target document root.

The root `.htaccess` remains as defense-in-depth for requests reaching the
global XAMPP document root through `/maacdurgapur`.

## 7. Request Flow Before and After

### 7.1 Homepage

Before:

```text
GET http://127.0.0.1/maacdurgapur/
→ global C:/xampp/htdocs document root
→ project root .htaccess
→ root index.php
→ public/index.php
→ Laravel
```

After:

```text
GET http://maacdurgapur.local/
→ project virtual host
→ C:/xampp/htdocs/maacdurgapur/public
→ public/.htaccess
→ public/index.php
→ Laravel
```

### 7.2 CSS asset

Before:

```text
GET /maacdurgapur/frontend/css/style.css
→ root .htaccess compatibility rewrite
→ public/frontend/css/style.css
```

After:

```text
GET /frontend/css/style.css
→ public document root
→ public/frontend/css/style.css
```

### 7.3 Sensitive root file

Before:

```text
GET /maacdurgapur/.env
→ root .htaccess denial
→ HTTP 403
```

After:

```text
GET /.env
→ filesystem lookup occurs only under public/
→ file does not exist
→ Laravel/Apache 404
```

The actual application `.env` is outside the virtual-host document root.

### 7.4 Storage log

After:

```text
GET /storage/logs/laravel.log
→ resolves under public/storage, not application storage/
→ no matching public file
→ HTTP 404
```

### 7.5 Private CV

After:

```text
GET /upload/file/cv/example.pdf
→ resolves under public/upload/file
→ private root upload/file is outside document root
→ HTTP 404
```

### 7.6 Explicit `/public` URL

After:

```text
GET /public/index.php
→ public/.htaccess legacy-public guard
→ HTTP 404
```

## 8. Execution Batches

## Batch 2A — Baseline and Backups

### Tasks

1. Confirm local hostname.
2. Confirm whether local HTTPS is required.
3. Confirm production hosting platform and absolute deployment path.
4. Record current:
   - `httpd.exe -t`
   - `httpd.exe -S`
   - Active HTTP/TLS document roots
   - Existing application and sensitive-path results
5. Back up:
   - `httpd-vhosts.conf`
   - `httpd-ssl.conf` if in scope
   - hosts file if in scope
   - Production domain configuration through the hosting platform

### Validation gate

- Backups are readable.
- Current Apache syntax passes.
- Production rollback mechanism is confirmed.

### Rollback

No active configuration changes occur in this batch.

## Batch 2B — Local HTTP Virtual Host

### Tasks

1. Add `maacdurgapur.local` to the hosts file.
2. Add the dedicated HTTP virtual host.
3. Ensure the directory block grants access only to `public/`.
4. Keep directory listing disabled.
5. Configure project-specific logs.
6. Validate Apache syntax.
7. Restart Apache.
8. Test the new hostname.

### Validation gate

- `httpd.exe -t` reports `Syntax OK`.
- `httpd.exe -S` lists the project virtual host.
- Homepage returns 200.
- Admin login returns 200.
- Assets use the new hostname and return correct MIME types.
- Sensitive paths return 403 or 404.

### Rollback

1. Restore `httpd-vhosts.conf`.
2. Remove the hosts-file entry.
3. Validate syntax.
4. Restart Apache.
5. Confirm the Batch 1 compatibility URL still works.

## Batch 2C — Local HTTPS, Conditional

### Tasks

1. Confirm approved local certificate and key.
2. Add a dedicated TLS virtual host.
3. Set the TLS document root to `public/`.
4. Validate syntax and certificate loading.
5. Restart Apache.
6. Test HTTPS without bypassing certificate warnings in automated validation.

### Validation gate

- TLS virtual host appears in `httpd.exe -S`.
- Certificate matches the local hostname.
- HTTPS homepage and assets load.
- Sensitive paths fail closed.

### Rollback

1. Restore `httpd-ssl.conf`.
2. Validate syntax.
3. Restart Apache.
4. Retain the working HTTP virtual host.

## Batch 2D — Production Document Root

### Tasks

1. Take verified backups.
2. Confirm maintenance/cutover window.
3. Set production HTTP and HTTPS document roots to `<application>/public`.
4. Confirm directory overrides permit `public/.htaccess`, or reproduce its
   rules in server configuration.
5. Verify PHP handler/FPM configuration for `public/index.php`.
6. Validate server configuration before reload.
7. Reload Apache through the approved hosting mechanism.
8. Run production smoke and containment tests.
9. Monitor access and error logs.

### Validation gate

- Both HTTP and HTTPS use the public document root.
- HTTPS is canonical.
- Homepage, admin login and required assets work.
- Sensitive resources return 403 or 404.
- Laravel writes to storage and cache successfully.
- No PHP source is returned as text.

### Rollback

1. Restore the prior production document-root setting.
2. Reload through the hosting platform.
3. Confirm Batch 1 containment remains deployed.
4. Restore service through the prior URL only if it remains securely contained.
5. Document and investigate the failure before retrying.

## Batch 2E — Stabilization

### Tasks

1. Observe logs after local and production cutover.
2. Confirm generated URLs do not contain `/public`.
3. Confirm no asset uses the old `/maacdurgapur` prefix in production.
4. Verify form POST and CSRF behavior.
5. Confirm admin session behavior.
6. Update the Batch 2 implementation report.

### Validation gate

- No critical errors during the stabilization window.
- No sensitive-path success responses.
- No unexpected redirect loops.
- No broken production assets attributable to the cutover.

## 9. Local XAMPP Validation Plan

### Apache

```powershell
C:\xampp\apache\bin\httpd.exe -t
C:\xampp\apache\bin\httpd.exe -S
```

Expected:

```text
Syntax OK
*:80 maacdurgapur.local
DocumentRoot C:/xampp/htdocs/maacdurgapur/public
```

### Application pages

Expected HTTP 200:

```text
http://maacdurgapur.local/
http://maacdurgapur.local/admin-login
http://maacdurgapur.local/maac
http://maacdurgapur.local/aksha
http://maacdurgapur.local/space-e-fic
http://maacdurgapur.local/faq
http://maacdurgapur.local/blog
```

Known pre-existing broken pages remain outside Batch 2.

### Assets

Expected HTTP 200 and correct MIME types:

```text
/frontend/css/style.css
/frontend/js/main.js
/frontend/images/pg-01.webp
/frontend/vedio/waterfall_desktop.mp4
/upload/SERVICE/animation2.jpg
/upload/images/course/default.png
/admin/dist/css/adminlte.min.css
/admin/plugins/jquery/jquery.min.js
```

### Sensitive paths

Expected 403 or 404:

```text
/.env
/db_dump.sql
/composer.json
/package.json
/artisan
/routes/web.php
/storage/logs/laravel.log
/vendor/autoload.php
/upload/file/cv/<test-file>
/upload/file/certi/<test-file>
/public/
/public/index.php
```

### Functional checks

- Homepage renders with styling.
- Admin login renders with styling.
- Navigation routes work.
- Counselling form receives a CSRF token.
- A controlled non-mutating validation request reaches Laravel.
- Uploaded public images render.
- Video range/streaming requests work.

No real lead submission will be performed unless separately authorized because
it changes database state.

## 10. Production Plan

## 10.1 Required information before implementation

- Production domain names
- Whether each brand already has an independent domain
- Hosting platform
- Absolute application path
- Current domain document root
- HTTP and HTTPS virtual-host ownership
- PHP execution model:
  - LSAPI
  - PHP-FPM
  - mod_php
- Whether `.htaccess` overrides are enabled
- Deployment/reload mechanism
- Maintenance window
- Rollback owner
- Backup location

## 10.2 cPanel procedure

Preferred:

1. Confirm domain is configured to permit document-root changes.
2. Point domain to:
   ```text
   <application-path>/public
   ```
3. Confirm AutoSSL/TLS remains active.
4. Confirm PHP 8.1 handler applies to the public directory.
5. Do not add a PHP handler to the repository root.
6. Verify `public/.htaccess` is processed.
7. Clear hosting cache only through approved controls.
8. Run smoke and containment checks.

If cPanel does not allow the primary domain document root to be changed:

- Relocate the private Laravel application above `public_html`, and
- Place only approved public files plus a correctly adjusted `index.php` under
  `public_html`.

That alternative changes deployment topology and requires a separate detailed
plan before execution.

## 10.3 Managed Apache procedure

Configure both ports:

```apache
<VirtualHost *:80>
    ServerName example.com
    Redirect permanent / https://example.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName example.com
    DocumentRoot "/path/to/application/public"

    <Directory "/path/to/application/public">
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Existing approved TLS and PHP configuration remains here.
</VirtualHost>
```

The exact production configuration must preserve existing certificate, PHP,
proxy and logging directives.

## 11. Production Cutover Sequence

1. Announce the approved change window.
2. Confirm backup status.
3. Capture baseline health and sensitive-path results.
4. Apply document-root change.
5. Validate configuration.
6. Reload, not forcibly terminate, Apache where possible.
7. Verify homepage and admin login.
8. Verify public assets.
9. Verify sensitive paths.
10. Verify Laravel storage/cache write permissions.
11. Monitor logs for at least the agreed stabilization period.
12. Close or roll back the change window.

## 12. Risks

| Risk | Impact | Mitigation |
|---|---|---|
| Incorrect virtual-host order | Wrong site served | Validate `httpd -S` |
| Assets retain `/maacdurgapur` prefix | Broken styling/media | Test generated asset URLs |
| Production `APP_URL` still points elsewhere | Incorrect links/canonicals | Record as Batch 3 dependency; do not change here |
| Public images exist only in root `upload/images` | Missing media | Batch 1 currently maps them; public-root cutover may expose only duplicates already under `public/upload` |
| Root PHP handler removed from request path | PHP may not execute | Verify public PHP handler/FPM configuration |
| `.htaccess` overrides disabled | Laravel routes return 404 | Add equivalent virtual-host rewrite or enable FileInfo override |
| HTTPS still uses old root | Security bypass | Verify both HTTP and HTTPS |
| Local hostname conflicts | Wrong service | Use approved unique hostname |
| Redirect loops | Availability loss | Do not add redirects until direct host works |
| Storage permissions fail | 500 errors | Verify writable runtime directories |
| cPanel regenerates Apache config | Change lost | Use supported cPanel mechanism |

## 13. Media Compatibility Risk

The largest known functional risk is legacy media ownership.

Current storage includes:

- Root `upload/images/`
- Public `public/upload/images/`
- Root private `upload/file/`

After direct `public/` document-root cutover:

- `public/upload/images/` remains accessible.
- Root `upload/images/` becomes inaccessible.
- Root `upload/file/` correctly remains inaccessible.

Before production cutover, compare every database-referenced public image with
the contents of `public/upload/images/`.

Batch 2 will not copy or modify media because that belongs to the separately
approved upload-separation batch. If required public images are missing,
production cutover stops and requests approval for the necessary media action.

## 14. Rollback Plan

### Local

1. Restore backed-up `httpd-vhosts.conf`.
2. Restore `httpd-ssl.conf` if changed.
3. Remove the hosts-file entry if added.
4. Run:
   ```powershell
   C:\xampp\apache\bin\httpd.exe -t
   ```
5. Restart Apache.
6. Confirm:
   ```text
   http://127.0.0.1/maacdurgapur/
   ```
   works through Batch 1 compatibility routing.

### Production

1. Restore the previous domain document root through the hosting platform.
2. Restore previous managed Apache configuration if applicable.
3. Validate configuration.
4. Reload Apache.
5. Confirm service through the previous route.
6. Keep Batch 1 containment files active.
7. Record failed checks and affected requests.

Rollback must never restore unrestricted access to `.env`, storage, SQL dumps
or private documents.

## 15. Acceptance Criteria

Batch 2 is complete only when:

1. Local project virtual host resolves correctly.
2. Local `DocumentRoot` is the project's `public/` directory.
3. Production HTTP and HTTPS document roots use `public/`.
4. Apache syntax validation passes.
5. Apache virtual-host mapping is verified.
6. Homepage and admin login return HTTP 200.
7. CSS, JavaScript, images and videos return correct MIME types.
8. Browser-visible URLs contain no `/public`.
9. Sensitive repository paths return 403 or 404.
10. Private CV and certificate files remain inaccessible.
11. Laravel routing works without the root front-controller shim.
12. Required public images remain available.
13. Storage and cache remain writable by PHP.
14. No repository, `.env`, database or business-logic changes were made.
15. Rollback was documented and configuration backups were verified.
16. A Batch 2 implementation report is approved.

## 16. Approval Gates

### Gate B1 — Local XAMPP

Approval authorizes:

- Backing up and modifying `httpd-vhosts.conf`
- Adding the approved hosts-file entry
- Restarting local Apache

### Gate B2 — Local HTTPS

Separate approval authorizes:

- Certificate setup
- `httpd-ssl.conf` changes
- TLS-host restart and validation

### Gate B3 — Production

Separate approval requires:

- Confirmed platform and paths
- Backup evidence
- Maintenance window
- Rollback owner
- Permission to update domain/virtual-host settings

Approval of this document alone does not authorize any server modification.

## 17. Expected Implementation Reporting

After each approved Batch 2 sub-batch:

```text
Sub-batch:
Configuration modified:
Backup location:
Apache syntax:
Virtual-host mapping:
Application checks:
Asset checks:
Containment checks:
Impact:
Rollback:
Residual risks:
```

## 18. Approval Requested

Approve Gate B1 separately to begin the local XAMPP HTTP virtual-host cutover.

Local HTTPS and production changes remain independently gated.
