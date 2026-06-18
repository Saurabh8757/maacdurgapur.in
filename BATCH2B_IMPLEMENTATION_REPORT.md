# Batch 2B Implementation Report — Local HTTP Virtual Host

**Project:** MAAC Durgapur  
**Environment:** Local XAMPP on Windows  
**Completed:** 19 June 2026 (Asia/Calcutta)  
**Scope:** Local HTTP virtual host only

## 1. Outcome

Batch 2B is complete.

- `maacdurgapur.local` resolves to `127.0.0.1`.
- Apache serves the Laravel application from `C:/xampp/htdocs/maacdurgapur/public`.
- The homepage and admin login load through the new host.
- Tested local CSS, JavaScript, image, video, admin, and upload assets return HTTP 200.
- Sensitive repository paths are denied or unavailable.
- No HTTPS, production, `.env`, database, media migration, application code, or UI changes were made.

## 2. Pre-change Audits

### 2.1 Asset path audit

The active frontend and admin assets use Laravel-generated URLs or paths compatible with a host-root deployment.

Direct checks confirmed that the following required assets exist under `public/` and are available through the new host:

- Frontend CSS and JavaScript
- Frontend WebP images
- Frontend MP4 video
- Admin JavaScript
- Public service images
- Current default course image
- Current default user profile image

The database was inspected read-only to verify active media references. Current referenced course and user fallback images are present under `public/upload/`.

Several legacy media files remain outside `public/`. No active database reference found during this audit required those files. They were not moved because media migration is outside Batch 2B.

### 2.2 URL generation audit

Request-context URL generation was tested for `http://maacdurgapur.local/`.

| URL type | Generated result |
|---|---|
| Application root | `http://maacdurgapur.local` |
| Frontend asset | `http://maacdurgapur.local/frontend/css/style.css` |
| Homepage route | `http://maacdurgapur.local` |
| Admin login route | `http://maacdurgapur.local/admin-login` |
| Career counselling route | `http://maacdurgapur.local/career-counselling` |

Rendered homepage and admin-login assets also use `http://maacdurgapur.local/...`.

`APP_URL` was not changed. Browser requests generate correct host-root URLs from the active HTTP request. URL generation performed outside an HTTP request, such as some CLI or queued work, may continue to use the existing `APP_URL`; remediation is intentionally outside this batch.

### 2.3 Hardcoded `/maacdurgapur` dependencies

No runtime application, Blade, route, configuration, or public asset code was found with a hardcoded `/maacdurgapur` base-path dependency.

Historical occurrences exist in documentation, repository metadata, and old error-log paths only. They do not control runtime routing.

One root-relative application endpoint was identified:

- `public/frontend/js/chatbot.js:195`
- `POST /career-counselling`

This is compatible with `maacdurgapur.local`, where the application is mounted at `/`. It was not modified.

## 3. Modified Configuration Files

### 3.1 Windows hosts file

**File:** `C:\Windows\System32\drivers\etc\hosts`

Added:

```text
127.0.0.1 maacdurgapur.local
```

### 3.2 Apache virtual-host configuration

**File:** `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

Added an explicit default `localhost` virtual host and the project virtual host:

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

The `localhost` vhost remains first so unmatched local HTTP hostnames continue to use the standard XAMPP document root.

### 3.3 Report

**File:** `C:\xampp\htdocs\maacdurgapur\BATCH2B_IMPLEMENTATION_REPORT.md`

This report is the only project-workspace file added in Batch 2B.

## 4. Backups

Original configuration backups were created before modification:

```text
C:\Users\HP\AppData\Local\Temp\maacdurgapur-batch2b-20260619-003239\hosts
C:\Users\HP\AppData\Local\Temp\maacdurgapur-batch2b-20260619-003239\httpd-vhosts.conf
```

## 5. Apache Validation

### `httpd.exe -t`

```text
Syntax OK
```

### `httpd.exe -S`

```text
*:80 is a NameVirtualHost
    default server localhost
    port 80 namevhost localhost
    port 80 namevhost maacdurgapur.local
```

The existing `*:443` configuration remains unchanged. No HTTPS configuration was performed.

An initially running Apache parent process retained the old configuration and served the XAMPP dashboard. The verified XAMPP Apache processes were cleanly stopped and Apache was restarted with the validated configuration. The active listener then served the project virtual host correctly.

## 6. Virtual Host Mapping

| Request | Resolution | Apache mapping |
|---|---|---|
| `http://maacdurgapur.local/` | `127.0.0.1:80` | `C:/xampp/htdocs/maacdurgapur/public` |
| `http://localhost/` | Local machine | `C:/xampp/htdocs` |

