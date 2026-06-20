<?php

namespace Tests\Feature\Admin\Cms;

use App\Models\Brand;
use App\Models\CmsCourse;
use App\Models\CmsFaq;
use App\Models\CmsFaqCategory;
use App\Models\CmsFeature;
use App\Models\CmsShowcaseCategory;
use App\Models\CmsShowcaseProject;
use App\Models\User;
use App\Services\Brands\AdminBrandContext;
use App\Services\Brands\AdminBrandContextResolver;
use App\Services\Cms\CmsAuthorizationService;
use App\Services\Cms\CmsCourseReadService;
use App\Services\Cms\CmsFaqReadService;
use App\Services\Cms\CmsFeatureReadService;
use App\Services\Cms\CmsShowcaseReadService;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class CmsAdminUiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->detectEnvironment(static fn () => 'local');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_ui_route_inventory_is_get_only_and_api_routes_are_unchanged(): void
    {
        $routes = $this->app['router']->getRoutes();
        $uiNames = [
            'admin::content.faqs.index',
            'admin::content.faqs.create',
            'admin::content.faqs.edit',
            'admin::content.courses.index',
            'admin::content.courses.create',
            'admin::content.courses.edit',
            'admin::content.features.index',
            'admin::content.features.create',
            'admin::content.features.edit',
            'admin::content.showcase.index',
            'admin::content.showcase.create',
            'admin::content.showcase.edit',
        ];

        foreach ($uiNames as $name) {
            $route = $routes->getByName($name);
            $this->assertNotNull($route, $name);
            $this->assertSame(
                ['GET'],
                array_values(array_diff($route->methods(), ['HEAD'])),
                $name
            );
            $this->assertContains('AdminMiddleware', $route->gatherMiddleware());
        }

        $apiRoutes = collect($routes->getRoutes())->filter(
            static fn ($route) => str_starts_with(
                $route->uri(),
                'v1/cpanel/admin/cms/'
            )
        );

        $this->assertCount(25, $apiRoutes);
    }

    public function test_authorized_admin_can_render_all_cms_screens_and_sidebar_group(): void
    {
        $user = $this->adminUser();
        $this->mockContext((int) $user->id);
        $this->mockAuthorization(true);
        $this->mockReadServices();

        $screens = [
            ['admin::content.faqs.index', [], 'Content Management'],
            ['admin::content.faqs.create', [], 'Create FAQ'],
            ['admin::content.faqs.edit', ['faq' => 101], 'Edit FAQ'],
            ['admin::content.courses.index', [], 'Courses'],
            ['admin::content.courses.create', [], 'Create Course'],
            ['admin::content.courses.edit', ['course' => 102], 'Edit Course'],
            ['admin::content.features.index', [], 'Features'],
            ['admin::content.features.create', [], 'Create Feature'],
            ['admin::content.features.edit', ['feature' => 103], 'Edit Feature'],
            ['admin::content.showcase.index', [], 'Showcase'],
            ['admin::content.showcase.create', [], 'Create Showcase Project'],
            ['admin::content.showcase.edit', ['showcase' => 104], 'Edit Showcase Project'],
        ];

        foreach ($screens as [$route, $parameters, $text]) {
            $this->actingAs($user)
                ->withHeader('Host', 'localhost')
                ->get(route($route, $parameters, false))
                ->assertOk()
                ->assertSee($text);
        }
    }

    public function test_page_access_is_denied_by_cms_authorization_service(): void
    {
        $user = $this->adminUser();
        $this->mockContext((int) $user->id);
        $this->mockAuthorization(false);

        $this->actingAs($user)
            ->withHeader('Host', 'localhost')
            ->get(route('admin::content.faqs.index', [], false))
            ->assertForbidden();
    }

    private function mockContext(int $userId): void
    {
        $brand = new Brand([
            'uuid' => 'e0802a5c-9ae4-4583-9534-d6c9281be008',
            'code' => 'maac',
            'name' => 'MAAC',
            'status' => 'active',
            'is_primary' => true,
        ]);
        $brand->id = 1;
        $brand->exists = true;

        $context = new AdminBrandContext(
            $brand,
            $userId,
            'test',
            CarbonImmutable::now(),
            true
        );
        $resolver = Mockery::mock(AdminBrandContextResolver::class);
        $resolver->shouldReceive('resolve')->andReturn($context);
        $this->app->instance(AdminBrandContextResolver::class, $resolver);
    }

    private function adminUser(): User
    {
        $user = new User([
            'name' => 'CMS Test Administrator',
            'email' => 'cms-admin@example.test',
        ]);
        $user->id = 9002;
        $user->user_type = 'Admin';
        $user->slug_name = 'cms-test-administrator';
        $user->profile_picture = 'default.png';
        $user->exists = true;

        return $user;
    }

    private function mockAuthorization(bool $allowed): void
    {
        $authorization = Mockery::mock(CmsAuthorizationService::class);
        $authorization->shouldReceive('allows')->andReturn($allowed);
        $authorization->shouldReceive('authorize')->andReturnUsing(
            static function () use ($allowed): void {
                abort_unless($allowed, 403);
            }
        );
        $this->app->instance(CmsAuthorizationService::class, $authorization);
    }

    private function mockReadServices(): void
    {
        $category = new CmsFaqCategory(['name' => 'General', 'status' => 'active']);
        $category->id = 201;
        $faq = new CmsFaq([
            'question' => 'Sample question',
            'answer' => 'Sample answer',
            'status' => 'active',
            'sort_order' => 1,
        ]);
        $faq->id = 101;
        $faq->setRelation('category', $category);

        $course = new CmsCourse([
            'title' => 'Sample course',
            'slug' => 'sample-course',
            'description' => 'Sample description',
            'status' => 'active',
            'sort_order' => 1,
        ]);
        $course->id = 102;

        $feature = new CmsFeature([
            'title' => 'Sample feature',
            'slug' => 'sample-feature',
            'description' => 'Sample description',
            'status' => 'active',
            'sort_order' => 1,
        ]);
        $feature->id = 103;

        $showcaseCategory = new CmsShowcaseCategory([
            'name' => 'Animation',
            'status' => 'active',
        ]);
        $showcaseCategory->id = 202;
        $project = new CmsShowcaseProject([
            'title' => 'Sample project',
            'slug' => 'sample-project',
            'student_name' => 'Sample student',
            'short_description' => 'Sample description',
            'status' => 'draft',
            'sort_order' => 1,
        ]);
        $project->id = 104;
        $project->setRelation('category', $showcaseCategory);

        $faqRead = Mockery::mock(CmsFaqReadService::class);
        $faqRead->shouldReceive('getAllAdmin')->andReturn(new Collection([$faq]));
        $faqRead->shouldReceive('getCategoriesAdmin')->andReturn(new Collection([$category]));
        $this->app->instance(CmsFaqReadService::class, $faqRead);

        $courseRead = Mockery::mock(CmsCourseReadService::class);
        $courseRead->shouldReceive('getAllAdmin')->andReturn(new Collection([$course]));
        $this->app->instance(CmsCourseReadService::class, $courseRead);

        $featureRead = Mockery::mock(CmsFeatureReadService::class);
        $featureRead->shouldReceive('getAllAdmin')->andReturn(new Collection([$feature]));
        $this->app->instance(CmsFeatureReadService::class, $featureRead);

        $showcaseRead = Mockery::mock(CmsShowcaseReadService::class);
        $showcaseRead->shouldReceive('getProjectsAdmin')->andReturn(new Collection([$project]));
        $showcaseRead->shouldReceive('getCategoriesAdmin')->andReturn(new Collection([$showcaseCategory]));
        $this->app->instance(CmsShowcaseReadService::class, $showcaseRead);
    }
}
