<?php

namespace App\Services\Settings;

use App\Models\Brand;
use App\Models\SettingDefinition;
use App\Services\Settings\Exceptions\UnsupportedSettingsLocaleException;

class SettingsLocaleResolver
{
    public function supportedLocales(): array
    {
        return array_values(array_unique(array_map(
            fn ($locale) => $this->normalize((string) $locale),
            config('brands.settings.supported_locales', ['en'])
        )));
    }

    public function globalDefault(): string
    {
        return $this->validate(
            (string) config(
                'brands.settings.default_locale',
                config('app.locale', 'en')
            )
        );
    }

    public function brandDefault(Brand $brand): string
    {
        return $this->validate(
            (string) ($brand->default_locale ?: $this->globalDefault())
        );
    }

    public function resolveGlobal(?string $requestedLocale = null): string
    {
        return $requestedLocale === null || trim($requestedLocale) === ''
            ? $this->globalDefault()
            : $this->validate($requestedLocale);
    }

    public function resolveBrand(
        Brand $brand,
        ?string $requestedLocale = null
    ): string {
        return $requestedLocale === null || trim($requestedLocale) === ''
            ? $this->brandDefault($brand)
            : $this->validate($requestedLocale);
    }

    public function resolveForDefinition(
        SettingDefinition $definition,
        SettingsScope $scope,
        ?string $requestedLocale = null
    ): string {
        if (!$definition->is_translatable) {
            return $scope->isBrand()
                ? $this->brandDefault($scope->brand())
                : $this->globalDefault();
        }

        return $scope->isBrand()
            ? $this->resolveBrand($scope->brand(), $requestedLocale)
            : $this->resolveGlobal($requestedLocale);
    }

    public function validate(string $locale): string
    {
        $normalized = $this->normalize($locale);

        if (!in_array($normalized, $this->supportedLocales(), true)) {
            throw new UnsupportedSettingsLocaleException($normalized);
        }

        return $normalized;
    }

    private function normalize(string $locale): string
    {
        return strtolower(str_replace('_', '-', trim($locale)));
    }
}
