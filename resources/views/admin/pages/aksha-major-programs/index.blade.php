@extends('admin.layout.admin_layout')
@include('admin.cms.partials.page-assets')

@section('title', 'AKSHA Major Programs')

@section('content')
<div class="content-wrapper cms-page">
    <section class="content-header"><div class="container-fluid"><div class="row align-items-center">
        <div class="col-sm-6"><h1>AKSHA Major Programs</h1></div>
        <div class="col-sm-6 text-sm-right"><a href="{{ route('admin::content.aksha.major-programs.create') }}" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add Program</a></div>
    </div></div></section>
    
    <section class="content"><div class="container-fluid"><div class="card cms-card">
        <div class="card-header border-bottom-0"><h3 class="card-title">Drag and drop rows to reorder. Changes save automatically.</h3></div>
        <div class="card-body p-0 table-responsive"><table class="table table-hover mb-0">
            <thead><tr><th style="width:50px;"></th><th>Program</th><th>Slug</th><th>Status</th><th>Featured</th><th>Actions</th></tr></thead>
            <tbody id="sortable-list">
            @forelse ($programs as $program)
                <tr data-id="{{ $program->id }}" class="sortable-row" style="cursor: move;">
                    <td class="text-muted"><i class="fas fa-grip-vertical"></i></td>
                    <td class="cms-title-cell">
                        <div class="d-flex align-items-center">
                            @if($program->featured_image_media_id && $program->featuredImage)
                                <img src="{{ $program->featuredImage->url }}" alt="Img" style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin-right:10px;">
                            @else
                                <div style="width:40px;height:40px;border-radius:4px;margin-right:10px;background:#eee;display:flex;align-items:center;justify-content:center;color:#999;"><i class="fas fa-image"></i></div>
                            @endif
                            <div>
                                <strong>{{ $program->title }}</strong><br>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($program->short_description, 60) }}</small>
                            </div>
                        </div>
                    </td>
                    <td><code>{{ $program->slug }}</code></td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input toggle-status" id="active_{{ $program->id }}" data-id="{{ $program->id }}" data-field="is_active" {{ $program->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="active_{{ $program->id }}"></label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input toggle-status" id="featured_{{ $program->id }}" data-id="{{ $program->id }}" data-field="is_featured" {{ $program->is_featured ? 'checked' : '' }}>
                            <label class="custom-control-label" for="featured_{{ $program->id }}"></label>
                        </div>
                    </td>
                    <td><div class="cms-actions">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin::content.aksha.major-programs.edit', $program->id) }}"><i class="fas fa-edit"></i> Edit</a>
                        <button class="btn btn-sm btn-outline-danger" type="button" data-cms-delete data-endpoint="{{ route('admin::aksha.major_programs.destroy', $program->id) }}"><i class="fas fa-trash"></i> Delete</button>
                    </div></td>
                </tr>
            @empty
                <tr><td colspan="6" class="cms-empty text-center py-4">No programs found.</td></tr>
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
                    await fetch(@json(route('admin::aksha.major_programs.reorder')), {
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
                await fetch(`{{ url('v1/cpanel/admin/cms/aksha/major-programs') }}/${id}/toggle`, {
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
