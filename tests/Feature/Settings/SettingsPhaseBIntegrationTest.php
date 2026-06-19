<?php

namespace Tests\Feature\Settings;

use App\Models\Brand;
use App\Models\User;
use App\Services\Authorization\PermissionResolver;
use App\Services\Settings\SettingsAuthorizationService;
use App\Services\Settings\SettingsScope;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SettingsPhaseBIntegrationTest extends TestCase
{
    public function test_bootstrap_super_admin_can_access_every_brand_and_global_scope(): void
    {
        $user = User::findOrFail(6);
        $authorization = $this->app->make(
            SettingsAuthorizationService::class
        );
        $brands = Brand::query()->active()->orderBy('id')->get();

        $this->assertSame(
            ['maac', 'aksha', 'space_e_fic'],
            $brands->pluck('code')->all()
        );

        foreach ($brands as $brand) {
            $this->assertTrue($authorization->allows(
                $user,
                SettingsScope::forBrand($brand, 'en'),
                'view'
            ));
        }

        $this->assertTrue($authorization->allows(
            $user,
            SettingsScope::forGlobal('en'),
            'view'
        ));
    }

    public function test_legacy_admin_without_rbac_is_denied(): void
    {
        $user = new User(['name' => 'Legacy Admin']);
        $user->id = 999999;
        $user->exists = true;
        $user->user_type = 'Admin';

        $this->assertFalse(
            $this->app->make(SettingsAuthorizationService::class)->allows(
                $user,
                SettingsScope::forGlobal('en'),
                'view'
            )
        );
    }

    public function test_phase_b_services_do_not_write_settings_or_audit_data(): void
    {
        $before = $this->tableCounts();
        $user = User::findOrFail(6);
        $brand = Brand::query()->where('code', 'maac')->firstOrFail();

        $this->app->make(PermissionResolver::class)->check(
            $user,
            'settings.brand.view',
            $brand
        );

        $this->assertSame($before, $this->tableCounts());
    }

    private function tableCounts(): array
    {
        return collect([
            'setting_audit_logs',
            'setting_values',
            'setting_value_versions',
            'settings_publications',
            'settings_publication_items',
        ])->mapWithKeys(
            static fn ($table) => [$table => DB::table($table)->count()]
        )->all();
    }
}
