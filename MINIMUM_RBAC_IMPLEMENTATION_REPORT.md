# Minimum RBAC Foundation Implementation Report

**Project:** MAAC Durgapur  
**Completed:** 19 June 2026  
**Scope:** RBAC schema, catalogue, relationships, and read-only permission resolver  
**Migration batch:** 5

## 1. Outcome

The minimum RBAC foundation was implemented successfully.

Implemented:

- `roles`
- `permissions`
- `role_permissions`
- `user_roles`
- Related Eloquent models and relationships
- Idempotent role, permission, and role-permission seeders
- Deny-by-default permission resolver
- Immutable permission decision object

Critical safety result:

```text
user_roles row count: 0
```

No user was assigned a role, no existing Admin was inferred to be Super Admin,
and no user gained permission through the new resolver.

## 2. Pre-Implementation Database Backup

Backup:

```text
C:\Users\HP\AppData\Local\Temp\maacdurgapur-rbac-backups\database-before-minimum-rbac-20260619-113951.sql
```

Verification:

| Check | Result |
|---|---|
| File size | 73,534 bytes |
| Tables included | 34 |
| Dump completion marker | Present |
| SHA-256 | `B854D1D626B4A522ABF8D34AA5314C61F8F8EA7AF25D9113438E39F2ABE48FFF` |

## 3. Pre-Implementation State

Before RBAC:

```text
Maximum migration batch: 4
RBAC tables present: No
RBAC user assignments: 0
site_info rows: 12
```

Admin login baseline:

| Check | Result |
|---|---|
| Login page | HTTP 200 |
| Invalid login status | 201 |
| Invalid message | `Invalid Email Or Password` |

## 4. Files Added

### Migrations

- `database/migrations/2026_06_19_130000_create_roles_table.php`
- `database/migrations/2026_06_19_130100_create_permissions_table.php`
- `database/migrations/2026_06_19_130200_create_role_permissions_table.php`
- `database/migrations/2026_06_19_130300_create_user_roles_table.php`

### Models

- `app/Models/Role.php`
- `app/Models/Permission.php`
- `app/Models/RolePermission.php`
- `app/Models/UserRole.php`

### Authorization services

- `app/Services/Authorization/PermissionDecision.php`
- `app/Services/Authorization/PermissionResolver.php`

### Seeders

- `database/seeders/RoleSeeder.php`
- `database/seeders/PermissionSeeder.php`
- `database/seeders/RolePermissionSeeder.php`

### Documentation

- `MINIMUM_RBAC_IMPLEMENTATION_REPORT.md`

## 5. Files Modified

- `app/Models/User.php`
  - Added role-assignment and role relationships.

- `app/Models/Brand.php`
  - Added brand-scoped user-role assignment relationship.

- `database/seeders/DatabaseSeeder.php`
  - Registered the three RBAC catalogue seeders.

## 6. Explicitly Unchanged

- `app/Http/Middleware/AdminMiddleware.php`
- `app/Http/Controllers/Admin/Login/AdminLoginController.php`
- `routes/admin.php`
- Settings UI
- Frontend
- Login authorization criteria
- Existing `brand_user` memberships
- Existing `site_info`

Pre- and post-implementation hashes remained identical for:

```text
AdminMiddleware.php
AdminLoginController.php
```

No RBAC policy, middleware, route, or UI consumes the resolver yet.

## 7. Migration Results

```text
2026_06_19_130000_create_roles_table             [5] Ran
2026_06_19_130100_create_permissions_table       [5] Ran
2026_06_19_130200_create_role_permissions_table  [5] Ran
2026_06_19_130300_create_user_roles_table        [5] Ran
```

All four migrations are isolated in batch 5.

PHP linting and `migrate --pretend` passed before execution.

## 8. Table Counts

| Table | Rows |
|---|---:|
| `roles` | 11 |
| `permissions` | 27 |
| `role_permissions` | 89 |
| `user_roles` | 0 |

Active user assignments:

```text
0
```

## 9. Seeded Roles

| Role | Scope | Catalogue grants |
|---|---|---:|
| Super Admin | Global | 27 |
| Brand Admin | Brand | 17 |
| Content Manager | Brand | 6 |
| Content Editor | Brand | 5 |
| Media Manager | Brand | 10 |
| Reviewer | Brand | 4 |
| Analyst | Brand | 3 |
| Auditor | Global | 5 |
| Student Counsellor | Brand | 1 |
| Placement Coordinator | Brand | 4 |
| Marketing Manager | Brand | 7 |

Scope totals:

