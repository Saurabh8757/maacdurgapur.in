<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AkshaSupportingCourse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class AkshaSupportingCourseController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:aksha_supporting_courses',
            'course_category' => 'nullable|string|max:255',
            'short_description' => 'required|string',
            'outcome' => 'nullable|string|max:255',
            'featured_image_media_id' => 'nullable|exists:media_assets,id',
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:100',
            'sort_order' => 'integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['brand_id'] = \App\Models\Brand::where('slug', 'aksha')->first()->id ?? 1;
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        $course = AkshaSupportingCourse::create($data);

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

        return response()->json($course, 201);
    }

    public function update(Request $request, AkshaSupportingCourse $supporting_course): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:aksha_supporting_courses,slug,' . $supporting_course->id,
            'course_category' => 'nullable|string|max:255',
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

        $supporting_course->update($data);
        return response()->json($supporting_course);
    }

    public function destroy(AkshaSupportingCourse $supporting_course): JsonResponse
    {
        $supporting_course->delete();
        return response()->json(null, 204);
    }

    public function toggleStatus(Request $request, AkshaSupportingCourse $supporting_course): JsonResponse
    {
        $request->validate([
            'field' => 'required|in:is_active,is_featured',
            'value' => 'required|boolean'
        ]);

        $supporting_course->update([$request->field => $request->value]);
        return response()->json(['message' => 'Status updated successfully']);
    }

    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:aksha_supporting_courses,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            AkshaSupportingCourse::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
