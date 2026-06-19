# Settings Module Implementation Plan

**Project:** MAAC Durgapur Multi-Brand CMS  
**Priority:** 1 — Settings Module  
**Status:** Awaiting approval  
**Prepared:** 19 June 2026  
**Implementation authorization:** Not granted

## 1. Objective

Implement a typed, validated, versioned, multi-brand Settings Module for:

- MAAC
- AKSHA
- Space-E-Fic

The module will replace the current flat `site_info` editing experience while
preserving existing website behavior during rollout.

The implementation must provide:

- Global defaults and brand overrides
- Settings grouped by administrative purpose
- Draft, review, publish, and rollback behavior
- Brand and locale-aware value resolution
- RBAC-enforced administration
- Media Manager references for media-valued settings
- Version history and publication history
- Safe caching and targeted invalidation
- Compatibility with future Homepage Builder and CMS modules

## 2. Current-State Constraints

The existing implementation consists of:

- Table: `site_info`
- Model: `app/Models/SiteInformationModel.php`
- Controller:
  `app/Http/Controllers/Admin/SiteInformation/SiteInformationController.php`
- Helper: `app/Helper/admin/siteInformation.php`
- Views:
  - `resources/views/admin/pages/site_info/index.blade.php`
  - `resources/views/admin/pages/site_info/add.blade.php`
- Routes in `routes/admin.php`

Current limitations:

- No unique constraint on `site_info.key`
- No brand scope
- No locale scope
- No types beyond text/image
- No draft/publish workflow
- No version history
- No RBAC permission checks
- Direct upload to a public path
- Stored absolute image URLs
- Repeated queries from the frontend helper
- No reliable rollback

The new module will be additive. The existing `site_info` table and reads remain
in place until the migration and cutover gates pass.

## 3. Scope

### Included

- Brand foundation required by Settings
- Settings database schema
- Settings catalogue seeding
- Models and relationships
- Form Request validation
- Settings resolver and publication services
- Admin controllers and routes
- Brand-aware admin UI
- RBAC policies and permissions
- Media Manager attachment contract
- Legacy settings import
- Shadow reads and controlled cutover
- Tests, observability, and rollback controls

### Excluded

- Media Manager upload implementation
- Homepage Builder
- Menu Builder
- CRM
- SEO Manager
- Analytics implementation
- Animation Manager
- Production storage migration
- Removal of `site_info`
- Removal of the legacy helper during the first release
- Arbitrary `.env` or infrastructure-secret management

## 4. Implementation Strategy

Use a staged, additive rollout:

```text
Prerequisites
  -> New schema
  -> Catalogue and models
  -> Resolver and workflows
  -> RBAC
  -> Admin UI
  -> Legacy import
  -> Shadow comparison
  -> Controlled read cutover
  -> Stabilization
```

No public read path changes until:

- Existing values are imported
- Resolver tests pass
- Brand fallback behavior is verified
- Published settings match legacy output
- Rollback has been tested

## 5. Database Tables

### 5.1 `brands`

Required before settings values can be brand-scoped.

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `uuid` | UUID, unique |
| `code` | String(50), unique |
| `name` | String(150) |
| `legal_name` | String(200), nullable |
| `slug` | String(150), unique |
| `default_locale` | String(10), default `en` |
| `timezone` | String(64), default `Asia/Calcutta` |
| `status` | String(30), indexed |
| `is_primary` | Boolean, default false |
| `created_by` | Nullable user foreign key |
| `updated_by` | Nullable user foreign key |
| timestamps | Created/updated |
| `deleted_at` | Nullable soft-delete timestamp |

Initial records:

- `maac`
- `aksha`
- `space_e_fic`

### 5.2 `brand_domains`

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `brand_id` | Foreign key to `brands` |
| `hostname` | String(255), unique |
| `scheme` | String(10) |
| `is_primary` | Boolean |
| `is_preview` | Boolean |
| `redirect_to_primary` | Boolean |
| `status` | String(30), indexed |
| timestamps | Created/updated |

The local `maacdurgapur.local` mapping is seeded only for local development or
configured through environment-specific data setup. Production hostnames must
not be hardcoded into a general-purpose migration.

### 5.3 `brand_user`

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `brand_id` | Foreign key to `brands` |
| `user_id` | Foreign key to `users` |
| `is_default` | Boolean |
| timestamps | Created/updated |

Constraints:

- Unique `brand_id`, `user_id`
- Index `user_id`, `is_default`

### 5.4 `setting_groups`

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `parent_id` | Nullable self foreign key |
| `code` | String(100), unique |
| `name` | String(150) |
| `description` | Text, nullable |
| `icon` | String(100), nullable |
| `sort_order` | Unsigned integer, default 0 |
| `status` | String(30), indexed |
| timestamps | Created/updated |

Initial groups:

- General
- Brand Identity
- Theme
- Typography
- Header
- Footer
- Contact
- Social Media
- Forms
- Loader
- Global Visual Settings
- Legal
- Integrations

### 5.5 `setting_definitions`

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `setting_group_id` | Foreign key to `setting_groups` |
| `key` | String(190), unique |
| `name` | String(190) |
| `description` | Text, nullable |
| `data_type` | String(50), indexed |
| `input_type` | String(50) |
| `default_value` | JSON, nullable |
| `validation_rules` | JSON, nullable |
| `options` | JSON, nullable |
| `is_required` | Boolean |
| `is_translatable` | Boolean |
| `is_brand_overridable` | Boolean |
| `is_sensitive` | Boolean |
| `is_public` | Boolean |
| `requires_publish` | Boolean |
| `sort_order` | Unsigned integer |
| `status` | String(30), indexed |
| timestamps | Created/updated |

