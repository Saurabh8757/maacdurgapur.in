@extends('admin.layout.admin_layout')
@section('title', 'Placement Showcase')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Placement Showcase</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin::placement-showcases.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Placement</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="sortable-table">
                            <thead>
                                <tr>
                                    <th width="30">#</th>
                                    <th width="80">Image</th>
                                    <th>Student Name</th>
                                    <th>Company</th>
                                    <th>Designation</th>
                                    <th>Package</th>
                                    <th width="80">Active</th>
                                    <th width="80">Featured</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-list">
                                @forelse($placements as $placement)
                                    <tr data-id="{{ $placement->id }}">
                                        <td><i class="fas fa-arrows-alt handle" style="cursor: move;"></i></td>
                                        <td>
                                            @if($placement->studentImage)
                                                <img src="{{ asset('storage/' . $placement->studentImage->storage_key) }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                <div style="width: 50px; height: 50px; background: #eee; border-radius: 4px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user text-muted"></i></div>
                                            @endif
                                        </td>
                                        <td>{{ $placement->student_name }}</td>
                                        <td>{{ $placement->company ? $placement->company->name : $placement->company_name }}</td>
                                        <td>{{ $placement->designation }}</td>
                                        <td>₹{{ number_format($placement->annual_package) }}</td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input toggle-active" id="active_{{ $placement->id }}" data-id="{{ $placement->id }}" {{ $placement->is_active ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="active_{{ $placement->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input toggle-featured" id="featured_{{ $placement->id }}" data-id="{{ $placement->id }}" {{ $placement->is_featured ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="featured_{{ $placement->id }}"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin::placement-showcases.edit', $placement->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin::placement-showcases.destroy', $placement->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this placement?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No placement records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(function() {
    // Sorting
    $("#sortable-list").sortable({
        handle: ".handle",
        update: function(event, ui) {
            let order = [];
            $('#sortable-list tr').each(function() {
                order.push($(this).data('id'));
            });

            $.ajax({
                url: "{{ route('admin::placement-showcases.reorder') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    order: order
                },
                success: function(response) {
                    toastr.success('Order updated successfully.');
                },
                error: function() {
                    toastr.error('Failed to update order.');
                }
            });
        }
    });

    // Toggle Active
    $('.toggle-active').change(function() {
        let is_active = $(this).prop('checked') ? 1 : 0;
        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('admin::placement-showcases.toggle-active') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                is_active: is_active
            },
            success: function() {
                toastr.success('Status updated.');
            },
            error: function() {
                toastr.error('Failed to update status.');
            }
        });
    });

    // Toggle Featured
    $('.toggle-featured').change(function() {
        let is_featured = $(this).prop('checked') ? 1 : 0;
        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('admin::placement-showcases.toggle-featured') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                is_featured: is_featured
            },
            success: function() {
                toastr.success('Featured status updated.');
            },
            error: function() {
                toastr.error('Failed to update status.');
            }
        });
    });
});
</script>
@endsection
