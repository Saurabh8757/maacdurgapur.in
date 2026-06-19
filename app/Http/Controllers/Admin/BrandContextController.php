<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SwitchBrandContextRequest;
use App\Models\User;
use App\Services\Brands\AdminBrandContextResolver;
use Illuminate\Http\RedirectResponse;

class BrandContextController extends Controller
{
    public function switch(
        SwitchBrandContextRequest $request,
        AdminBrandContextResolver $resolver
    ): RedirectResponse {
        $user = $request->user();

        if (
            !$user instanceof User
            || !$resolver->select(
                $request,
                $user,
                (string) $request->validated('brand_uuid')
            )
        ) {
            abort(403);
        }

        return redirect()->route('admin::dashboard');
    }
}