Definitions are system-managed catalogue records. Normal administrators cannot
create arbitrary setting keys.

### 5.6 `setting_values`

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `setting_definition_id` | Foreign key |
| `brand_id` | Nullable foreign key |
| `scope_key` | String(64), normalized global/brand scope |
| `locale` | String(10), default normalized locale |
| `value` | JSON, nullable |
| `status` | String(30), indexed |
| `published_at` | Nullable timestamp |
| `published_by` | Nullable user foreign key |
| `created_by` | Nullable user foreign key |
| `updated_by` | Nullable user foreign key |
| timestamps | Created/updated |

Normalized uniqueness:

```text
setting_definition_id + scope_key + locale + status
```

The implementation must not depend on MySQL treating nullable fields as unique.

Suggested `scope_key` values:

- `global`
- `brand:{brand_uuid}`

Allowed statuses:

- `draft`
- `published`
- `archived`

### 5.7 `setting_value_versions`

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `setting_value_id` | Foreign key |
| `version_number` | Unsigned integer |
| `value` | JSON, nullable |
| `status` | String(30) |
| `change_summary` | String(500), nullable |
| `created_by` | Nullable user foreign key |
| `created_at` | Timestamp |

Constraints:

- Unique `setting_value_id`, `version_number`
- Versions are immutable

### 5.8 `settings_publications`

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `uuid` | UUID, unique |
| `brand_id` | Nullable brand foreign key |
| `scope_key` | String(64), indexed |
| `locale` | String(10) |
| `status` | String(30), indexed |
| `change_summary` | Text, nullable |
| `approval_request_id` | Nullable future approval foreign key |
| `published_by` | Nullable user foreign key |
| `published_at` | Nullable timestamp |
| `rolled_back_by` | Nullable user foreign key |
| `rolled_back_at` | Nullable timestamp |
| timestamps | Created/updated |

Statuses:

- `pending`
- `published`
- `failed`
- `rolled_back`

### 5.9 `settings_publication_items`

Columns:

| Column | Definition |
|---|---|
| `id` | Unsigned bigint primary key |
| `settings_publication_id` | Foreign key |
| `setting_value_id` | Foreign key |
| `setting_value_version_id` | Foreign key |
| `previous_version_id` | Nullable version foreign key |
| `created_at` | Timestamp |

Constraints:

- Unique publication/value pair

### 5.10 Media dependency

Settings with `data_type = media` or `media_collection` will use the approved
Media Manager tables:

- `media_assets`
- `media_attachments`

The Settings implementation must not recreate media records or store public
paths in `setting_values`.

If Media Manager is not yet implemented, media-valued setting definitions are
seeded but marked unavailable in the editor until the required media tables and
picker exist. Existing logo/favicon values continue through the legacy
compatibility path during this interval.

## 6. Migration Files

Exact planned migration files, using the actual generation timestamp at
implementation time while preserving this order:

1. `database/migrations/<timestamp>_create_brands_table.php`
2. `database/migrations/<timestamp>_create_brand_domains_table.php`
3. `database/migrations/<timestamp>_create_brand_user_table.php`
4. `database/migrations/<timestamp>_create_setting_groups_table.php`
5. `database/migrations/<timestamp>_create_setting_definitions_table.php`
6. `database/migrations/<timestamp>_create_setting_values_table.php`
7. `database/migrations/<timestamp>_create_setting_value_versions_table.php`
8. `database/migrations/<timestamp>_create_settings_publications_table.php`
9. `database/migrations/<timestamp>_create_settings_publication_items_table.php`

### Migration rules

- Do not modify the historical `2021_06_14_084524_create_site_info_table.php`.
- Do not drop or rename `site_info`.
- Use foreign keys and indexes explicitly.
- Use restrictive delete behavior for definitions and values.
- Do not cascade-delete publication/version history.
- Rollback order must reverse dependencies safely.
- Do not seed business data from migration `up()` methods.
- Test migrations against a copy of the current schema before shared use.

### Seeders

Planned files:

1. `database/seeders/BrandSeeder.php`
2. `database/seeders/SettingGroupSeeder.php`
3. `database/seeders/SettingDefinitionSeeder.php`
4. `database/seeders/SettingsPermissionSeeder.php`

Update:

- `database/seeders/DatabaseSeeder.php`

Seeders must be idempotent using stable codes/keys. They must not reset
administrator passwords or overwrite edited setting values.

## 7. Initial Setting Definition Catalogue

### General

- `general.site_name`
- `general.short_name`
- `general.tagline`
- `general.copyright_text`
- `general.default_locale`
- `general.timezone`

### Brand Identity

- `brand.primary_logo`
- `brand.secondary_logo`
- `brand.compact_logo`
- `brand.logo_light`
- `brand.logo_dark`
- `brand.favicon`
- `brand.social_share_image`

### Contact

- `contact.primary_email`
- `contact.primary_phone`
- `contact.whatsapp_number`
- `contact.address`
- `contact.map_url`

### Theme

- `theme.primary_color`
- `theme.secondary_color`
- `theme.accent_color`
- `theme.background_color`
- `theme.surface_color`
- `theme.text_color`
- `theme.muted_text_color`
- `theme.link_color`
- `theme.button_style`
- `theme.border_radius`
- `theme.shadow_preset`

