<?php

namespace App\Http\Controllers\Admin\Cms\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Cms\CmsShowcaseCategoryRequest;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use App\Services\Cms\CmsShowcaseReadService;
use App\Services\Cms\CmsShowcaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CmsShowcaseCategoryPageController extends Controller
{
    public function __construct(
        private readonly CmsShowcaseReadService $readService,
        private readonly CmsShowcaseService $writeService,
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {
    }

    public function index(Request $request)
    {
        $permissions = $this->permissions();
        abort_unless($permissions['view'], 403);

        $categories = $this->readService->getCategoriesAdmin();
        $search = trim((string) $request->query('q'));

        if ($search !== '') {
            $categories = $categories->filter(
                static fn ($category) => str_contains(
                    mb_strtolower($category->name.' '.$category->slug),
                    mb_strtolower($search)
                )
            )->values();
        }

        return view('admin.cms.showcase-categories.index', [
            'categories' => $this->paginate($categories, $request),
            'permissions' => $permissions,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $this->authorizeOperation('create');

        return view('admin.cms.showcase-categories.create');
    }

    public function store(CmsShowcaseCategoryRequest $request): RedirectResponse
    {
        $this->writeService->createCategory($request->validated());

        return redirect()
            ->route('admin::content.showcase-categories.index')
            ->with('success', 'Showcase category created successfully.');
    }

    public function edit(int $category)
    {
        $this->authorizeOperation('edit');

        return view('admin.cms.showcase-categories.edit', [
            'category' => $this->findCategory($category),
        ]);
    }

    public function update(CmsShowcaseCategoryRequest $request, int $category): RedirectResponse
    {
        $this->writeService->updateCategory(
            $this->findCategory($category),
            $request->validated()
        );

        return redirect()
            ->route('admin::content.showcase-categories.index')
            ->with('success', 'Showcase category updated successfully.');
    }

    public function destroy(int $category): RedirectResponse
    {
        $this->authorizeOperation('delete');
        $item = $this->findCategory($category);

        if ($item->projects_count > 0) {
            return redirect()
                ->route('admin::content.showcase-categories.index')
                ->with('error', 'Move or delete the category projects before deleting this category.');
        }

        $this->writeService->deleteCategory($item);

        return redirect()
            ->route('admin::content.showcase-categories.index')
            ->with('success', 'Showcase category deleted successfully.');
    }

    private function findCategory(int $category)
    {
        $item = $this->readService->getCategoriesAdmin()->firstWhere('id', $category);
        abort_unless($item, 404);

        return $item;
    }

    private function permissions(): array
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $user = auth()->user();

        return collect(['view', 'create', 'edit', 'delete'])
            ->mapWithKeys(fn (string $operation) => [
                $operation => $this->authorizationService->allows(
                    $user,
                    'showcase',
                    $operation,
                    $brand
                ),
            ])->all();
    }

    private function authorizeOperation(string $operation): void
    {
        $this->authorizationService->authorize(
            auth()->user(),
            'showcase',
            $operation,
            $this->brandContextManager->requireAdminContext()->brand()
        );
    }

    private function paginate(Collection $items, Request $request): LengthAwarePaginator
    {
        $perPage = 10;
        $page = LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
}
