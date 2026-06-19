# Sprint S1 Implementation Report

**Project:** MAAC Durgapur  
**Sprint:** S1 — Multi-Brand Foundation  
**Completed:** 19 June 2026  
**Scope:** `brands`, `brand_domains`, `brand_user`, related models, relationships, and seeders

## 1. Outcome

Sprint S1 was implemented successfully.

- Created the multi-brand database foundation.
- Seeded MAAC, AKSHA, and Space-E-Fic.
- Registered `maacdurgapur.local` as the MAAC local domain.
- Assigned existing administrator accounts to MAAC only.
- Added bidirectional Eloquent relationships.
- Confirmed all migrations ran successfully.
- Confirmed seeders are idempotent.
- Confirmed the existing homepage and admin login still load.
- Did not modify `site_info`, frontend, admin UI, routes, controllers, or
  existing business tables.

## 2. Database Backup

A database backup was created before any S1 file or database change.

```text
C:\Users\HP\AppData\Local\Temp\maacdurgapur-s1-backups\database-before-s1-20260619-104341.sql
```

Verification:

| Check | Result |
|---|---|
| File size | 32,847 bytes |
| Tables present | 21 |
| Insert groups present | 8 |
| `users` table included | Yes |
| `site_info` table included | Yes |
| Dump completion marker | Present |
| SHA-256 | `30DB9E344E4FB0B8DB60FCAC49027712155927F62DF1F35E4E0A9915C83F7D4D` |

The backup is outside the repository and was not committed.

## 3. Files Added

### Migrations

- `database/migrations/2026_06_19_100000_create_brands_table.php`
- `database/migrations/2026_06_19_100100_create_brand_domains_table.php`
- `database/migrations/2026_06_19_100200_create_brand_user_table.php`

### Models

- `app/Models/Brand.php`
- `app/Models/BrandDomain.php`
- `app/Models/BrandMembership.php`

### Seeders

- `database/seeders/BrandSeeder.php`
- `database/seeders/BrandDomainSeeder.php`
- `database/seeders/BrandUserSeeder.php`

### Documentation

- `S1_IMPLEMENTATION_REPORT.md`

## 4. Files Modified

- `app/Models/User.php`
  - Added brand, default-brand, membership, and brand-audit relationships.

- `database/seeders/DatabaseSeeder.php`
  - Registered the three new S1 seeders in dependency order.

No pre-existing user changes overlapped these files when implementation began.

## 5. Database Changes

### `brands`

Stores:

- Stable UUID
- Brand code and slug
- Name and legal name
- Default locale
- Timezone
- Status
- Primary-brand flag
- Creation/update actor references
- Soft deletion

Constraints include unique UUID, code, and slug.

### `brand_domains`

Stores:

- Brand relationship
- Unique hostname
- HTTP/HTTPS scheme
- Primary and preview flags
- Canonical redirect behavior
- Status

Brand deletion is restricted while domains remain.

### `brand_user`

Stores:

- User relationship
- Brand relationship
- Default-brand flag

The brand/user pair is unique. Deleting a user or brand removes its membership
rows.

## 6. Seeded Data

### Brands

| Code | Name | Slug | Primary |
|---|---|---|---|
| `maac` | MAAC | `maac` | Yes |
| `aksha` | AKSHA | `aksha` | No |
| `space_e_fic` | Space-E-Fic | `space-e-fic` | No |

### Local domain

| Hostname | Brand | Scheme | Primary |
|---|---|---|---|
| `maacdurgapur.local` | MAAC | HTTP | Yes |

The domain seeder runs only when the Laravel environment is `local`.
Production domains were not created.

### Initial memberships

- Existing users with `user_type = Admin` were assigned to MAAC.
- MAAC was set as their default brand.
- No administrator was automatically assigned to AKSHA or Space-E-Fic.

Current seeded counts:

| Entity | Count |
|---|---:|
| Brands | 3 |
| Domains | 1 |
| Memberships | 1 |

## 7. Migration Results

All three migrations ran in migration batch 2:

```text
2026_06_19_100000_create_brands_table         Ran
2026_06_19_100100_create_brand_domains_table  Ran
2026_06_19_100200_create_brand_user_table     Ran
```

Pre-execution `migrate --pretend` completed successfully and showed the
expected tables, indexes, and foreign keys.

## 8. Code Validation

PHP linting passed for:

- Three migrations
- Three models
- Three seeders
- Updated `User` model
- Updated `DatabaseSeeder`

Result:

```text
No syntax errors detected
```

Git whitespace validation reported no patch errors. Existing line-ending
warnings for the two modified tracked files are non-functional Windows Git
normalization notices.

## 9. Relationship Validation

The following relationships were executed against the migrated database:

### Brand

- `domains()`
- `activeDomains()`
- `users()`
- `memberships()`
- `creator()`
- `updater()`