| Scope | Roles |
|---|---:|
| Brand | 9 |
| Global | 2 |

No role was assigned to a user.

## 10. Seeded Permissions

| Domain | Permissions |
|---|---:|
| Brand | 2 |
| Settings | 12 |
| Media | 13 |

Total:

```text
27
```

Critical-risk permissions are marked as requiring future MFA and fresh
authentication. Those requirements are exposed by the resolver decision but
are not enforced by login or middleware in this sprint.

## 11. Seeder Idempotency

The following seeders were executed twice:

```text
RoleSeeder
PermissionSeeder
RolePermissionSeeder
```

Counts remained:

```text
roles:            11
permissions:      27
role_permissions: 89
user_roles:        0
```

No duplicate catalogue records or user assignments were created.

The complete `DatabaseSeeder` was not executed because legacy seeders remain
non-idempotent.

## 12. Relationship Validation

Validated:

### `Role`

- `parent()`
- `children()`
- `permissions()`
- `permissionGrants()`
- `userAssignments()`
- `users()`
- Creator/updater relationships

### `Permission`

- `roles()`
- `roleGrants()`

### `RolePermission`

- `role()`
- `permission()`
- `grantingUser()`

### `UserRole`

- `user()`
- `role()`
- `brand()`
- `assigner()`
- `revoker()`

### Existing models

- User role and assignment relationships
- Brand user-role assignments

Runtime results:

- Super Admin resolves 27 catalogue permissions.
- `settings.brand.view` is mapped to seven catalogue roles.
- All role/user/brand assignment relationships return zero rows.

## 13. Permission Resolver Validation

The resolver:

- Denies by default.
- Reads only active permission and role-assignment records.
- Validates brand membership.
- Supports global and brand scope.
- Supports parent-role traversal with cycle protection.
- Uses request-local memoization only.
- Does not inspect `user_type`.
- Does not infer Super Admin.
- Does not modify sessions or assignments.

The existing Admin user was checked for:

```text
settings.brand.view
```

Result:

```text
allowed: false
reason: role_assignment_missing
```

All current users were evaluated:

```text
Allowed users: 0
```

This confirms no user gained new permission.

No temporary or transactional `user_roles` record was created during resolver
validation.

## 14. Admin Login Validation

Existing authorization behavior remains:

```text
email + password + user_type=Admin
```

Post-RBAC validation:

| Check | Result |
|---|---|
| Login page | HTTP 200 |
| Invalid login status | 201 |
| Invalid message unchanged | Passed |
| Valid login status | 200 |
| Valid message unchanged | Passed |
| Authenticated dashboard | HTTP 200 |

The new resolver does not participate in login or `AdminMiddleware`.

## 15. Application and Test Validation

| Resource | Result |
|---|---|
| Homepage | HTTP 200 |
| Admin login | HTTP 200 |
| Frontend CSS | HTTP 200 |
| Frontend JavaScript | HTTP 200 |

Existing automated tests:

```text
Tests: 2 passed
```

PHP syntax and Git whitespace validation passed.

## 16. Existing Data Integrity

```text
site_info rows: 12
site_info SHA-256:
c47abb52d0b0d35702842ce5d64a29f45121d24bd34514e8aada8b4673ef2558

setting_values rows: 0
media_assets rows: 0
user_roles rows: 0
```

`site_info` matches the pre-RBAC checksum.

## 17. Rollback Validation

Supported Laravel 9 command:

```powershell
php artisan migrate:rollback --step=4
```

The rollback was previewed—not executed—with:

```powershell
php artisan migrate:rollback --step=4 --pretend --no-ansi
```

Confirmed order:

1. Drop `user_roles`
2. Drop `role_permissions`
3. Drop `permissions`
4. Drop `roles`

Before executing rollback:

1. Confirm batch 5 is still the latest migration batch.
2. Confirm its latest four migrations are the RBAC migrations.
3. Create a fresh database backup.
4. Confirm no route, policy, or UI consumes RBAC.
5. Confirm `user_roles` remains empty or export assignments.

Rollback does not affect:

- Login flow
- `AdminMiddleware`
- Brand membership
- Settings schema
- Media schema
- `site_info`
- Frontend
- Admin UI

## 18. Residual Requirements

Before S4 Settings routes can be enabled:

1. Review and approve a bootstrap administrator assignment.
2. Create that assignment through a separately approved operation.
3. Add Settings policies and brand-context middleware.
4. Decide how future non-`Admin` RBAC users authenticate.
5. Enforce MFA/reauthentication requirements for critical permissions.

Until then, the RBAC foundation is deliberately dormant.
