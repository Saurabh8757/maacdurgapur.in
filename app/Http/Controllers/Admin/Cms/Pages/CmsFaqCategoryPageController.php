<?php

namespace App\Http\Controllers\Admin\Cms\Pages;

use App\Http\Controllers\Controller;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use App\Services\Cms\CmsFaqReadService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CmsFaqCategoryPageController extends Controller
{
    public function __construct(
        private readonly CmsFaqReadService $readService,
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

        return view('admin.cms.faq-categories.index', [
            'categories' => $this->paginate($categories, $request),
            'permissions' => $permissions,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $this->authorizeOperation('create');

        return view('admin.cms.faq-categories.create');
    }

    public function edit(int $faqCategory)
    {
        $this->authorizeOperation('edit');
        $category = $this->readService
            ->getCategoriesAdmin()
            ->firstWhere('id', $faqCategory);
        abort_unless($category, 404);

        return view('admin.cms.faq-categories.edit', [
            'category' => $category,
        ]);
    }

    private function permissions(): array
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $user = auth()->user();

        return collect(['view', 'create', 'edit', 'delete'])
            ->mapWithKeys(fn (string $operation) => [
                $operation => $this->authorizationService->allows(
                    $user,
                    'faqs',
                    $operation,
                    $brand
                ),
            ])->all();
    }

    private function authorizeOperation(string $operation): void
    {
        $this->authorizationService->authorize(
            auth()->user(),
            'faqs',
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
