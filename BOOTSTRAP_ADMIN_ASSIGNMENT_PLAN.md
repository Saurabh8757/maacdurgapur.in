# Bootstrap Administrator Assignment Plan

**Project:** MAAC Durgapur  
**Status:** Awaiting approval  
**Prepared:** 19 June 2026  
**Scope:** One explicit bootstrap RBAC assignment

## 1. Objective

Safely activate RBAC for one existing administrator while preserving:

- Existing admin login behavior
- Existing `AdminMiddleware` behavior
- Existing MAAC brand membership
- Existing frontend and admin UI
- Dormant Settings UI state

The operation will create exactly one `user_roles` record.

## 2. Existing Administrator Discovery

Read-only database inspection found:

| Property | Result |
|---|---|
| Active legacy Admin users | 1 |
| Approved candidate user ID | `6` |
| Display name | Admin |
| Email | Present; masked during review |
| Soft deleted | No |
| Existing brand memberships | MAAC |
| Existing RBAC assignments | 0 |

The email address and credentials are intentionally not reproduced in this
document.

### MAAC brand

| Property | Result |
|---|---|
| Brand ID | `1` |
| Code | `maac` |
| Status | Active |
| Primary brand | Yes |

### Super Admin role

| Property | Result |
|---|---|
| Role ID | `1` |
| Code | `super_admin` |
| Scope type | Global |
| Status | Active |
| Catalogue permissions | 27 |
| Automatically assignable | No |

`is_assignable = false` is intentional. Super Admin cannot be assigned through
ordinary role-management workflows. This separately approved bootstrap
operation is the controlled exception.

## 3. Assignment Semantics

The administrator will receive:

1. One active global `super_admin` role assignment.
2. Continued membership in the MAAC brand through the existing `brand_user`
   row.

The administrator will not receive:

- A second MAAC membership
- A `brand_admin` assignment
- Any AKSHA membership
- Any Space-E-Fic membership
- Any role inferred from `user_type`
- Any automatic assignment through `DatabaseSeeder`

### Why the Super Admin assignment is global

The approved `super_admin` role has:

```text
scope_type = global
```

Therefore its `user_roles` assignment must use:

```text
brand_id = null
scope_key = global
```

Storing the Super Admin assignment with `brand_id = 1` would violate the RBAC
scope contract.

MAAC context is represented separately by the existing `brand_user`
membership:

```text
user_id = 6
brand_id = 1
is_default = true
```

## 4. Exact File Inventory

### New file

1. `app/Console/Commands/AssignBootstrapAdministrator.php`

Purpose:

- Perform one explicit, validated bootstrap assignment.
- Require the target user ID and brand code as command options.
- Fail closed when prerequisites do not match.
- Avoid automatic execution during deployment or general seeding.

### Report added after implementation

2. `BOOTSTRAP_ADMIN_ASSIGNMENT_REPORT.md`

### Existing files modified

None.

In particular, do not modify:

- `database/seeders/DatabaseSeeder.php`
- `AdminLoginSeeder.php`
- `app/Http/Middleware/AdminMiddleware.php`
- `AdminLoginController.php`
- `routes/admin.php`
- Settings UI files
- Frontend files
- `site_info`

## 5. Proposed One-Time Command

Command signature:

```text
rbac:assign-bootstrap-admin
    --user-id=
    --brand=
    --reason=
```

Approved execution:

```powershell
php artisan rbac:assign-bootstrap-admin `
  --user-id=6 `
  --brand=maac `
  --reason="Approved bootstrap Super Admin assignment"
