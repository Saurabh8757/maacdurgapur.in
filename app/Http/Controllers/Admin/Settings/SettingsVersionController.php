<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\SettingDefinition;
use App\Services\Settings\SettingsAuthorizationService;
use App\Services\Settings\SettingsScopeResolver;
use App\Services\Settings\SettingsVersionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsVersionController extends Controller
{
    public function __construct(
        private SettingsScopeResolver $scopeResolver,
        private SettingsAuthorizationService $authorizationService,
        private SettingsVersionService $versionService
    ) {
    }

    public function index(Request $request, string $definitionKey): JsonResponse
    {
        $scopeKey = $request->route()->named('settings.global.versions.index') ? 'global' : 'brand';
        $scope = $this->scopeResolver->resolve($scopeKey);
        $user = $request->user();

        $definition = SettingDefinition::where('key', $definitionKey)->firstOrFail();

        $this->authorizationService->authorizeDefinition($user, $scope, $definition, 'view');
        $canViewSensitive = $this->authorizationService->allows($user, $scope, 'view_sensitive');

        $versions = $this->versionService->getHistory($scope, $definition, $canViewSensitive);

        return response()->json([
            'data' => $versions->items(),
            'meta' => [
                'current_page' => $versions->currentPage(),
                'last_page' => $versions->lastPage(),
                'per_page' => $versions->perPage(),
                'total' => $versions->total(),
            ],
        ]);
    }

    public function show(Request $request, string $definitionKey, int $versionNumber): JsonResponse
    {
        $scopeKey = $request->route()->named('settings.global.versions.show') ? 'global' : 'brand';
        $scope = $this->scopeResolver->resolve($scopeKey);
        $user = $request->user();

        $definition = SettingDefinition::where('key', $definitionKey)->firstOrFail();

        $this->authorizationService->authorizeDefinition($user, $scope, $definition, 'view');
        $canViewSensitive = $this->authorizationService->allows($user, $scope, 'view_sensitive');

        $version = $this->versionService->getVersion($scope, $definition, $versionNumber, $canViewSensitive);

        return response()->json([
            'data' => $version,
        ]);
    }
}