### Typography

- `typography.heading_font`
- `typography.body_font`
- `typography.accent_font`
- `typography.base_font_size`
- `typography.heading_scale`
- `typography.line_height`

### Header

- `header.sticky_enabled`
- `header.primary_cta_label`
- `header.primary_cta_url`
- `header.announcement_enabled`
- `header.announcement_text`

### Footer

- `footer.summary`
- `footer.newsletter_enabled`
- `footer.legal_text`
- `footer.background_media`

### Loader

- `loader.enabled`
- `loader.type`
- `loader.media`
- `loader.minimum_duration_ms`
- `loader.maximum_duration_ms`
- `loader.background_color`
- `loader.reduced_motion_behavior`

### Global Visual Settings

- `visual.reduced_motion_fallback`
- `visual.default_section_spacing`
- `visual.image_treatment`
- `visual.video_autoplay_policy`
- `visual.parallax_intensity`
- `visual.animations_enabled`
- `visual.threejs_enabled`
- `visual.mobile_effects_mode`

### Forms

- `forms.default_success_message`
- `forms.consent_text`
- `forms.privacy_policy_url`
- `forms.notification_email`

### Social

- `social.facebook_url`
- `social.instagram_url`
- `social.youtube_url`
- `social.linkedin_url`
- `social.whatsapp_url`

### Legal

- `legal.privacy_summary`
- `legal.cookie_notice`
- `legal.terms_url`
- `legal.data_consent_text`
- `legal.media_copyright_notice`

## 8. Models

### New model files

1. `app/Models/Brand.php`
2. `app/Models/BrandDomain.php`
3. `app/Models/SettingGroup.php`
4. `app/Models/SettingDefinition.php`
5. `app/Models/SettingValue.php`
6. `app/Models/SettingValueVersion.php`
7. `app/Models/SettingsPublication.php`
8. `app/Models/SettingsPublicationItem.php`

### Existing model update

- `app/Models/User.php`

Add relationships:

- `brands()`
- `roleAssignments()` when RBAC foundation is implemented
- Setting authorship/publication relationships only if required by use cases;
  avoid loading them by default

### Required relationships

#### `Brand`

- Has many domains
- Belongs to many users through `brand_user`
- Has many setting values
- Has many publications

#### `SettingGroup`

- Belongs to optional parent
- Has many child groups
- Has many definitions

#### `SettingDefinition`

- Belongs to group
- Has many values
- Scopes for active/public/type/group

#### `SettingValue`

- Belongs to definition
- Belongs to optional brand
- Belongs to creator/updater/publisher
- Has many versions
- Has media attachments through the approved polymorphic relation

#### `SettingsPublication`

- Belongs to optional brand
- Belongs to publisher/rollback actor
- Has many publication items

### Casts

Use model casts for:

- JSON values
- Validation rules
- Options
- Booleans
- Publication timestamps
- Soft deletion where specified

Do not expose sensitive values through default serialization.

## 9. Application Services

The Settings Module should not place resolution and publication logic directly
inside controllers.

### New service files

1. `app/Services/Settings/SettingValueCaster.php`
2. `app/Services/Settings/SettingValidator.php`
3. `app/Services/Settings/SettingsResolver.php`
4. `app/Services/Settings/SettingsDraftService.php`
5. `app/Services/Settings/SettingsPublicationService.php`
6. `app/Services/Settings/SettingsRollbackService.php`
7. `app/Services/Settings/SettingsCacheService.php`
8. `app/Services/Settings/LegacySettingsImporter.php`
9. `app/Services/Settings/LegacySettingsComparator.php`

### Responsibilities

#### `SettingValueCaster`

- Convert request values to canonical typed JSON
- Convert stored values to expected runtime PHP values
- Reject unsupported type conversions

#### `SettingValidator`

- Build validation from trusted definitions
- Apply type-specific and definition-specific rules
- Validate brand override and translation eligibility
- Validate sensitive-setting permissions
- Validate Media Manager references

#### `SettingsResolver`

Resolution order:

1. Brand and requested locale
2. Brand and brand default locale
3. Global and requested locale
4. Global and default locale
5. Definition default

It returns:

- Effective value
- Source scope
- Source locale
- Definition metadata when requested

Public resolution excludes:

- Non-public definitions
- Draft values
- Sensitive definitions
- Ineligible media

#### `SettingsDraftService`

- Save draft changes transactionally
- Create immutable version snapshots
- Remove/reset brand overrides safely
- Record actor and change summary

#### `SettingsPublicationService`

- Lock affected records
- Revalidate draft values and media
- Create publication and publication items
- Archive replaced published values
- Promote approved values
- Commit atomically
- Invalidate cache after commit

#### `SettingsRollbackService`

- Validate historical versions
- Create new draft/version from selected publication
- Require reason
- Publish only through normal authorization/approval
- Preserve all historical records

#### `SettingsCacheService`

Cache key structure:

```text
settings:{scope_key}:{locale}:{publication_uuid}
```

Do not invalidate all application cache entries.

#### Legacy services

- Import known `site_info` keys into global or MAAC defaults
- Normalize stored absolute URLs and path separators only in imported values
- Produce a comparison report without changing public reads
- Be rerunnable without duplicating values

## 10. Controllers

### New controller files

