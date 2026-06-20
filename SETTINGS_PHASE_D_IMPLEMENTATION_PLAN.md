# Settings Phase D Implementation Plan

Rules:

1. Follow the approved file inventory exactly.
2. Do not create any migration.
3. Do not modify any database schema.
4. Do not create any seeder.
5. Do not activate any Setting Definition.
6. Do not create Setting Value Versions.
7. Do not create Publications.
8. Do not modify site_info.
9. Do not modify Brand Context architecture.
10. Do not modify RBAC architecture.

Implementation requirements:

* Implement draft creation and draft editing only.
* Respect SettingsScopeResolver and SettingsAuthorizationService.
* Enforce sensitive-definition permissions.
* Enforce optimistic concurrency protection.
* Enforce review-lock protection.
* Use transactional writes.
* Create audit entries through SettingsAuditLogger only.
* Never mutate published values.
* Mutate only status=draft rows.

Validation requirements:

* Run all Phase D unit tests.
* Run all Phase D feature tests.
* Run complete test suite.
* Verify route count becomes exactly 74.
* Verify migration batch remains 6.
* Verify setting_definitions count unchanged.
* Verify site_info checksum unchanged.
* Verify no setting_value_versions created.
* Verify no settings_publications created.
* Verify no settings_publication_items created.

Deliverables:

1. Full implementation.
2. Validation evidence.
3. Route inventory.
4. Database integrity report.
5. Rollback validation.
6. SETTINGS_PHASE_D_IMPLEMENTATION_REPORT.md

Do not proceed to Phase E.
Do not proceed to publishing workflows.
Do not proceed to Media Manager integration.
Only implement Phase D.
