<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class SettingsPhaseCReadOnlyUiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->detectEnvironment(static fn () => 'local');
    }

    public function test_super_admin_can_view_brand_and_global_settings(): void
    {
        $user = User::findOrFail(6);

        $this->actingAs($user)
            ->withHeader('Host', 'localhost')
            ->get(route('admin::settings.brand.index', [], false))
            ->assertOk()
            ->assertSee('Brand Settings')
            ->assertSee('Read only')
            ->assertSee('Inactive');

        $this->actingAs($user)
            ->withHeader('Host', 'localhost')
            ->get(route('admin::settings.global.index', [], false))
            ->assertOk()
            ->assertSee('Global Settings')
            ->assertSee('Read only');
    }

    public function test_settings_routes_are_get_only_and_route_inventory_is_seventy_two(): void
    {
        $this->assertCount(72, $this->app['router']->getRoutes()->getRoutes());

        foreach ([
            'admin::settings.brand.index',
            'admin::settings.global.index',
        ] as $name) {
            $route = $this->app['router']->getRoutes()->getByName($name);

            $this->assertNotNull($route);
            $this->assertSame(
                ['GET'],
                array_values(array_diff($route->methods(), ['HEAD']))
            );
            $this->assertContains('AdminMiddleware', $route->gatherMiddleware());
        }
    }

    public function test_unknown_group_returns_not_found(): void
    {
        $this->actingAs(User::findOrFail(6))
            ->withHeader('Host', 'localhost')
            ->get(route('admin::settings.global.index', [
                'group' => 'not-a-real-group',
            ], false))
            ->assertNotFound();
    }

    public function test_unsupported_locale_and_scope_injection_are_rejected(): void
    {
        $user = User::findOrFail(6);

        $this->actingAs($user)
            ->from(route('admin::settings.global.index', [], false))
            ->withHeader('Host', 'localhost')
            ->get(route('admin::settings.global.index', ['locale' => 'fr'], false))
            ->assertRedirect(route('admin::settings.global.index', [], false))
            ->assertSessionHasErrors('locale');

        $this->actingAs($user)
            ->from(route('admin::settings.brand.index', [], false))
            ->withHeader('Host', 'localhost')
            ->get(route('admin::settings.brand.index', [
                'brand_id' => 999,
                'brand_uuid' => 'injected',
            ], false))
            ->assertRedirect(route('admin::settings.brand.index', [], false))
            ->assertSessionHasErrors(['brand_id', 'brand_uuid']);
    }

    public function test_get_requests_do_not_write_settings_audit_or_site_info_data(): void
    {
        $before = $this->integritySnapshot();
        $user = User::findOrFail(6);

        $this->actingAs($user)
            ->withHeader('Host', 'localhost')
            ->get(route('admin::settings.brand.index', [], false))
            ->assertOk();
        $this->actingAs($user)
            ->withHeader('Host', 'localhost')
            ->get(route('admin::settings.global.index', [], false))
            ->assertOk();

        $this->assertSame($before, $this->integritySnapshot());
    }

    public function test_legacy_admin_without_rbac_is_forbidden(): void
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => 'Legacy Admin',
                'email' => 'legacy-'.Str::uuid().'@example.test',
                'password' => bcrypt('not-used'),
            ]);
            $user->forceFill(['user_type' => 'Admin'])->save();

            $this->actingAs($user)
                ->withHeader('Host', 'localhost')
                ->get(route('admin::settings.global.index', [], false))
                ->assertForbidden();
        } finally {
            DB::rollBack();
        }
    }

    private function integritySnapshot(): array
    {
        $tables = [
            'setting_values',
            'setting_value_versions',
            'settings_publications',
            'settings_publication_items',
            'setting_audit_logs',
        ];

        $counts = collect($tables)->mapWithKeys(
            static fn (string $table) => [$table => DB::table($table)->count()]
        )->all();

        $siteInfo = DB::table('site_info')->orderBy('id')->get();

        return [
            'counts' => $counts,
            'site_info_rows' => $siteInfo->count(),
            'site_info_sha256' => hash('sha256', $siteInfo->toJson()),
            'migration_batch' => (int) DB::table('migrations')->max('batch'),
        ];
    }
}
