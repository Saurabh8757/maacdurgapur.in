<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\ViewSettingsRequest;
use App\Models\User;
use App\Services\Settings\Exceptions\SettingsAuthorizationException;
use App\Services\Settings\Exceptions\SettingsScopeException;
use App\Services\Settings\SettingsReadService;
use App\Services\Settings\SettingsScopeResolver;
use Illuminate\Contracts\View\View;

class SettingsController extends Controller
{
    public function brand(
        ViewSettingsRequest $request,
        SettingsScopeResolver $scopeResolver,
        SettingsReadService $readService
    ): View {
        return $this->render(
            $request,
            fn () => $scopeResolver->brandScope($request->validated('locale')),
            $readService
        );
    }

    public function global(
        ViewSettingsRequest $request,
        SettingsScopeResolver $scopeResolver,
        SettingsReadService $readService
    ): View {
        return $this->render(
            $request,
            fn () => $scopeResolver->globalScope($request->validated('locale')),
            $readService
        );
    }

    private function render(
        ViewSettingsRequest $request,
        callable $scopeFactory,
        SettingsReadService $readService
    ): View {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        try {
            $scope = $scopeFactory();
            $catalogue = $readService->catalogue(
                $user,
                $scope,
                $request->validated('group')
            );
        } catch (SettingsAuthorizationException | SettingsScopeException) {
            abort(403);
        }

        return view('admin.pages.settings.index', [
            ...$catalogue,
            'supportedLocales' => config(
                'brands.settings.supported_locales',
                ['en']
            ),
        ]);
    }
}
