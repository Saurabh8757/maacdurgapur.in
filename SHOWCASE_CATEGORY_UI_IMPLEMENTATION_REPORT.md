# Showcase Category UI Implementation Report

## Routes added

All routes are inside the existing authenticated, brand-context-aware admin group with the `v1/cpanel/admin/content-management` prefix.

- `GET showcase-categories` — `admin::content.showcase-categories.index`
- `GET showcase-categories/create` — `admin::content.showcase-categories.create`
- `POST showcase-categories` — `admin::content.showcase-categories.store`
- `GET showcase-categories/{category}/edit` — `admin::content.showcase-categories.edit`
- `PUT showcase-categories/{category}` — `admin::content.showcase-categories.update`
- `DELETE showcase-categories/{category}` — `admin::content.showcase-categories.destroy`

## Blades created

- `resources/views/admin/cms/showcase-categories/index.blade.php`
- `resources/views/admin/cms/showcase-categories/create.blade.php`
- `resources/views/admin/cms/showcase-categories/edit.blade.php`
- `resources/views/admin/cms/showcase-categories/_form.blade.php`

The index contains Name, Slug, Status, Sort Order, Created, and Actions columns. The shared form contains Name, Slug, Status, and Sort Order fields.

## Controller created

`App\Http\Controllers\Admin\Cms\Pages\CmsShowcaseCategoryPageController`

The controller implements index, create, store, edit, update, and destroy. It uses:

- `CmsAuthorizationService` for `showcase` view/create/edit/delete permissions.
- `CmsShowcaseReadService` for active-brand category lookup.
- `CmsShowcaseService` for audited create/update/delete operations.
- `CmsShowcaseCategoryRequest` for the existing validation rules.

Categories containing showcase projects cannot be deleted from the UI or controller.

## Sidebar integration

Added **Showcase Categories** below **Content Management**. It is displayed only when the current user has `showcase:view` permission for the active brand.

## Dropdown verification

The Showcase Project create/edit pages already obtain their dropdown data through:

```php
'categories' => $this->readService->getCategoriesAdmin(),
```

and render each `CmsShowcaseCategory` in `admin.cms.showcase._form`.

Creating a category through the new UI therefore makes it available in the Showcase Project Category dropdown for the same active brand without duplicated query logic.
