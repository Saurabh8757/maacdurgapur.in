<?php

namespace App\Services\Settings;

use App\Models\User;
use App\Services\Brands\BrandContextManager;
use App\Services\Settings\Exceptions\SettingsScopeException;
use Illuminate\Contracts\Auth\Guard;
use LogicException;

class SettingsScopeResolver
{
    public function __construct(
        private BrandContextManager $brandContextManager,
        private SettingsLocaleResolver $localeResolver,
        private Guard $auth
    ) {
    }

    public function resolve(
        string $scope,
        ?string $locale = null
    ): SettingsScope {
        return match ($scope) {
            'brand' => $this->brandScope($locale),
            'global' => $this->globalScope($locale),
            default => throw SettingsScopeException::unsupportedScope($scope),
        };
    }

    public function brandScope(?string $locale = null): SettingsScope
    {
        $user = $this->auth->user();

        if (!$user instanceof User) {
            throw SettingsScopeException::unauthenticated();
        }

        try {
            $adminContext = $this->brandContextManager->requireAdminContext();
        } catch (LogicException) {
            throw SettingsScopeException::missingAdminContext();
        }

        if ($adminContext->userId() !== (int) $user->getKey()) {
            throw SettingsScopeException::contextUserMismatch();
        }

        $brand = $adminContext->brand();

        return SettingsScope::forBrand(
            $brand,
            $this->localeResolver->resolveBrand($brand, $locale)
        );
    }

    public function globalScope(?string $locale = null): SettingsScope
    {
        return SettingsScope::forGlobal(
            $this->localeResolver->resolveGlobal($locale)
        );
    }
}
