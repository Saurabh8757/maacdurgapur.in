# Settings Phase D Reconciliation Report

**Prepared:** June 19, 2026
**Purpose:** Reconcile the proposed Phase D plan with the actual codebase and schema state.

## 1. Baseline Verification

### 1.1 Exact Existing Settings Routes
*   `GET /v1/cpanel/admin/settings/brand` (Name: `admin::settings.brand.index`)
*   `GET /v1/cpanel/admin/settings/global` (Name: `admin::settings.global.index`)

### 1.2 Exact Existing Settings Controllers
*   `app/Http/Controllers/Admin/Settings/SettingsController.php`

### 1.3 Exact Existing Settings Services
*   `SettingsAuditLogger.php`
*   `SettingsAuthorizationService.php`
*   `SettingsLocaleResolver.php`
*   `SettingsNavigationService.php`
*   `SettingsReadService.php`
*   `SettingsScope.php`
*   `SettingsScopeResolver.php`
*   `SettingsValuePresenter.php`
*   `Exceptions/SettingsAuthorizationException.php`
*   `Exceptions/SettingsScopeException.php`
*   `Exceptions/UnsupportedSettingsLocaleException.php`

### 1.4 Exact Permission Records
*   Permissions in the database use Title Case names (e.g., `Settings Brand Edit`, `Settings Global Edit`, `Settings Sensitive Edit`) rather than dot-notation keys. The application maps the logical codes to these names dynamically.

### 1.5 Exact `setting_values` Schema
*   Columns: `id`, `setting_definition_id`, `brand_id`, `scope_key`, `locale`, `value` (json), `status` (string, default 'draft'), `published_at`, `published_by`, `created_by`, `updated_by`, `created_at`, `updated_at`.
*   *Observation*: There is no dedicated `lock_version` or `is_locked` column.

### 1.6 Exact `settings_publications` Schema
*   Columns: `id`, `uuid`, `brand_id`, `scope_key`, `locale`, `status` (string, default 'pending'), `change_summary`, `published_by`, `published_at`, `rolled_back_by`, `rolled_back_at`, `created_at`, `updated_at`.

### 1.7 Exact Unique Constraints (Drafts)
*   The `setting_values` table employs the unique index `setting_values_definition_scope_locale_status_unique` on `(setting_definition_id, scope_key, locale, status)`. This guarantees only one draft can exist per definition, scope, and locale.

---

## 2. Reconciled Plan

### 2.1 Correct File Inventory
*   **Controllers**:
    *   `[NEW]` `app/Http/Controllers/Admin/Settings/SettingsDraftController.php`
*   **Requests**:
    *   `[NEW]` `app/Http/Requests/Admin/Settings/UpdateSettingsRequest.php`
    *   `[NEW]` `app/Http/Requests/Admin/Settings/ResetSettingOverrideRequest.php`
*   **Services**:
    *   `[NEW]` `app/Services/Settings/SettingsDraftService.php`
    *   `[NEW]` `app/Services/Settings/SettingValidator.php`
    *   `[NEW]` `app/Services/Settings/SettingValueCaster.php`
*   **Tests**:
    *   `[NEW]` `tests/Feature/Admin/Settings/SettingsDraftTest.php`
    *   `[NEW]` `tests/Feature/Admin/Settings/SettingsValidationTest.php`
    *   `[NEW]` `tests/Unit/Settings/SettingValidatorTest.php`
    *   `[NEW]` `tests/Unit/Settings/SettingValueCasterTest.php`

*(No `setting_value_versions` models or migrations will be created).*

### 2.2 Correct Route Inventory
Instead of the S4 Plan's parameterized `{brand}` routes, Phase D will align with Phase C's session-based brand resolution:
*   `PUT /v1/cpanel/admin/settings/brand/draft` (`admin::settings.brand.draft.update`)
*   `DELETE /v1/cpanel/admin/settings/brand/draft/{definition}/override` (`admin::settings.brand.override.reset`)
*   `PUT /v1/cpanel/admin/settings/global/draft` (`admin::settings.global.draft.update`)

Total new routes: 3. (Adjusting from the 2 initially assumed, as both brand and global editing are required based on S4).

### 2.3 Correct RBAC Mapping
*   Operations will use string codes `'edit'` and `'edit_sensitive'` within `SettingsAuthorizationService`.
*   The service correctly resolves these to `settings.brand.edit`, `settings.global.edit`, and `settings.sensitive.edit`. The underlying `PermissionResolver` maps these logical keys to the exact `"Settings Brand Edit"` records seeded in the database.

### 2.4 Correct Draft Lifecycle
1.  **Creation**: When an admin saves a draft for an unconfigured definition, a new `setting_values` record is created with `status = 'draft'`.
2.  **Updating**: When an admin updates an existing draft, the row is updated using the submitted value.
3.  **Concurrency**: The update uses optimistic concurrency by comparing the submitted `updated_at` against the current database `updated_at`.
4.  **Audit**: `SettingsAuditLogger` is called to record the `setting_value` change.
5.  **Review-Lock**: The mutation is rejected if the target row's `status` is not `'draft'`.

### 2.5 Deviations from the Approved S4 Phase D Plan
*   **No Versions**: The S4 UI plan required creating `setting_value_versions` records. Phase D will strictly avoid this, executing only `UPDATE` statements on the `setting_values` table.
*   **Route Signature**: The S4 UI plan included the `{brand}` slug in the URL (`/settings/{brand}/draft`). The reconciled implementation uses `/settings/brand/draft` to match the `ResolveAdminBrandContext` middleware established in Phase C.
*   **Optimistic Concurrency Mechanism**: Instead of a dedicated `lock_version` column or `setting_value_versions` ID comparison, optimistic locking will be enforced via the `updated_at` timestamp.