1. `app/Http/Controllers/Admin/Settings/SettingsController.php`
2. `app/Http/Controllers/Admin/Settings/SettingsDraftController.php`
3. `app/Http/Controllers/Admin/Settings/SettingsPublicationController.php`
4. `app/Http/Controllers/Admin/Settings/SettingsHistoryController.php`
5. `app/Http/Controllers/Admin/Settings/SettingsPreviewController.php`
6. `app/Http/Controllers/Admin/Settings/BrandContextController.php`

### Controller actions

#### `SettingsController`

- `index(Brand $brand)`
- `group(Brand $brand, SettingGroup $group)`
- `show(Brand $brand, SettingDefinition $definition)`

Responsibilities:

- Read-only orchestration
- Return grouped definitions and effective values
- Never implement publication logic

#### `SettingsDraftController`

- `update(UpdateSettingsRequest $request, Brand $brand)`
- `resetOverride(ResetSettingOverrideRequest $request, Brand $brand, SettingDefinition $definition)`
- `discard(DiscardSettingsDraftRequest $request, Brand $brand)`

#### `SettingsPublicationController`

- `store(PublishSettingsRequest $request, Brand $brand)`
- `schedule()` only in a later approved extension
- `rollback(RollbackSettingsRequest $request, Brand $brand, SettingsPublication $publication)`

#### `SettingsHistoryController`

- `index(Brand $brand)`
- `publication(Brand $brand, SettingsPublication $publication)`
- `compare(CompareSettingsVersionsRequest $request, Brand $brand)`

#### `SettingsPreviewController`

- `show(PreviewSettingsRequest $request, Brand $brand)`
- Preview must be authenticated, expiring, and isolated from public cache

#### `BrandContextController`

- `switch(SwitchBrandRequest $request)`
- Confirms active brand membership before changing session context

### Existing controller treatment

`app/Http/Controllers/Admin/SiteInformation/SiteInformationController.php`
remains unchanged during initial implementation.

After cutover:

- Its routes become read-only compatibility redirects or are disabled.
- It is not deleted in the first release.

## 11. Form Request Validation

### New request files

1. `app/Http/Requests/Admin/Settings/UpdateSettingsRequest.php`
2. `app/Http/Requests/Admin/Settings/ResetSettingOverrideRequest.php`
3. `app/Http/Requests/Admin/Settings/DiscardSettingsDraftRequest.php`
4. `app/Http/Requests/Admin/Settings/PublishSettingsRequest.php`
5. `app/Http/Requests/Admin/Settings/RollbackSettingsRequest.php`
6. `app/Http/Requests/Admin/Settings/CompareSettingsVersionsRequest.php`
7. `app/Http/Requests/Admin/Settings/PreviewSettingsRequest.php`
8. `app/Http/Requests/Admin/Settings/SwitchBrandRequest.php`

### Common validation

- Brand must be active.
- User must have active brand membership.
- Locale must be supported by the brand.
- Definition must be active.
- Definition must belong to the requested group.
- Brand override is rejected when `is_brand_overridable = false`.
- Unknown definition keys are rejected.
- Unknown fields within structured values are rejected.
- Draft revision token must match to prevent lost updates.
- Change summary is required for publication and rollback.

### Type-specific rules

#### String

- Required according to definition
- Maximum length from definition, default 255
- Strip invalid control characters

#### Text

- Maximum length from definition
- Normalize line endings

#### Rich text

- Sanitize through an approved HTML allowlist
- Reject scripts, event handlers, iframes, embedded objects, and unsafe URLs
- Store sanitized canonical content

#### Integer / decimal

- Numeric type enforcement
- Definition min/max
- Decimal precision limit

#### Boolean

- Strict boolean normalization

#### Color

- Approved hex format or theme token only
- No CSS expressions or arbitrary style strings

#### URL

- `http`/`https` by default
- `tel`, `mailto`, or WhatsApp behavior only for definitions permitting them
- Reject `javascript:`, `data:`, and protocol-relative URLs
- Internal route settings should use route identifiers where designed

#### Email

- Valid email format
- Maximum 254 characters

#### Phone

- Normalized business phone format
- Store display value and/or normalized value according to definition

#### Enum

- Value must exist in trusted definition options

#### Font

- Value must exist in the approved font catalogue
- No arbitrary remote stylesheet or script URL

#### JSON/repeater

- Validate against the definition's trusted nested schema
- Enforce item count
- Reject undeclared keys

#### Media

- Asset exists
- Asset status is `ready`
- Asset is public where the setting is public
- Asset belongs to the same brand or approved shared scope
- MIME/media type meets the definition contract
- Required dimensions and alt text are present

### Publication validation

- All required definitions resolve to a value.
- No invalid draft values remain.
- Referenced media remains eligible.
- Sensitive values are not included in the public bundle.
- Loader minimum is not greater than maximum.
- Loader maximum cannot indefinitely block access.
- Theme tokens meet defined format and contrast warnings are acknowledged.
- Approval evidence is present when policy requires it.

## 12. RBAC Integration

The Settings Module depends on the approved RBAC implementation.

### Required permission codes

- `settings.brand.view`
- `settings.brand.edit`
- `settings.brand.submit`
- `settings.brand.approve`
- `settings.brand.publish`
- `settings.brand.rollback`
- `settings.global.view`
- `settings.global.edit`
- `settings.global.publish`
- `settings.definitions.manage`
- `settings.sensitive.view`
- `settings.sensitive.edit`
- `brands.view`
- `brands.access_global_scope`

