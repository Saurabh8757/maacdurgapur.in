<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recruiter;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RecruiterController extends Controller
{
    public function index()
    {
        $recruiters = Recruiter::with(['logo'])
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.recruiters.index', compact('recruiters'));
    }

    public function create()
    {
        return view('admin.pages.recruiters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'company_website' => 'nullable|url|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except(['_token', 'company_logo']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['sort_order'] = $request->input('sort_order', 0);
        $data['css_class'] = Str::slug($request->company_name);

        if ($request->hasFile('company_logo')) {
            $data['company_logo_media_id'] = $this->uploadImage($request->file('company_logo'), 'recruiters/logos');
        }

        Recruiter::create($data);

        return redirect()->route('admin::recruiters.index')->with('success', 'Recruiter created successfully.');
    }

    public function edit(Recruiter $recruiter)
    {
        return view('admin.pages.recruiters.edit', compact('recruiter'));
    }

    public function update(Request $request, Recruiter $recruiter)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'company_website' => 'nullable|url|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->except(['_token', '_method', 'company_logo']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $data['css_class'] = Str::slug($request->company_name);

        if ($request->hasFile('company_logo')) {
            $data['company_logo_media_id'] = $this->uploadImage($request->file('company_logo'), 'recruiters/logos');
        }

        $recruiter->update($data);

        return redirect()->route('admin::recruiters.index')->with('success', 'Recruiter updated successfully.');
    }

    public function destroy(Recruiter $recruiter)
    {
        $recruiter->delete();
        return redirect()->route('admin::recruiters.index')->with('success', 'Recruiter deleted successfully.');
    }

    public function toggleActive(Request $request)
    {
        $recruiter = Recruiter::find($request->id);
        if ($recruiter) {
            $recruiter->is_active = $request->is_active;
            $recruiter->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function toggleFeatured(Request $request)
    {
        $recruiter = Recruiter::find($request->id);
        if ($recruiter) {
            $recruiter->is_featured = $request->is_featured;
            $recruiter->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');
        if (is_array($order)) {
            foreach ($order as $index => $id) {
                Recruiter::where('id', $id)->update(['sort_order' => $index + 1]);
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
            'uploaded_by' => Auth::id() ?? 1,
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
