<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkshaMajorProgram;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class AkshaMajorProgramController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:aksha_major_programs',
            'short_description' => 'required|string',
            'outcome' => 'nullable|string|max:255',
            'featured_image_media_id' => 'nullable|exists:media_assets,id',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'sort_order' => 'integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['brand_id'] = \App\Models\Brand::first()->id ?? 1;
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        $program = AkshaMajorProgram::create($data);

        app(\App\Services\NotificationService::class)->sendToBrand(
            null, // AKSHA might be global
            'AKSHA Content Added',
            'New AKSHA content has been added.',
            'info',
            'Aksha',
            null,
            null,
            'fas fa-book',
            'info'
        );

        return response()->json($program, 201);
    }

    public function update(Request $request, AkshaMajorProgram $major_program): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:aksha_major_programs,slug,' . $major_program->id,
            'short_description' => 'required|string',
            'outcome' => 'nullable|string|max:255',
            'featured_image_media_id' => 'nullable|exists:media_assets,id',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'sort_order' => 'integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        $major_program->update($data);
        return response()->json($major_program);
    }

    public function destroy(AkshaMajorProgram $major_program): JsonResponse
    {
        $major_program->delete();
        return response()->json(null, 204);
    }

    public function toggleStatus(Request $request, AkshaMajorProgram $major_program): JsonResponse
    {
        $request->validate([
            'field' => 'required|in:is_active,is_featured',
            'value' => 'required|boolean'
        ]);

        $major_program->update([$request->field => $request->value]);
        return response()->json(['message' => 'Status updated successfully']);
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:aksha_major_programs,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            AkshaMajorProgram::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