### New policy files

1. `app/Policies/BrandPolicy.php`
2. `app/Policies/SettingDefinitionPolicy.php`
3. `app/Policies/SettingValuePolicy.php`
4. `app/Policies/SettingsPublicationPolicy.php`

Update:

- `app/Providers/AuthServiceProvider.php`

### Policy checks

- Brand membership
- Permission code
- Global versus brand scope
- Definition sensitivity
- Definition category, such as legal/theme
- Draft ownership where relevant
- Approval separation
- Publication/rollback risk

### Temporary compatibility

If the full RBAC sprint has not been implemented before Settings begins:

1. Implement the minimum approved RBAC foundation first:
   roles, permissions, role assignments, brand memberships, permission
   middleware/policies, and required settings permissions.
2. Do not implement Settings with hardcoded `user_type == Admin` authorization.
3. Do not expose Settings routes until policies are active.

## 13. Middleware and Brand Context

### New middleware

- `app/Http/Middleware/ResolveAdminBrand.php`

Register alias in:

- `app/Http/Kernel.php`

Responsibilities:

- Read the selected brand from a trusted route/session context
- Confirm the user is assigned to the brand
- Reject inactive brands
- Share the selected brand with controllers/views
- Never silently switch to the primary brand after an authorization failure

Suggested middleware alias:

```text
admin.brand
```

The existing `AdminMiddleware` remains until the wider RBAC replacement is
approved and implemented.

## 14. Routes

Update:

- `routes/admin.php`

Planned route group:

```text
Prefix: /v1/cpanel/admin/settings
Name: admin::settings.
Middleware: web, AdminMiddleware, revalidate, admin.brand
```

### Planned routes

| Method | URI | Route name | Controller action |
|---|---|---|---|
| GET | `/settings/{brand}` | `admin::settings.index` | `SettingsController@index` |
| GET | `/settings/{brand}/group/{group}` | `admin::settings.group` | `SettingsController@group` |
| GET | `/settings/{brand}/definition/{definition}` | `admin::settings.show` | `SettingsController@show` |
| PUT | `/settings/{brand}/draft` | `admin::settings.draft.update` | `SettingsDraftController@update` |
| DELETE | `/settings/{brand}/draft/{definition}/override` | `admin::settings.override.reset` | `SettingsDraftController@resetOverride` |
| DELETE | `/settings/{brand}/draft` | `admin::settings.draft.discard` | `SettingsDraftController@discard` |
| POST | `/settings/{brand}/publish` | `admin::settings.publish` | `SettingsPublicationController@store` |
| POST | `/settings/{brand}/preview` | `admin::settings.preview` | `SettingsPreviewController@show` |
| GET | `/settings/{brand}/history` | `admin::settings.history.index` | `SettingsHistoryController@index` |
| GET | `/settings/{brand}/history/{publication}` | `admin::settings.history.show` | `SettingsHistoryController@publication` |
| POST | `/settings/{brand}/compare` | `admin::settings.history.compare` | `SettingsHistoryController@compare` |
| POST | `/settings/{brand}/rollback/{publication}` | `admin::settings.rollback` | `SettingsPublicationController@rollback` |
| POST | `/admin-context/brand` | `admin::brand-context.switch` | `BrandContextController@switch` |

Rules:

- All mutations use POST/PUT/DELETE with CSRF.
- No settings mutation uses GET.
- Use scoped route model binding so group, definition, and publication belong
  to the expected brand/scope.
- Apply per-action policy middleware or controller authorization.

### Legacy routes

Initially retain:

- `admin::information`
- `admin::information_add`
- `admin::information_save`
- `admin::information_edit`

During shadow mode, remove the legacy page from normal navigation but retain a
restricted compatibility path. Disable legacy writes only after cutover.

## 15. Admin UI Pages

### New view files

1. `resources/views/admin/pages/settings/index.blade.php`
2. `resources/views/admin/pages/settings/group.blade.php`
3. `resources/views/admin/pages/settings/partials/field.blade.php`
4. `resources/views/admin/pages/settings/partials/inheritance.blade.php`
5. `resources/views/admin/pages/settings/partials/media_field.blade.php`
6. `resources/views/admin/pages/settings/partials/validation_summary.blade.php`
7. `resources/views/admin/pages/settings/partials/publication_status.blade.php`
8. `resources/views/admin/pages/settings/history/index.blade.php`
9. `resources/views/admin/pages/settings/history/show.blade.php`
10. `resources/views/admin/pages/settings/history/compare.blade.php`
11. `resources/views/admin/pages/settings/preview.blade.php`

### Existing view update

- `resources/views/admin/layout/leftmenu.blade.php`

Add permission-aware Settings navigation:

```text
Settings
├── General
├── Brand Identity
├── Theme
├── Typography
├── Header
├── Footer
├── Contact
├── Social Media
├── Forms
├── Loader
├── Global Visual Settings
├── Legal
└── Publication History
```

### Admin header integration

Likely update:

- `resources/views/admin/layout/admin_layout.blade.php`

Add:

- Brand switcher
- Current brand indicator
- Global-scope indicator for authorized users
- Pending draft status

### Page behavior

#### Settings overview

- Current brand and locale
- Group cards/navigation
- Draft change count
- Last publication
- Effective source summary
- Preview and publication actions according to permission

#### Group form

- Definition-driven fields
- Inheritance source badge
- Add/reset brand override
- Field-level validation
- Save draft without publication
- Unsaved-change warning
- Optimistic locking token

