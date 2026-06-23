@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('title', 'AKSHA Supporting Courses')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><div class="row align-items-center">
        <div class="col-sm-6"><h1>AKSHA Supporting Courses</h1></div>
        <div class="col-sm-6 text-sm-right"><a href="{{ route('admin::content.aksha.supporting-courses.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Course</a></div>
    </div></div></section>
    
    <section class="content"><div class="container-fluid"><div class="card cms-card">
        <div class="card-header border-bottom-0"><h3 class="card-title">Drag and drop rows to reorder. Changes save automatically.</h3></div>
        <div class="card-body p-0 table-responsive"><table class="table table-hover mb-0">
            <thead><tr><th style="width:50px;"></th><th>Course</th><th>Category</th><th>Status</th><th>Featured</th><th>Actions</th></tr></thead>
            <tbody id="sortable-list">
            @forelse ($courses as $course)
                <tr data-id="{{ $course->id }}" class="sortable-row" style="cursor: move;">
                    <td class="text-muted"><i class="fas fa-grip-vertical"></i></td>
                    <td class="cms-title-cell">
                        <div class="d-flex align-items-center">
                            @if($course->featured_image_media_id && $course->featuredImage)
                                <img src="{{ $course->featuredImage->url }}" alt="Img" style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin-right:10px;">
                            @else
                                <div style="width:40px;height:40px;border-radius:4px;margin-right:10px;background:#eee;display:flex;align-items:center;justify-content:center;color:#999;"><i class="fas fa-image"></i></div>
                            @endif
                            <div>
                                <strong>{{ $course->title }}</strong><br>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($course->short_description, 60) }}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-info">{{ $course->course_category ?? 'N/A' }}</span></td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input toggle-status" id="active_{{ $course->id }}" data-id="{{ $course->id }}" data-field="is_active" {{ $course->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="active_{{ $course->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input toggle-status" id="featured_{{ $course->id }}" data-id="{{ $course->id }}" data-field="is_featured" {{ $course->is_featured ? 'checked' : '' }}>
                            <label class="custom-control-label" for="featured_{{ $course->id }}"></label>
                        </div>
                    </td>
                    <td><div class="cms-actions">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin::content.aksha.supporting-courses.edit', $course->id) }}"><i class="fas fa-edit"></i> Edit</a>
                        <button class="btn btn-sm btn-outline-danger" type="button" data-cms-delete data-endpoint="{{ route('admin::aksha.supporting_courses.destroy', $course->id) }}"><i class="fas fa-trash"></i> Delete</button>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="cms-empty text-center py-4">No supporting courses found.</td></tr>
            @endforelse
            </tbody>
        </table></div>
    </div></div></section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('sortable-list');
    if (el) {
        new Sortable(el, {
            handle: '.sortable-row',
            animation: 150,
            onEnd: async function () {
                const items = Array.from(el.querySelectorAll('tr')).map((tr, index) => {
                    return { id: tr.dataset.id, sort_order: index + 1 };
                });
                
                try {
                    await fetch(@json(route('admin::aksha.supporting_courses.reorder')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ items })
                    });
                    toastr.success('Order saved successfully.');
                } catch (e) {
                    toastr.error('Failed to save order.');
                }
            }
        });
    }

    document.querySelectorAll('.toggle-status').forEach(input => {
        input.addEventListener('change', async function() {
            const id = this.dataset.id;
            const field = this.dataset.field;
            const value = this.checked ? 1 : 0;
            
            try {
                await fetch(`{{ url('v1/cpanel/admin/cms/aksha/supporting-courses') }}/${id}/toggle`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ field, value })
                });
                toastr.success('Updated successfully.');
            } catch (e) {
                toastr.error('Failed to update.');
                this.checked = !this.checked; // Revert
            }
        });
    });
});
</script>
@endpush
