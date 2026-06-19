<?php

namespace App\Services\Brands;

use App\Models\Brand;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdminBrandContextResolver
{
    public function __construct(
        private AdminBrandAccessResolver $accessResolver
    ) {
    }

    public function resolve(Request $request): ?AdminBrandContext
    {
        $user = $request->user();

        if (!$user instanceof User || !$request->hasSession()) {
            return null;
        }

        $accessibleBrands = $this->accessResolver->accessibleBrands($user);

        if ($accessibleBrands->isEmpty()) {
            $request->session()->forget($this->sessionKey());

            return null;
        }

        $storedContext = $request->session()->get($this->sessionKey());
        $storedBrand = $this->storedBrand(
            $storedContext,
            $user,
            $accessibleBrands
        );

        if ($storedBrand) {
            return $this->context(
                $storedBrand,
                $user,
                (string) $storedContext['source'],
                $this->parseSelectedAt($storedContext['selected_at'] ?? null)
            );
        }

        if ($storedContext !== null) {
            $request->session()->forget($this->sessionKey());
        }

        [$brand, $source] = $this->defaultSelection($user, $accessibleBrands);

        return $this->storeSelection($request, $user, $brand, $source);
    }

    public function select(
        Request $request,
        User $user,
        string $brandUuid
    ): ?AdminBrandContext {
        $brand = $this->accessResolver
            ->accessibleBrands($user)
            ->firstWhere('uuid', $brandUuid);

        if (!$brand) {
            return null;
        }

        return $this->storeSelection(
            $request,
            $user,
            $brand,
            'explicit_switch'
        );
    }

    private function defaultSelection(
        User $user,
        Collection $accessibleBrands
    ): array {
        $defaultBrand = $this->accessResolver->defaultAccessibleBrand(
            $user,
            $accessibleBrands
        );

        if ($defaultBrand) {
            return [$defaultBrand, 'user_default'];
        }

        if ($accessibleBrands->count() === 1) {
            return [$accessibleBrands->first(), 'single_accessible'];
        }

        if ($this->accessResolver->hasActiveGlobalSuperAdminAssignment($user)) {
            $primaryBrand = $accessibleBrands->firstWhere('is_primary', true);

            if ($primaryBrand) {
                return [$primaryBrand, 'primary_brand'];
            }
        }

        return [$accessibleBrands->first(), 'deterministic_fallback'];
    }

    private function storedBrand(
        mixed $storedContext,
        User $user,
        Collection $accessibleBrands
    ): ?Brand {
        if (
            !is_array($storedContext)
            || (int) ($storedContext['user_id'] ?? 0) !== (int) $user->id
            || !is_numeric($storedContext['brand_id'] ?? null)
            || !is_string($storedContext['brand_uuid'] ?? null)
            || !in_array(
                $storedContext['source'] ?? null,
                $this->allowedSources(),
                true
            )
        ) {
            return null;
        }

        $brand = $accessibleBrands->firstWhere(
            'id',
            (int) $storedContext['brand_id']
        );

        if (!$brand || $brand->uuid !== $storedContext['brand_uuid']) {
            return null;
        }

        return $brand;
    }

    private function storeSelection(
        Request $request,
        User $user,
        Brand $brand,
        string $source
    ): AdminBrandContext {
        $selectedAt = CarbonImmutable::now();

        $request->session()->put($this->sessionKey(), [
            'brand_id' => (int) $brand->id,
            'brand_uuid' => (string) $brand->uuid,
            'user_id' => (int) $user->id,
            'source' => $source,
            'selected_at' => $selectedAt->toIso8601String(),
        ]);

        return $this->context($brand, $user, $source, $selectedAt);
    }

    private function context(
        Brand $brand,
        User $user,
        string $source,
        CarbonImmutable $selectedAt
    ): AdminBrandContext {
        return new AdminBrandContext(
            $brand,
            (int) $user->id,
            $source,
            $selectedAt,
            $this->accessResolver->hasActiveGlobalSuperAdminAssignment($user)
        );
    }

    private function parseSelectedAt(mixed $selectedAt): CarbonImmutable
    {
        if (!is_string($selectedAt) || trim($selectedAt) === '') {
            return CarbonImmutable::now();
        }

        try {
            return CarbonImmutable::parse($selectedAt);
        } catch (\Throwable) {
            return CarbonImmutable::now();
        }
    }

    private function sessionKey(): string
    {
        return (string) config(
            'brands.admin_context.session_key',
            'admin_brand_context'
        );
    }

    private function allowedSources(): array
    {
        return [
            'user_default',
            'single_accessible',
            'primary_brand',
            'deterministic_fallback',
            'explicit_switch',
        ];
    }
}