#### History

- Publication list
- Actor and timestamp
- Change summary
- Value-level diff
- Rollback action

#### Preview

- Uses draft values for the authenticated editor only
- Does not mutate public cache
- Clearly marked as preview
- Expires

### UI implementation rule

Use the existing AdminLTE/Bootstrap stack initially. Do not introduce a new
frontend framework or dependency during the Settings implementation.

## 16. Frontend Settings Consumption

### New files

1. `app/View/Composers/PublicSettingsComposer.php`
2. `app/Providers/ViewServiceProvider.php`

Register the provider only after resolver readiness.

Alternative use for pages requiring fewer settings:

- Inject `SettingsResolver` into controllers/services rather than querying in
  Blade.

### Existing files likely updated during cutover

- `config/app.php` to register the view provider if Laravel package discovery
  is not applicable
- `app/Helper/admin/siteInformation.php`
- `resources/views/frontend/layout/app.blade.php`
- `resources/views/frontend/pages/index.blade.php`
- `resources/views/admin/layout/leftmenu.blade.php`

Cutover rules:

- Do not issue database queries from Blade.
- Do not call the old helper four times per render.
- Resolve one public settings bundle per brand/locale/publication.
- Keep a compatibility adapter so existing template keys remain available
  during staged migration.

### Compatibility adapter

Planned file:

- `app/Services/Settings/LegacySettingsAdapter.php`

Map:

| Legacy key | New definition |
|---|---|
| `site_name` | `general.site_name` |
| `site_logo` | `brand.primary_logo` |
| `fav_icon` | `brand.favicon` |
| `copy_right` | `general.copyright_text` |

Until Media Manager is active, legacy media URLs remain the fallback for the
logo/favicon keys.

## 17. Media Integration

### Settings-side implementation

Settings must:

- Use `media_attachments`
- Attach media to `SettingValue`
- Use controlled collections based on definition key
- Store no media path in `setting_values.value`
- Validate brand/shared ownership
- Require public, ready assets for public settings
- Display usage references in Media Manager

Suggested attachment model:

```text
attachable_type = setting_value
attachable_id   = setting_values.id
collection      = setting definition key or normalized media slot
```

### Dependency order

Media-valued settings become editable only after:

1. `media_assets` exists.
2. `media_attachments` exists.
3. Media Picker is available.
4. Asset readiness and brand policies are active.

Before then:

- Show the current resolved media preview.
- Mark the field “Media Manager required.”
- Prevent direct file upload from Settings.
- Keep legacy media values unchanged.

### No duplicate upload implementation

Do not reuse `app/Helper/admin/ImageUpload.php` for the new Settings Module.
The helper writes directly into public paths and does not provide the approved
security or media lifecycle.

## 18. Events, Listeners, and Audit

### New domain events

1. `app/Events/Settings/SettingsDraftUpdated.php`
2. `app/Events/Settings/SettingsPublished.php`
3. `app/Events/Settings/SettingsRolledBack.php`
4. `app/Events/Settings/SettingOverrideReset.php`

### New listeners

1. `app/Listeners/Settings/InvalidateSettingsCache.php`
2. `app/Listeners/Settings/RecordSettingsActivity.php`
3. `app/Listeners/Settings/RecordSettingsAudit.php`

Update:

- `app/Providers/EventServiceProvider.php`

Events are dispatched after successful transactions where appropriate.

Audit data:

- Actor
- Brand/global scope
- Locale
- Definition keys changed
- Before/after values with sensitive values redacted
- Publication UUID
- Approval request
- Rollback reason

## 19. Configuration

### New configuration file

- `config/settings.php`

Contents:

- Supported types
- Supported input controls
- Default locale
- Cache prefix and TTL
- Preview expiry
- Allowed URL schemes
- Rich-text sanitization profile name
- Maximum repeater items
- Feature controls for shadow reads and cutover

Do not place business setting values in this file.

Suggested feature controls:

- `SETTINGS_ADMIN_ENABLED`
- `SETTINGS_SHADOW_COMPARE_ENABLED`
- `SETTINGS_PUBLIC_READ_ENABLED`

Environment changes occur only in the approved deployment phase. Code must
provide safe defaults that preserve the legacy read path.

## 20. Testing Plan

### New unit tests

1. `tests/Unit/Settings/SettingValueCasterTest.php`
2. `tests/Unit/Settings/SettingValidatorTest.php`
3. `tests/Unit/Settings/SettingsResolverTest.php`
4. `tests/Unit/Settings/SettingsCacheServiceTest.php`

### New feature tests

1. `tests/Feature/Admin/Settings/SettingsAccessTest.php`
2. `tests/Feature/Admin/Settings/SettingsDraftTest.php`
3. `tests/Feature/Admin/Settings/SettingsPublicationTest.php`
4. `tests/Feature/Admin/Settings/SettingsRollbackTest.php`
5. `tests/Feature/Admin/Settings/SettingsPreviewTest.php`
6. `tests/Feature/Admin/Settings/SettingsValidationTest.php`
7. `tests/Feature/Admin/Settings/SettingsBrandIsolationTest.php`
8. `tests/Feature/Admin/Settings/SettingsInheritanceTest.php`
9. `tests/Feature/Admin/Settings/SettingsMediaAuthorizationTest.php`
10. `tests/Feature/Admin/Settings/LegacySettingsImportTest.php`
11. `tests/Feature/Public/PublishedSettingsRenderingTest.php`

