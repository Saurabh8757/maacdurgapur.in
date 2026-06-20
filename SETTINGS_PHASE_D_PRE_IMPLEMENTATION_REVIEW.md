# Settings Phase D Pre-Implementation Review

This review validates the internally generated `SETTINGS_PHASE_D_IMPLEMENTATION_PLAN.md` against the existing codebase and database schema constraints.

## 1. Missing Dependencies
*   **File Inventory**: The generated Phase D plan does not list an explicit file inventory. We assume the Phase D boundary inherits the draft-mutation files defined in `S4_SETTINGS_UI_IMPLEMENTATION_PLAN.md` (e.g., `SettingsDraftController`, `UpdateSettingsRequest`, and the `SettingsDraftService`), but drops any files related to versioning or publication.

## 2. Schema Assumptions
*   **Optimistic Concurrency**: The rule "Enforce optimistic concurrency protection" conflicts with the rule "Do not create Setting Value Versions" if we assume traditional version records. However, the `setting_values` table possesses an `updated_at` column. The implementation assumes optimistic locking will be handled by comparing the submitted `updated_at` timestamp with the database row's `updated_at` timestamp in the `WHERE` clause during the `UPDATE` query.
*   **Review-Lock Protection**: The database schema does not have an explicit `is_locked` or `locked_at` column. "Review-lock protection" will be enforced by ensuring mutations only occur when `status === 'draft'`. Any other status implicitly locks the record from draft editing.

## 3. Route Assumptions
*   **Route Count**: The validation requirement expects the route count to become exactly 74. Currently, there are 72 routes (Phase C added 2 GET routes). Phase D will strictly add only the 2 mutation routes specified in the S4 UI Plan:
    *   `PUT /v1/cpanel/admin/settings/{brand}/draft` (Save draft)
    *   `DELETE /v1/cpanel/admin/settings/{brand}/draft/{definition}/override` (Reset override)

## 4. RBAC Assumptions
*   **Permissions**: The existing `SettingsAuthorizationService` accepts string operation codes (e.g., `'edit'`) and resolves them to `settings.brand.edit`, `settings.global.edit`, and `settings.sensitive.edit` via `PermissionResolver`. We assume these permission strings are fully supported by the underlying RBAC foundation (even if not explicitly seeded in the database), and we will authorize mutations exclusively through `SettingsAuthorizationService::authorizeDefinition(..., 'edit')`.

## 5. Potential Conflicts
*   **Phase A Audit Logger**: Phase D must log draft changes directly through `SettingsAuditLogger` without assuming `SettingValueVersion` models exist, as `setting_value_versions` creation is forbidden. The logger's `logDraftUpdated` method must be compatible with logging `SettingValue` changes directly.
*   **Phase C Read-Only UI**: Phase C controllers and views must remain read-only. We will not modify the `SettingsReadService`. The new `SettingsDraftController` will handle mutations in isolation.

## 6. Blockers
*   None identified, provided the plan corrections (below) are approved.

## 7. Required Plan Corrections
*   **Version Creation Override**: The original `S4_SETTINGS_UI_IMPLEMENTATION_PLAN.md` explicitly calls for creating immutable version records on draft saves ("Create version number 1", "Create the next immutable version"). This violates Phase D Rule 6 ("Do not create Setting Value Versions").
    *   *Correction*: The implementation will completely bypass the creation of `setting_value_versions`. It will only perform `UPDATE` operations on the `setting_values` table row and log the action via `SettingsAuditLogger`.
