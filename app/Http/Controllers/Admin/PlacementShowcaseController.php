<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlacementShowcase;
use App\Models\Company;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlacementShowcaseController extends Controller
{
    public function index()
    {
        $placements = PlacementShowcase::with(['company', 'studentImage', 'companyLogo'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.placement-showcases.index', compact('placements'));
    }

    public function create()
    {
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.placement-showcases.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'company_name' => 'nullable|string|max:255',
            'designation' => 'required|string|max:255',
            'annual_package' => 'required|numeric|min:0',
            'student_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except(['_token', 'student_image', 'company_logo']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->input('sort_order', 0);

        // Handle new company creation if custom name provided instead of ID
        if (!$request->filled('company_id') && $request->filled('company_name')) {
            $company = Company::firstOrCreate(['name' => $request->company_name]);
            $data['company_id'] = $company->id;
        }

        if ($request->hasFile('student_image')) {
            $data['student_image_media_id'] = $this->uploadImage($request->file('student_image'), 'placements/students');
        }

        if ($request->hasFile('company_logo')) {
            $data['company_logo_media_id'] = $this->uploadImage($request->file('company_logo'), 'placements/companies');
        }

        PlacementShowcase::create($data);

        return redirect()->route('admin::placement-showcases.index')->with('success', 'Placement Showcase created successfully.');
    }

    public function edit(PlacementShowcase $placementShowcase)
    {
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        return view('admin.pages.placement-showcases.edit', compact('placementShowcase', 'companies'));
    }

    public function update(Request $request, PlacementShowcase $placementShowcase)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'company_name' => 'nullable|string|max:255',
            'designation' => 'required|string|max:255',
            'annual_package' => 'required|numeric|min:0',
            'student_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except(['_token', '_method', 'student_image', 'company_logo']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        
        if (!$request->filled('company_id') && $request->filled('company_name')) {
            $company = Company::firstOrCreate(['name' => $request->company_name]);
            $data['company_id'] = $company->id;
        }

        if ($request->hasFile('student_image')) {
            $data['student_image_media_id'] = $this->uploadImage($request->file('student_image'), 'placements/students');
        }

        if ($request->hasFile('company_logo')) {
            $data['company_logo_media_id'] = $this->uploadImage($request->file('company_logo'), 'placements/companies');
        }

        $placementShowcase->update($data);

        return redirect()->route('admin::placement-showcases.index')->with('success', 'Placement Showcase updated successfully.');
    }

    public function destroy(PlacementShowcase $placementShowcase)
    {
        $placementShowcase->delete();
        return redirect()->route('admin::placement-showcases.index')->with('success', 'Placement Showcase deleted successfully.');
    }

    public function toggleActive(Request $request)
    {
        $placement = PlacementShowcase::find($request->id);
        if ($placement) {
            $placement->is_active = $request->is_active;
            $placement->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function toggleFeatured(Request $request)
    {
        $placement = PlacementShowcase::find($request->id);
        if ($placement) {
            $placement->is_featured = $request->is_featured;
            $placement->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');
        if (is_array($order)) {
            foreach ($order as $index => $id) {
                PlacementShowcase::where('id', $id)->update(['sort_order' => $index + 1]);
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    private function uploadImage($file, $folderPath): int
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $extension;
        $path = $file->storeAs($folderPath, $filename, 'public');

        $asset = MediaAsset::create([
            'uploaded_by' => Auth::id() ?? 1, // fallback for seeder/console
            'storage_disk' => 'public',
            'storage_key' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'display_name' => $file->getClientOriginalName(),
            'extension' => $extension,
            'mime_type' => $file->getMimeType(),
            'media_type' => 'image',
            'size_bytes' => $file->getSize(),
            'checksum_sha256' => hash_file('sha256', $file->getRealPath()),
            'visibility' => 'public',
            'status' => 'ready',
        ]);

        return $asset->id;
    }
}
