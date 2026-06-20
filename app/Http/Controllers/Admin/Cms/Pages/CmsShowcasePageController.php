<?php

namespace App\Http\Controllers\Admin\Cms\Pages;

use App\Http\Controllers\Controller;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use App\Services\Cms\CmsShowcaseReadService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CmsShowcasePageController extends Controller
{
    public function __construct(
        private readonly CmsShowcaseReadService $readService,
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {
    }

    public function index(Request $request)
    {
        $permissions = $this->permissions();
        abort_unless($permissions['view'], 403);
        $items = $this->readService->getProjectsAdmin();
        $search = trim((string) $request->query('q'));

        if ($search !== '') {
            $items = $items->filter(
                static fn ($project) => str_contains(
                    mb_strtolower(
                        $project->title.' '.$project->student_name.' '.
                        optional($project->category)->name
                    ),
                    mb_strtolower($search)
                )
            )->values();
        }

        return view('admin.cms.showcase.index', [
            'projects' => $this->paginate($items, $request),
            'permissions' => $permissions,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $this->authorizeOperation('create');

        return view('admin.cms.showcase.create', [
            'categories' => $this->readService->getCategoriesAdmin(),
        ]);
    }

    public function edit(int $showcase)
    {
        $this->authorizeOperation('edit');
        $project = $this->readService->getProjectsAdmin()->firstWhere('id', $showcase);
        abort_unless($project, 404);

        return view('admin.cms.showcase.edit', [
            'project' => $project,
            'categories' => $this->readService->getCategoriesAdmin(),
        ]);
    }

    private function permissions(): array
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $user = auth()->user();

        return collect(['view', 'create', 'edit', 'delete', 'publish'])
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