Application request flow:

```text
maacdurgapur.local
  -> Windows hosts: 127.0.0.1
  -> Apache *:80 name-based vhost
  -> project public/ directory
  -> public/.htaccess
  -> public/index.php
  -> Laravel router
```

## 7. Application Validation

### Homepage test

| Test | Result |
|---|---|
| URL | `http://maacdurgapur.local/` |
| HTTP status | 200 |
| Page title | `MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Creative Institute` |
| Main page content | Rendered |
| Generated asset host | `maacdurgapur.local` |

### Admin login test

| Test | Result |
|---|---|
| URL | `http://maacdurgapur.local/admin-login` |
| HTTP status | 200 |
| Page title | `MAAC \| Admin Login` |
| Login form | Rendered |
| Admin CSS/JavaScript URLs | Generated on `maacdurgapur.local` |

No credentials were submitted and no authentication state was changed.

## 8. Asset Verification

| Asset | Status | Content type |
|---|---:|---|
| `/frontend/css/style.css` | 200 | `text/css` |
| `/frontend/js/main.js` | 200 | `text/javascript` |
| `/frontend/images/pg-01.webp` | 200 | `image/webp` |
| `/frontend/vedio/waterfall_desktop.mp4` | 200 | `video/mp4` |
| `/upload/SERVICE/animation2.jpg` | 200 | `image/jpeg` |
| `/upload/images/course/default.png` | 200 | `image/png` |
| `/admin/plugins/jquery/jquery.min.js` | 200 | `text/javascript` |

Browser inspection also confirmed loaded homepage images have valid natural dimensions and the page references the expected local CSS, JavaScript, image, and video URLs.

## 9. Sensitive Path Tests

| Path | Status | Result |
|---|---:|---|
| `/.env` | 403 | Blocked |
| `/db_dump.sql` | 403 | Blocked |
| `/composer.json` | 403 | Blocked |
| `/package.json` | 403 | Blocked |
| `/artisan` | 403 | Blocked |
| `/.git/config` | 403 | Blocked |
| `/routes/web.php` | 404 | Not web-accessible |
| `/storage/logs/laravel.log` | 404 | Not web-accessible |
| `/vendor/autoload.php` | 404 | Not web-accessible |
| `/bootstrap/cache/config.php` | 404 | Not web-accessible |
| `/upload/file/cv/test.pdf` | 404 | No exposed file/path |
| `/upload/file/certi/test.pdf` | 404 | No exposed file/path |
| `/public` | 404 | No nested public-root access |
| `/public/index.php` | 404 | No nested front-controller access |

No CV or certificate document files were present under `public/upload/file` during validation.

## 10. Rollback Instructions

Run the following from an Administrator PowerShell session:

1. Restore the original Apache virtual-host file:

   ```powershell
   Copy-Item -LiteralPath 'C:\Users\HP\AppData\Local\Temp\maacdurgapur-batch2b-20260619-003239\httpd-vhosts.conf' -Destination 'C:\xampp\apache\conf\extra\httpd-vhosts.conf' -Force
   ```

2. Restore the original Windows hosts file:

   ```powershell
   Copy-Item -LiteralPath 'C:\Users\HP\AppData\Local\Temp\maacdurgapur-batch2b-20260619-003239\hosts' -Destination 'C:\Windows\System32\drivers\etc\hosts' -Force
   ```

3. Validate the restored Apache configuration:

   ```powershell
   C:\xampp\apache\bin\httpd.exe -t
   C:\xampp\apache\bin\httpd.exe -S
   ```

4. Restart Apache through the XAMPP Control Panel.

5. Remove this report only if the documentation change must also be rolled back.

## 11. Residual Risks and Deferred Work

1. The local site is HTTP-only. HTTPS was explicitly excluded.
2. Production web-server configuration remains unchanged.
3. `.env` and `APP_URL` remain unchanged. CLI-generated absolute URLs may use the existing configured URL.
4. Legacy media outside `public/` is not available through the new vhost. No active database reference tested in this batch depended on it.
5. The legacy `http://127.0.0.1/maacdurgapur/` compatibility route remains available through the default localhost/XAMPP document root and Batch 1 root containment. Its eventual removal should be handled in an approved later batch.
6. The existing XAMPP development TLS certificate warning on port 443 is unchanged and outside this HTTP-only batch.

## 12. Scope Compliance

- HTTPS configuration: **Not performed**
- Production changes: **Not performed**
- `.env` changes: **Not performed**
- Database changes: **Not performed**
- Media migration: **Not performed**
- Business-logic changes: **Not performed**
- Frontend/UI changes: **Not performed**
- Batch 3 or later work: **Not started**
