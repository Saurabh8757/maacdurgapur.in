<?php

namespace Tests\Unit\Settings;

use App\Services\Settings\SettingsAuthorizationService;
use App\Services\Settings\SettingsLocaleResolver;
use App\Services\Settings\SettingsScopeResolver;
use Tests\TestCase;

class SettingsPhaseBContainerBindingTest extends TestCase
{
    /**
     * @dataProvider serviceProvider
     */
    public function test_phase_b_services_are_request_scoped(
        string $service
    ): void {
        $first = $this->app->make($service);
        $sameScope = $this->app->make($service);

        $this->assertSame($first, $sameScope);

        $this->app->forgetScopedInstances();

        $nextScope = $this->app->make($service);

        $this->assertNotSame($first, $nextScope);
    }

    public function test_locale_configuration_matches_first_release_policy(): void
    {
        $this->assertSame(
            ['en'],
            config('brands.settings.supported_locales')
        );
        $this->assertSame(
            'en',
            config('brands.settings.default_locale')
        );
    }

    public function serviceProvider(): array
    {
        return [
            [SettingsScopeResolver::class],
            [SettingsAuthorizationService::class],
            [SettingsLocaleResolver::class],
        ];
    }
}
