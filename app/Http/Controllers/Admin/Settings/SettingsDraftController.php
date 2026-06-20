<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\ResetSettingOverrideRequest;
use App\Http\Requests\Admin\Settings\UpdateSettingsRequest;
use App\Models\SettingDefinition;
use App\Services\Settings\SettingsAuthorizationService;
use App\Services\Settings\SettingsDraftService;
use App\Services\Settings\SettingsScopeResolver;
use Illuminate\Http\JsonResponse;

class SettingsDraftController extends Controller
{
    public function __construct(
        private SettingsScopeResolver $scopeResolver,
        private SettingsAuthorizationService $authorizationService,
        private SettingsDraftService $draftService
    ) {
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $scope = $this->scopeResolver->resolve('brand');
        $user = $request->user();
        
        $definition = SettingDefinition::where('key', $request->input('definition_key'))->firstOrFail();

        $this->authorizationService->authorizeDefinition($user, $scope, $definition, 'edit');

        $draft = $this->draftService->updateDraft(
            user: $user,
            scope: $scope,
            definition: $definition,
            value: $request->input('value'),
            expectedUpdatedAt: $request->input('updated_at')
        );

        return response()->json([
            'message' => 'Draft updated successfully',
            'data' => $draft,
        ]);
    }

    public function resetOverride(ResetSettingOverrideRequest $request, string $definition): JsonResponse
    {
        $scope = $this->scopeResolver->resolve('brand');
        $user = $request->user();

        $settingDef = SettingDefinition::where('key', $definition)->firstOrFail();

        $this->authorizationService->authorizeDefinition($user, $scope, $settingDef, 'edit');

        $this->draftService->resetOverride(
            user: $user,
            scope: $scope,
            definition: $settingDef
        );

        return response()->json([
            'message' => 'Brand override reset successfully',
        ]);
    }
}