### BrandDomain

- `brand()`

### BrandMembership

- `brand()`
- `user()`

### User

- `brands()`
- `brandMemberships()`
- `defaultBrand()`
- `createdBrands()`
- `updatedBrands()`

Runtime results:

- MAAC resolved its local domain.
- The domain resolved its MAAC parent.
- MAAC resolved one administrator.
- The administrator resolved MAAC through `brands()`.
- The membership resolved both its user and brand.
- The administrator did not receive AKSHA or Space-E-Fic access.

## 10. Seeder Idempotency

The following seeders were run twice:

```text
BrandSeeder
BrandDomainSeeder
BrandUserSeeder
```

Counts remained:

```text
brands:      3
domains:     1
memberships: 1
```

No duplicate brand, domain, or membership records were created.

The complete `DatabaseSeeder` was not run because existing legacy seeders are
not idempotent.

## 11. Existing Database Validation

The `site_info` table was not modified.

Current validation:

| Check | Result |
|---|---|
| Row count | 12 |
| Columns | `id`, `key`, `is_image`, `value`, `created_at`, `updated_at` |
| Historical migration modified | No |
| Model/controller/view modified | No |

Verified existing business tables remain present:

- `banners`
- `about_pages`
- `our_courses`
- `carrer_counsellings`

No migration altered an existing business table.

## 12. Existing Site Verification

### Application pages

| URL | Status | Result |
|---|---:|---|
| `http://maacdurgapur.local/` | 200 | Homepage rendered with expected title and hero content |
| `http://maacdurgapur.local/admin-login` | 200 | Admin login rendered with login form |

Homepage title:

```text
MAAC Durgapur – West Bengal's #1 Animation, VFX & AI Creative Institute
```

Admin title:

```text
MAAC | Admin Login
```

### Asset checks

| Asset | Status | Content type |
|---|---:|---|
| `/frontend/css/style.css` | 200 | `text/css` |
| `/frontend/js/main.js` | 200 | `text/javascript` |
| `/frontend/images/pg-01.webp` | 200 | `image/webp` |
| `/frontend/vedio/waterfall_desktop.mp4` | 200 | `video/mp4` |

### Route checks

Existing routes remain registered:

- Admin login
- Admin login submission
- Admin dashboard
- Career counselling submission

No route file was modified.

### Automated tests

The existing project test suite passed:

```text
Tests: 2 passed
```

The suite currently contains only the repository's baseline unit assertion and
homepage response feature test; relationship validation was therefore also
performed separately against the migrated database as documented above.

## 13. Scope Compliance

| Area | Result |
|---|---|
| `brands` table | Implemented |
| `brand_domains` table | Implemented |
| `brand_user` table | Implemented |
| Related models | Implemented |
| Related relationships | Implemented |
| Related seeders | Implemented |
| `site_info` | Untouched |
| Frontend | Untouched |
| Admin UI | Untouched |
| Routes/controllers | Untouched |
| Existing business tables | Untouched |
| RBAC implementation | Not started |
| Settings tables | Not started |
| Media Manager | Not started |

## 14. Rollback Instructions

### Preferred application rollback

S1 currently has no effect on public or admin application behavior. If the new
brand foundation must be disabled before later sprints, leave the additive
tables in place and stop further S1-dependent work.

### Migration rollback

Because all three S1 migrations are in batch 2 and no later migrations have
been applied, they may be rolled back with:

```powershell
php artisan migrate:rollback --batch=2
```

This removes, in dependency order:

1. `brand_user`
2. `brand_domains`
3. `brands`

The rollback does not alter `site_info` or existing business tables.

Before executing rollback:

1. Confirm migration batch 2 still contains only the three S1 migrations.
2. Create a fresh database backup.
3. Stop any later feature that depends on brand IDs.

### Full database restoration

Use the pre-S1 dump only if database-level restoration is required:

```text
C:\Users\HP\AppData\Local\Temp\maacdurgapur-s1-backups\database-before-s1-20260619-104341.sql
```

Restoration is destructive and must be performed only after a new backup and
explicit approval.

### Code rollback

Remove the nine S1 implementation files and revert only the S1 additions in:

- `app/Models/User.php`
- `database/seeders/DatabaseSeeder.php`

Do not revert unrelated architecture documents or user-owned changes.

## 15. Residual Considerations

1. Only MAAC has a local domain mapping in S1.
2. Existing administrators have MAAC membership only.
3. The database indexes `is_primary`, but enforcing exactly one primary brand
   remains an application/service rule for a later sprint.
4. Brand membership does not yet grant or deny permissions; RBAC remains a
   separate implementation phase.
5. The legacy `user_type` authorization remains unchanged.
6. `DatabaseSeeder` includes legacy non-idempotent seeders and must not be
   rerun wholesale against shared data.
