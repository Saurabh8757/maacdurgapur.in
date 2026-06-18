# Batch 1 Security Implementation Report

Implementation date: June 19, 2026  
Scope: Sprint 1, Batch 1 only  
Status: Completed

## 1. Scope Implemented

Batch 1 implemented only:

- Removal of public Artisan routes
- Root repository-route containment
- Laravel public-directory rewrite configuration
- Public upload execution and file-type protection
- Preservation of the current legacy subdirectory application URL

The following were not changed:

- `.env`
- Apache virtual hosts
- Apache document roots
- Database or migrations
- Credentials
- CMS
- Admin business logic
- Frontend UI
- Homepage Builder
- CRM
- SEO
- Analytics
- Security-header middleware

## 2. Modified Files

### `.htaccess`

Changes:

- Disabled directory listings.
- Denied sensitive root files, dotfiles, manifests, database dumps, logs, and
  backup artifacts.
- Blocked direct requests to framework source, dependencies, storage, and
  development directories.
- Blocked direct access to private applicant files under `upload/file/`.
- Blocked browser-visible `/public/...` legacy paths.
- Preserved bearer authorization headers.
- Preserved Laravel front-controller routing through the root compatibility
  `index.php`.
- Added internal mappings for approved public asset namespaces:
  - `frontend/`
  - `admin/`
  - `image/`
  - favicon and robots file
  - approved `upload/SERVICE/` and `upload/images/` assets
- Blocked direct browser requests to the root compatibility `index.php`.

### `routes/web.php`

Removed:

- Duplicate `/clear-cache` definitions
- `/config-cache`
- All associated public `Artisan::call()` execution

All business and public website routes were preserved.

### `public/.htaccess`

Added:

- Canonical Laravel front-controller rewrites
- Authorization-header preservation
- Directory-listing protection
- Sensitive-file defense-in-depth
- Blocking of browser-visible legacy `/public/...` paths

### `public/upload/.htaccess`

Added:

- Directory-listing protection
- CGI and server-side-include protection
- Removal of inherited executable handlers
- Denial of executable, configuration, archive, database, backup, and unmanaged
  HTML file types

## 3. Backups

Original Batch 1 backups:

```text
C:\Users\HP\AppData\Local\Temp\maacdurgapur-sprint1-batch1-20260618-235623
```

Backup of the superseded unconditional-404 root `.htaccess`:

```text
C:\Users\HP\AppData\Local\Temp\maacdurgapur-root-htaccess-revision-20260619-001752
```

The first backup directory contains the pre-Batch-1 root `.htaccess` and
`routes/web.php`. Marker files record that the two public `.htaccess` files did
not previously exist.

## 4. Git Diff Summary

Tracked-file summary:

```text
.htaccess      | 49 lines changed
routes/web.php | 17 lines removed
```

New files:

```text
public/.htaccess
public/upload/.htaccess
BATCH1_IMPLEMENTATION_REPORT.md
```

Pre-existing user changes were not modified:

- `public/frontend/css/style.css`
- `public/frontend/vedio/waterfall_desktop.mp4`
- `resources/views/frontend/pages/index.blade.php`

## 5. Apache Validation

Commands:

```powershell
C:\xampp\apache\bin\httpd.exe -t
C:\xampp\apache\bin\httpd.exe -S
```

Result:

```text
Syntax OK
```

Observed configuration remains unchanged:

- Main document root: `C:/xampp/htdocs`
- Existing default TLS virtual host: `www.example.com`
- No project virtual host was added or modified.

## 6. Route Verification

Commands:

```powershell
php -l routes\web.php
php artisan route:list --json
```

Results:

- PHP syntax: Passed
- Total routes: 69
- `/clear-cache`: Absent
- `/config-cache`: Absent
- Homepage route: Present
- Admin login route: Present

The route count decreased from 71 to 69 because two unique operational URIs
were removed; `/clear-cache` had previously been declared twice.

## 7. Application Availability Verification

| Request | Result | Content type |
|---|---:|---|
| `/maacdurgapur/` | 200 | `text/html` |
| `/maacdurgapur/admin-login` | 200 | `text/html` |
| `/maacdurgapur/maac` | 200 | `text/html` |

The revised root strategy restores the current subdirectory application while
retaining sensitive-path containment.

## 8. Asset Verification