```

The command is deliberately not called by:

- Migrations
- Seeders
- Composer hooks
- Deployment scripts
- Application startup

## 6. Command Validation Rules

Before inserting any row, the command must verify:

1. Exactly one user exists with ID `6`.
2. The user is not soft deleted.
3. The user's legacy `user_type` is `Admin`.
4. The user has an active MAAC `brand_user` membership.
5. The MAAC brand exists and is active.
6. The `super_admin` role exists.
7. The role is active.
8. The role scope is `global`.
9. The role has the expected permission catalogue.
10. No active or pending Super Admin assignment already exists for the user.
11. No other user has been selected implicitly.
12. A non-empty assignment reason is supplied.

If any check fails:

- Insert nothing.
- Return a non-zero exit status.
- Display a safe diagnostic without credentials.

## 7. Exact Database Change

### Existing row preserved

`brand_user` remains unchanged:

| Column | Value |
|---|---|
| `user_id` | `6` |
| `brand_id` | `1` |
| `is_default` | `true` |

### New `user_roles` row

| Column | Value |
|---|---|
| `uuid` | Newly generated UUID |
| `user_id` | `6` |
| `role_id` | Resolved by `code = super_admin`; currently `1` |
| `brand_id` | `null` |
| `scope_key` | `global` |
| `status` | `active` |
| `starts_at` | Current timestamp |
| `expires_at` | `null` |
| `assigned_by` | `6` |
| `reason` | Approved bootstrap assignment reason |
| `revoked_by` | `null` |
| `revoked_at` | `null` |
| `revocation_reason` | `null` |
| timestamps | Current timestamps |

The implementation must resolve the role by code rather than assume role ID
`1`, even though the current ID is documented above.

## 8. Transaction and Idempotency

The assignment runs inside one database transaction.

The command should:

1. Lock the target user and role rows where appropriate.
2. Re-run all prerequisite checks inside the transaction.
3. Use `firstOrCreate` or equivalent guarded logic for the exact active global
   assignment.
4. Confirm exactly one assignment exists after the operation.
5. Commit.

Idempotent rerun behavior:

- If the exact approved assignment already exists, create no duplicate.
- Report “assignment already active.”
- Return success only after validating that the existing row matches the
  approved scope.

## 9. Permission Resolver Effect

Before assignment:

```text
settings.brand.view
allowed = false
reason = role_assignment_missing
```

After assignment:

- The user has an active global role assignment.
- The user retains active MAAC membership.
- Global Super Admin grants may satisfy brand-scoped permission checks for
  MAAC through the resolver.
- No permission is inferred from `user_type`.

The assignment does not:

- Change login behavior
- Change middleware behavior
- Enable Settings routes
- Add policies
- Display new navigation
- Activate Settings definitions

## 10. Login and Middleware Preservation

### Login

The existing login condition remains:

```text
email + password + user_type=Admin
```

No change will be made to:

- Login route
- Login controller
- JSON response
- Session-hardening behavior
- Login Blade template

### Middleware

`AdminMiddleware` remains unchanged and continues checking:

```text
user_type == Admin
```

The new RBAC assignment remains dormant until separately approved policies or
routes call the permission resolver.

## 11. Validation Plan

### Before assignment

1. Create and verify a database backup.
2. Record current migration batch.
3. Record `user_roles` count; expected `0`.
4. Record `site_info` checksum.
5. Record login-controller and middleware hashes.
6. Confirm user `6` is the sole active legacy Admin.
7. Confirm the user has MAAC membership.
8. Confirm no existing role assignment.

### Command validation

1. PHP-lint the command.
2. Run the command in a non-writing validation mode if implemented.
3. Run the approved assignment once.
4. Run it a second time to prove idempotency.

### Database validation

Expected results:

```text
user_roles total = 1
active Super Admin assignments for user 6 = 1
assignments for every other user = 0
MAAC brand memberships unchanged
AKSHA memberships unchanged
Space-E-Fic memberships unchanged
```

Validate:

- `brand_id` is null on the Super Admin assignment.
- `scope_key` is `global`.
- Role code resolves to `super_admin`.
- Assignment reason exists.
- No duplicate assignment exists.

### Resolver validation

For user `6`:

- Global permission check succeeds where appropriate.
- MAAC brand permission check succeeds.
- Unknown permission remains denied.
- Brand checks still require brand membership.

For all other users:

- No new permission is granted.

### Login validation

Verify:

- Admin login page returns HTTP 200.
- Invalid login response remains unchanged.
- Valid login response remains unchanged.
- Dashboard remains HTTP 200 after valid login.
- Session regeneration remains active.
- Logout remains functional.

### Application validation

- Homepage HTTP 200
- Admin login HTTP 200
- Frontend CSS/JS HTTP 200
- `site_info` checksum unchanged
- Settings definitions remain inactive
- Settings UI remains unavailable

## 12. Risks

### R1 — Global privilege is broader than MAAC membership

**Severity:** High

Super Admin is a global role. Future resolver consumers may permit platform-wide
operations even though the user currently has only MAAC brand membership.

Control:

- This is an explicit consequence of the approved Super Admin assignment.
- Brand-scoped operations still validate membership where designed.
- No UI or route consumes RBAC in this step.

### R2 — Bootstrap command may be rerun

**Severity:** Medium

Control:

- Exact idempotent assignment check
- Unique database constraint
- Explicit command options
- No automatic invocation

### R3 — Wrong user selection

**Severity:** Critical

Control:

- Explicit `--user-id=6`
- Validate active Admin status and MAAC membership
- Never select the first Admin implicitly
- Fail if prerequisites differ

### R4 — Self-attributed assignment

**Severity:** Medium

The bootstrap administrator is necessarily the first assignment actor.

Control:

- Store explicit reason
- Record the operation in the implementation report
- Future assignments must use normal approval/audit workflows

### R5 — `is_assignable=false`

**Severity:** High if bypassed generally

Control:

- Only the narrowly scoped bootstrap command may perform this assignment.
- Future generic role-assignment services must reject non-assignable roles.

## 13. Rollback Plan

### Preferred rollback: revoke, do not delete

Update the exact assignment:

```text
status = revoked
revoked_by = 6
revoked_at = current timestamp
revocation_reason = approved bootstrap rollback reason
```

Advantages:

- Preserves security history
- Resolver immediately stops granting permissions
- Does not alter role catalogue or brand membership

### Emergency hard rollback

If the operation must leave the database exactly as before and no audit
architecture relies on the row:

1. Export the assignment row.
2. Delete only the exact `user_roles` row by UUID.
3. Verify total `user_roles` count returns to `0`.

Hard deletion requires explicit approval.

### Code rollback

Remove:

```text
app/Console/Commands/AssignBootstrapAdministrator.php
```

The command file may also remain after assignment if access is operationally
restricted, but removing it reduces accidental reuse risk.

No rollback is required for:

- Login flow
- `AdminMiddleware`
- Routes
- Settings UI
- Frontend
- Database schema
- Brand membership
- Role/permission catalogue

### Full database recovery

Use the pre-assignment backup only for database-level recovery. Full restore is
destructive and requires a fresh backup and explicit approval.

## 14. Definition of Done

The bootstrap assignment is complete when:

1. Exactly one approved user has one active Super Admin assignment.
2. The assignment belongs to user ID `6`.
3. The assignment is global and has no `brand_id`.
4. The user retains MAAC membership.
5. No other user receives a role.
6. The resolver grants the expected approved permissions only to user `6`.
7. Login and `AdminMiddleware` remain unchanged.
8. Settings UI remains inactive.
9. Frontend remains unchanged.
10. Rollback has been validated and documented.

## 15. Approval Gate

Approval is required for:

1. User ID `6` as the bootstrap administrator
2. Global Super Admin scope
3. Existing MAAC membership as the brand context
4. The one-time command approach
5. Self-attribution through `assigned_by = 6`
6. Preferred revoke-based rollback

No assignment or file change should be made until this plan is approved.