### Required test cases

#### Brand isolation

- MAAC editor cannot edit AKSHA.
- AKSHA draft cannot affect MAAC.
- Space-E-Fic media cannot be attached to MAAC without shared permission.

#### Inheritance

- Brand override wins over global.
- Removing override returns to global.
- Locale fallback order is correct.
- Definition default is used only when no published value exists.

#### Draft/publication

- Draft does not change public output.
- Publication atomically changes all selected values.
- Failed validation publishes nothing.
- Editing after approval invalidates approval.

#### Security

- Direct URLs cannot bypass policy.
- Unknown keys are rejected.
- Unsafe rich text and URLs are rejected.
- Sensitive values never appear in public bundles, diffs, or logs.
- CSRF is required.

#### Media

- Private/processing/foreign-brand assets are rejected.
- Published media cannot be replaced by a raw path.

#### Rollback

- Prior publication can be restored as a new publication.
- History remains immutable.
- Missing/ineligible media blocks unsafe rollback.

#### Legacy

- Known keys import correctly.
- Import is idempotent.
- Shadow comparison identifies mismatches.
- Feature-control rollback restores legacy output.

## 21. Exact File Change Inventory

### New files

#### Migrations

- Nine migration files listed in Section 6

#### Seeders

- `database/seeders/BrandSeeder.php`
- `database/seeders/SettingGroupSeeder.php`
- `database/seeders/SettingDefinitionSeeder.php`
- `database/seeders/SettingsPermissionSeeder.php`

#### Models

- `app/Models/Brand.php`
- `app/Models/BrandDomain.php`
- `app/Models/SettingGroup.php`
- `app/Models/SettingDefinition.php`
- `app/Models/SettingValue.php`
- `app/Models/SettingValueVersion.php`
- `app/Models/SettingsPublication.php`
- `app/Models/SettingsPublicationItem.php`

#### Services

- Nine services listed in Section 9
- `app/Services/Settings/LegacySettingsAdapter.php`

#### Controllers

- Six controllers listed in Section 10

#### Requests

- Eight Form Requests listed in Section 11

#### Policies

- Four policies listed in Section 12

#### Middleware

- `app/Http/Middleware/ResolveAdminBrand.php`

#### Events/listeners

- Four events and three listeners listed in Section 18

#### Views

- Eleven Settings views/partials listed in Section 15

#### View integration

- `app/View/Composers/PublicSettingsComposer.php`
- `app/Providers/ViewServiceProvider.php`

#### Configuration

- `config/settings.php`

#### Tests

- Fifteen test files listed in Section 20

### Existing files likely modified

- `database/seeders/DatabaseSeeder.php`
- `app/Models/User.php`
- `app/Providers/AuthServiceProvider.php`
- `app/Providers/EventServiceProvider.php`
- `app/Http/Kernel.php`
- `routes/admin.php`
- `resources/views/admin/layout/admin_layout.blade.php`
- `resources/views/admin/layout/leftmenu.blade.php`
- `config/app.php`
- `app/Helper/admin/siteInformation.php`
- `resources/views/frontend/layout/app.blade.php`
- `resources/views/frontend/pages/index.blade.php`

### Explicitly preserved in initial release

- `database/migrations/2021_06_14_084524_create_site_info_table.php`
- `app/Models/SiteInformationModel.php`
- `app/Http/Controllers/Admin/SiteInformation/SiteInformationController.php`
- `resources/views/admin/pages/site_info/index.blade.php`
- `resources/views/admin/pages/site_info/add.blade.php`
- Existing `site_info` rows
- Existing public media files

## 22. Rollout Order

### Phase S0 — Pre-implementation gate

Tasks:

1. Back up database and verify restore.
2. Inventory `site_info` keys and duplicates.
3. Identify every template/helper consumer.
4. Confirm MAAC/AKSHA/Space-E-Fic records and domain policy.
5. Approve initial setting catalogue.
6. Confirm which settings require approval.
7. Confirm media dependency behavior.

Exit criteria:

- No unresolved duplicate legacy keys
- Import mapping approved
- RBAC prerequisite approved for implementation
- Rollback environment verified

### Phase S1 — Brand and minimum RBAC foundation

Tasks:

1. Create brand tables/models.
2. Seed three brands.
3. Assign existing administrator to reviewed brand/global scope.
4. Implement required settings permissions and policies.
5. Implement trusted admin brand context.

Exit criteria:

- Cross-brand access tests pass
- Existing admin access remains available
- No Settings route exposed without policy enforcement

### Phase S2 — Settings schema and catalogue

Tasks:

1. Create settings migrations.
2. Create models and relationships.
3. Seed groups and definitions.
4. Validate migration rollback in a disposable database.

Exit criteria:

- Fresh and existing-schema migrations pass
- Seeders are idempotent
- No changes to `site_info`

### Phase S3 — Resolver and draft services

Tasks:

1. Implement casting and validation.
2. Implement inheritance resolver.
3. Implement draft/version service.
4. Implement cache service.
5. Add unit tests.

Exit criteria:

- Resolution matrix passes
- Drafts do not affect public values
- Sensitive/public filtering passes

### Phase S4 — Publication, history, and rollback

Tasks:

1. Implement transactional publication.
2. Implement history and comparison.
3. Implement rollback-as-new-publication.
4. Integrate approval contract.
5. Add audit/activity events.

Exit criteria:

