# Bootstrap Administrator Assignment Report

**Project:** MAAC Durgapur  
**Completed:** 19 June 2026  
**Scope:** Explicit bootstrap Super Admin assignment

## 1. Outcome

The existing approved administrator was assigned one active global Super Admin
role.

Final state:

```text
user_roles total: 1
active user_roles: 1
assignments for user 6: 1
assignments for other users: 0
```

The administrator retains the existing MAAC brand membership.

No login, middleware, route, frontend, Settings UI, or `site_info` change was
made.

## 2. Pre-Execution Database Backup

Backup:

```text
C:\Users\HP\AppData\Local\Temp\maacdurgapur-bootstrap-admin-backups\database-before-bootstrap-admin-20260619-120244.sql
```

Verification:

| Check | Result |
|---|---|
| File size | 90,854 bytes |
| Tables included | 38 |
| Dump completion marker | Present |
| SHA-256 | `FFB41941C8153F229F2D0DEBABD29E4F1DB9F9FACEE2DB3E6A960CFD3E0F2E56` |

## 3. Prerequisites Validated

Before assignment:

| Check | Result |
|---|---|
| User ID 6 exists | Passed |
| User is soft-deleted | No |
| Legacy user type | Admin |
| Active MAAC membership | Present |
| MAAC brand active | Yes |
| Super Admin role active | Yes |
| Super Admin scope | Global |
| Super Admin catalogue permissions | 27 |
| Existing `user_roles` | 0 |
| Existing assignments for user 6 | 0 |

The command's validation-only mode completed successfully and created no row.

## 4. File Added

```text
app/Console/Commands/AssignBootstrapAdministrator.php
```

The command:

- Requires explicit user ID, brand code, and reason.
- Validates the user, legacy Admin status, brand, membership, role, scope, and
  permission catalogue.
- Uses a database transaction.
- Creates only the exact global Super Admin assignment.
- Is idempotent.
- Has no automatic execution path.
- Is not called by migrations, seeders, deployment hooks, or application
  startup.

No existing application file was modified for this assignment.

## 5. Command Execution

Validation:

```powershell
php artisan rbac:assign-bootstrap-admin `
  --user-id=6 `
  --brand=maac `
  --reason="Approved bootstrap Super Admin assignment" `
  --validate-only
```

Result:

```text
Bootstrap administrator prerequisites are valid.
```

Assignment:

```powershell
php artisan rbac:assign-bootstrap-admin `
  --user-id=6 `
  --brand=maac `
  --reason="Approved bootstrap Super Admin assignment"
```

First execution:

```text
Bootstrap Super Admin assignment created.
```

Second execution:

```text
Bootstrap Super Admin assignment is already active.
```

No duplicate was created.

## 6. Exact Assignment

| Property | Result |
|---|---|
| User ID | `6` |
| Role | `super_admin` |
| Role scope | `global` |
| `brand_id` | `null` |
| `scope_key` | `global` |
| Status | `active` |
| Assigned by | User `6` |
| Reason | Present |
| Expiry | None |

The Super Admin role was resolved by immutable code, not assumed database ID.

## 7. Brand Membership

The existing MAAC membership remains:

```text
MAAC memberships for user 6: 1
```

No new brand membership was inserted.

No AKSHA or Space-E-Fic membership was granted.

## 8. `user_roles` Counts

| Query | Count |
|---|---:|
| All assignments | 1 |
| Active assignments | 1 |
| Assignments for user 6 | 1 |
| Assignments for other users | 0 |

## 9. Resolver Validation

### User 6 — MAAC brand Settings permission

```text
permission: settings.brand.view
brand: maac
allowed: true
reason: granted
role: super_admin
```

### User 6 — global Settings permission

```text
permission: settings.global.view
allowed: true
reason: granted
role: super_admin
```

### User 6 — AKSHA brand permission

```text
permission: settings.brand.view
brand: aksha
allowed: false
reason: brand_membership_missing
```

This confirms the global role does not bypass the resolver's explicit
brand-membership check for a brand-scoped request.

### Unknown permission

```text
allowed: false
reason: permission_unknown
```

No other user received an assignment or permission.

## 10. Login Validation

The login implementation and authorization criteria remain unchanged:

```text
email + password + user_type=Admin
```

Results:

| Check | Result |
|---|---|
| Admin login page | HTTP 200 |
| Invalid login status | 201 |
| Invalid message unchanged | Passed |
| Valid login status | 200 |
| Valid message unchanged | Passed |
| Dashboard after login | HTTP 200 |

The RBAC resolver is not invoked by login.

## 11. Middleware and Route Validation

Unchanged hashes were verified for:

- `app/Http/Middleware/AdminMiddleware.php`
- `app/Http/Controllers/Admin/Login/AdminLoginController.php`
- `routes/admin.php`

`AdminMiddleware` continues using the existing legacy Admin check.

## 12. Settings and Data Integrity

| Check | Result |
|---|---|
| Active setting definitions | 0 |
| `setting_values` rows | 0 |
| Settings UI enabled | No |
| `site_info` rows | 12 |
| `site_info` checksum | Unchanged |

Checksum:

```text
c47abb52d0b0d35702842ce5d64a29f45121d24bd34514e8aada8b4673ef2558
```

## 13. Application Availability

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

## 14. Rollback Validation

The preferred rollback is assignment revocation.

The following behavior was validated inside a database transaction and then
rolled back:

1. Change assignment status to `revoked`.
2. Set revocation actor, time, and reason.
3. Confirm active assignments become `0`.
4. Confirm resolver denies MAAC Settings access.
5. Roll back the validation transaction.
6. Confirm the active assignment returns to `1`.

Validation result:

```text
Before: active assignment = 1
During revocation: active assignment = 0
Resolver: denied, role_assignment_missing
After transaction rollback: active assignment = 1
```

### Approved rollback procedure

Update the exact assignment:

```text
status = revoked
revoked_by = 6
revoked_at = current timestamp
revocation_reason = approved rollback reason
```

This preserves assignment history and immediately removes permission grants.

### Hard rollback

Deleting the assignment is not recommended. If explicitly approved:

1. Export the assignment row.
2. Delete only its exact UUID.
3. Confirm `user_roles` returns to zero.

## 15. Residual Considerations

1. Settings routes and policies remain unimplemented.
2. RBAC does not yet replace `AdminMiddleware`.
3. Non-Admin RBAC users still cannot authenticate through the legacy login
   condition.
4. Super Admin critical permissions indicate future MFA and
   reauthentication requirements, but these controls are not enforced yet.
5. The one-time command remains available and idempotent; it may be removed
   after operational sign-off to reduce accidental reuse risk.
