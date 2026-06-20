<?php

namespace App\Http\Controllers\Admin\Cms\Pages;

use App\Http\Controllers\Controller;
use App\Services\Brands\BrandContextManager;
use App\Services\Cms\CmsAuthorizationService;
use App\Services\Cms\CmsCourseReadService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CmsCoursePageController extends Controller
{
    public function __construct(
        private readonly CmsCourseReadService $readService,
        private readonly BrandContextManager $brandContextManager,
        private readonly CmsAuthorizationService $authorizationService
    ) {
    }

    public function index(Request $request)
    {
        $permissions = $this->permissions();
        abort_unless($permissions['view'], 403);
        $items = $this->readService->getAllAdmin();
        $search = trim((string) $request->query('q'));

        if ($search !== '') {
            $items = $items->filter(
                static fn ($course) => str_contains(
                    mb_strtolower($course->title.' '.$course->slug.' '.$course->description),
                    mb_strtolower($search)
                )
            )->values();
        }

        return view('admin.cms.courses.index', [
            'courses' => $this->paginate($items, $request),
            'permissions' => $permissions,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $this->authorizeOperation('create');

        return view('admin.cms.courses.create');
    }

    public function edit(int $course)
    {
        $this->authorizeOperation('edit');
        $item = $this->readService->getAllAdmin()->firstWhere('id', $course);
        abort_unless($item, 404);

        return view('admin.cms.courses.edit', ['course' => $item]);
    }

    private function permissions(): array
    {
        $brand = $this->brandContextManager->requireAdminContext()->brand();
        $user = auth()->user();

        return collect(['view', 'create', 'edit', 'delete'])
            ->mapWithKeys(fn (string $operation) => [
                $operation => $this->authorizationService->allows(
                    $user,
                    'courses',
                    $operation,
                    $brand
                ),
            ])->all();
    }

    private function authorizeOperation(string $operation): void
    {
        $this->authorizationService->authorize(
            auth()->user(),
            'courses',
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