- Atomicity tests pass
- Approval hash/version tests pass
- Rollback test passes

### Phase S5 — Admin UI

Tasks:

1. Add routes/controllers/requests.
2. Build Settings navigation and forms.
3. Add brand switcher.
4. Add inheritance display.
5. Add preview/history/compare pages.
6. Add permission-aware actions.

Exit criteria:

- All admin actions work with keyboard and server validation
- Direct route access is denied correctly
- Brand context is always visible

### Phase S6 — Media integration boundary

If Media Manager is available:

1. Enable media fields.
2. Integrate Media Picker.
3. Validate attachments and usage.

If Media Manager is not available:

1. Keep media fields read-only.
2. Retain legacy media fallback.
3. Do not implement direct Settings uploads.

Exit criteria:

- No raw upload path is introduced
- No private or cross-brand asset can be published

### Phase S7 — Legacy import

Tasks:

1. Run dry-run inventory.
2. Import mapped keys into draft/global or MAAC scope.
3. Review imported output.
4. Publish an initial settings publication.
5. Produce mismatch report.

Exit criteria:

- Import is idempotent
- Legacy and resolved output match for mapped keys
- No legacy rows/files changed

### Phase S8 — Shadow read

Tasks:

1. Keep public output on legacy helper.
2. Resolve new settings in parallel.
3. Log only key-level mismatch metadata, not sensitive values.
4. Run homepage/admin regression checks.

Exit criteria:

- No unexplained mismatches for the agreed observation period
- Query count and response time meet budget
- Cache invalidation verified

### Phase S9 — Controlled cutover

Tasks:

1. Enable new resolver for one low-risk setting group.
2. Verify MAAC public rendering.
3. Expand to remaining text settings.
4. Enable media settings only after Media Manager readiness.
5. Monitor errors and cache.

Exit criteria:

- Homepage and admin remain available
- Brand output is correct
- No sensitive data exposure
- Feature-control rollback tested

### Phase S10 — Stabilization

Tasks:

1. Disable legacy write navigation.
2. Keep legacy table/read fallback for the agreed period.
3. Document operating procedures.
4. Perform access review.
5. Complete production-readiness review.

Exit criteria:

- Settings Module is authoritative
- Legacy writes are closed
- Rollback window and future cleanup are separately approved

## 23. Deployment and Rollback

### Before deployment

- Database backup and restore test
- Migration dry run
- Current `site_info` export
- Configuration/feature-control snapshot
- Route list capture
- Test suite pass

### Deployment sequence

1. Deploy additive code with new public reads disabled.
2. Run migrations.
3. Run reviewed idempotent seeders.
4. Assign initial permissions and brand membership.
5. Enable Settings admin for authorized users.
6. Import legacy settings in dry-run mode, then approved write mode.
7. Publish initial settings.
8. Enable shadow comparison.
9. Enable public reads only after acceptance.

### Application rollback

1. Disable new public settings reads.
2. Restore legacy helper path.
3. Disable new Settings writes if data integrity is uncertain.
4. Preserve new tables, versions, and audit records.
5. Investigate before database rollback.

### Migration rollback

Migration rollback is permitted only before Settings data becomes
authoritative and after a verified backup.

Do not drop Settings tables merely to recover public rendering. Use the
feature-controlled legacy read path.

## 24. Risks and Controls

| Risk | Control |
|---|---|
| Cross-brand data leakage | Scoped policies, queries, route binding, tests |
| Duplicate legacy keys | Pre-import inventory and explicit mapping |
| Draft visible publicly | Separate statuses, resolver filters, tests |
| Publication partial failure | Database transaction and row locks |
| Cache serves stale values | Publication-version cache keys |
| Sensitive value exposure | Public flag filtering, encryption/redaction policy |
| Unsafe rich text | Strict sanitizer and server validation |
| Media path regression | Media references plus legacy fallback |
| Settings become arbitrary CMS | Fixed catalogue and definition permissions |
| Admin lockout | Additive RBAC rollout and reviewed bootstrap assignment |
| Lost concurrent edits | Optimistic locking/version token |
| Irreversible cutover | Shadow reads and retained legacy path |

## 25. Definition of Done

Settings Module implementation is complete when:

1. All schema migrations and rollbacks pass in a disposable environment.
2. MAAC, AKSHA, and Space-E-Fic are first-class brand records.
3. Settings definitions are typed and seeded idempotently.
4. Brand/global/locale inheritance is correct.
5. Draft changes do not affect public rendering.
6. Publication is atomic and audited.
7. Rollback creates a new publication without deleting history.
8. RBAC and brand isolation tests pass.
9. Admin navigation and direct routes enforce the same permissions.
10. Sensitive settings never enter public bundles or logs.
11. Media settings use Media Manager references or remain safely read-only.
12. Existing `site_info` data and public media remain intact.
13. Legacy import and shadow comparison pass.
14. Homepage, admin login, CSS, JavaScript, images, and video regressions pass.
15. Feature-controlled rollback to legacy reads is tested.
16. Production deployment and operating runbooks are approved.

## 26. Approval Gate

Before code implementation, approve:

1. Database tables and normalized `scope_key` strategy
2. Initial setting catalogue
3. Brand seeding and administrator assignment
4. Minimum RBAC prerequisite
5. Media fields remaining read-only until Media Manager exists
6. Legacy key mapping
7. Publication approval policy
8. Rollout and rollback sequence

No code, migrations, seeders, routes, or views should be created until this
plan is approved.
