<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadFormField;
use App\Models\Brand;
use Illuminate\Http\Request;

class LeadFormController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::all();
        $brand_id = $request->get('brand_id', $brands->first()->id ?? null);
        
        $form_type = $request->get('form_type', 'hero');
        
        $fields = LeadFormField::where('brand_id', $brand_id)
            ->where('form_type', $form_type)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('admin.pages.lead_forms.index', compact('fields', 'brands', 'brand_id', 'form_type'));
    }

    public function create(Request $request)
    {
        $brands = Brand::all();
        $brand_id = $request->get('brand_id');
        return view('admin.pages.lead_forms.create', compact('brands', 'brand_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'form_type' => 'required|in:hero,global_modal',
            'label' => 'required|string|max:255',
            'field_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/',
            'type' => 'required|in:text,email,phone,select,textarea,checkbox',
            'placeholder' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'options' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['is_required'] = $request->has('is_required');
        $data['is_active'] = $request->has('is_active');
        
        // Handle options array
        if ($request->type === 'select' && $request->filled('options')) {
            $optionsArray = array_map('trim', explode(',', $request->options));
            $data['options'] = json_encode($optionsArray);
        } else {
            $data['options'] = null;
        }

        LeadFormField::create($data);

        return redirect()->route('admin::lead_forms.index', ['brand_id' => $request->brand_id])
            ->with('success', 'Field created successfully.');
    }

    public function edit($id)
    {
        $field = LeadFormField::findOrFail($id);
        $brands = Brand::all();
        
        $optionsString = '';
        if ($field->options) {
            $optionsString = implode(', ', json_decode($field->options, true) ?? []);
        }

        return view('admin.pages.lead_forms.edit', compact('field', 'brands', 'optionsString'));
    }

    public function update(Request $request, $id)
    {
        $field = LeadFormField::findOrFail($id);

        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'form_type' => 'required|in:hero,global_modal',
            'label' => 'required|string|max:255',
            'field_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_]+$/',
            'type' => 'required|in:text,email,phone,select,textarea,checkbox',
            'placeholder' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'options' => 'nullable|string'
        ]);

        $data = $request->all();
        $data['is_required'] = $request->has('is_required');
        $data['is_active'] = $request->has('is_active');

        if ($request->type === 'select' && $request->filled('options')) {
            $optionsArray = array_map('trim', explode(',', $request->options));
            $data['options'] = json_encode($optionsArray);
        } else {
            $data['options'] = null;
        }

        $field->update($data);

        return redirect()->route('admin::lead_forms.index', ['brand_id' => $request->brand_id])
            ->with('success', 'Field updated successfully.');
    }

    public function destroy($id)
    {
        $field = LeadFormField::findOrFail($id);
        $brand_id = $field->brand_id;
        $field->delete();

        return redirect()->route('admin::lead_forms.index', ['brand_id' => $brand_id])
            ->with('success', 'Field deleted successfully.');
    }
}
