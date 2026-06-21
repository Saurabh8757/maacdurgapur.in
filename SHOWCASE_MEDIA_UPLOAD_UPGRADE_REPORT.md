# Showcase Media Upload Upgrade Report

## Admin form upgrade

- Replaced the visible numeric Thumbnail Media ID field on Showcase Project create/edit screens with an image file picker.
- The existing `thumbnail_media_id` field remains in the form as the schema-compatible project attachment value, but is hidden from users.
- Uploading a file immediately creates a `media_assets` record and writes the returned asset ID into `thumbnail_media_id`.
- Create and edit screens show an image preview. Edit displays the current thumbnail before replacement.
- The `video_url` field remains unchanged in storage and validation, while its form label is now **Project Link**.

## Media upload

Added:

- `POST v1/cpanel/admin/cms/showcase-media`
- Route name: `admin::cms.showcase_media.store`
- Controller: `App\Http\Controllers\Admin\Cms\CmsShowcaseMediaController`

Uploads are:

- Restricted to JPG, JPEG, PNG, WebP, and GIF.
- Limited to 10 MB.
- Stored on the existing `public` filesystem disk under `media/showcase/{brand_id}`.
- Registered as brand-scoped, public, active image records in `media_assets`.
- Protected by existing Showcase create/edit RBAC.

## Frontend behavior

- Showcase cards render the assigned uploaded thumbnail through the existing `thumbnail` relation.
- The **No Thumbnail** placeholder was removed.
- When `video_url`/Project Link exists, the entire card is an external link opening in a new tab with `noopener noreferrer`.
- Projects without a Project Link remain non-clickable cards.

## Compatibility

- No database migration was added.
- `cms_showcase_projects.video_url` remains the persisted Project Link column.
- `CmsShowcaseProject` was not changed.
- Existing category, publication, sorting, and project API behavior remain intact.
