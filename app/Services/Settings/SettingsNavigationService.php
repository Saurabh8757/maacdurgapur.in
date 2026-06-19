<?php

namespace App\Services\Settings;

use App\Models\User;
use App\Services\Brands\BrandContextManager;

class SettingsNavigationService
{
    public function __construct(
        private SettingsAuthorizationService $authorization,
        private BrandContextManager $brandContextManager,
        private SettingsLocaleResolver $localeResolver
    ) {
    }

    public function canViewBrand(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        $context = $this->brandContextManager->adminContext();

        if (!$context || $context->userId() !== (int) $user->getKey()) {
            return false;
        }

        return $this->authorization->allows(
            $user,
            SettingsScope::forBrand(
                $context->brand(),
                $this->localeResolver->brandDefault($context->brand())
            ),
            'view'
        );
    }

    public function canViewGlobal(?User $user): bool
    {
        return $user
            ? $this->authorization->allows(
                $user,
                SettingsScope::forGlobal($this->localeResolver->globalDefault()),
                'view'
            )
            : false;
    }
}