| Asset | Result | Content type |
|---|---:|---|
| `frontend/css/style.css` | 200 | `text/css` |
| `frontend/js/main.js` | 200 | `text/javascript` |
| `frontend/images/pg-01.webp` | 200 | `image/webp` |
| `upload/SERVICE/animation2.jpg` | 200 | `image/jpeg` |
| `upload/images/course/default.png` | 200 | `image/png` |
| `frontend/vedio/waterfall_desktop.mp4` | 200 | `video/mp4` |

The mappings are internal rewrites. The browser-visible URL does not expose a
`/public` segment.

## 9. Containment Verification

| Sensitive request | Result |
|---|---:|
| `.env` | 403 |
| `db_dump.sql` | 403 |
| `composer.json` | 403 |
| `package.json` | 403 |
| `storage/logs/laravel.log` | 403 |
| `routes/web.php` | 404 |
| `vendor/autoload.php` | 404 |
| Uploaded CV path | 404 |
| `/public/` | 404 |
| `/public/index.php` | 404 |
| `/clear-cache` | 404 |
| `/config-cache` | 404 |
| Public upload archive | 404 |
| Unmanaged uploaded HTML | 403 |

No tested sensitive resource returned HTTP 200.

## 10. Security Impact

Batch 1 now:

- Prevents direct download of confirmed sensitive repository files.
- Prevents access to Laravel source and runtime directories.
- Prevents public delivery of applicant CV files.
- Removes unauthenticated Artisan command execution.
- Prevents `public/` from being used as a second visible application route.
- Prevents executable and unmanaged high-risk files in the public upload tree.
- Preserves the existing homepage, admin login, application routing, and public
  assets until Batch 2 can establish the definitive public document root.

## 11. Rollback Instructions

### Restore the pre-Batch-1 state

```powershell
Copy-Item -LiteralPath `
  'C:\Users\HP\AppData\Local\Temp\maacdurgapur-sprint1-batch1-20260618-235623\root.htaccess' `
  -Destination 'C:\xampp\htdocs\maacdurgapur\.htaccess' -Force

Copy-Item -LiteralPath `
  'C:\Users\HP\AppData\Local\Temp\maacdurgapur-sprint1-batch1-20260618-235623\routes\web.php' `
  -Destination 'C:\xampp\htdocs\maacdurgapur\routes\web.php' -Force

Remove-Item -LiteralPath `
  'C:\xampp\htdocs\maacdurgapur\public\.htaccess'

Remove-Item -LiteralPath `
  'C:\xampp\htdocs\maacdurgapur\public\upload\.htaccess'
```

Then validate:

```powershell
C:\xampp\apache\bin\httpd.exe -t
php -l routes\web.php
php artisan route:list
```

Security warning:

Full rollback restores the original critical exposure and public Artisan
routes. It must not be performed unless an alternative secure document-root
boundary is already active.

### Restore only the prior root `.htaccess` revision

```powershell
Copy-Item -LiteralPath `
  'C:\Users\HP\AppData\Local\Temp\maacdurgapur-root-htaccess-revision-20260619-001752\root.htaccess' `
  -Destination 'C:\xampp\htdocs\maacdurgapur\.htaccess' -Force
```

This restores the superseded unconditional-404 version and will make the
current application URL unavailable. It is retained only as change evidence,
not as a recommended rollback target.

## 12. Residual Risks

Batch 1 is transitional containment. Remaining risks include:

1. Apache still serves the global `C:/xampp/htdocs` document root.
2. The project does not yet have a dedicated virtual host targeting `public/`.
3. Production `.env` still requires hardening; debug remains outside Batch 1.
4. Exposed credentials have not been rotated.
5. Private documents remain in the repository tree, though direct tested HTTP
   access is blocked.
6. The SQL dump remains present and tracked.
7. Root `.htaccess` relies on a denylist and compatibility rewrites until the
   definitive Batch 2 document-root change.
8. New sensitive directories or files not matching the deny rules could become
   exposed while the repository remains under the global document root.
9. Public-upload protection is extension-based and does not replace secure
   server-side upload validation.
10. Security headers have not been implemented.
11. `APP_ENV` and `APP_DEBUG` were not modified.
12. The production cPanel PHP handler must be reviewed before deploying this
    root `.htaccess`, because the previous root file contained a host-generated
    PHP handler directive.

## 13. Conclusion

Batch 1 meets its immediate route and file-containment objectives while
preserving the current application and asset URLs.

Batch 2 has not begun. Apache virtual hosts, production document roots, `.env`,
credentials, database state, and later sprint modules remain unchanged.
