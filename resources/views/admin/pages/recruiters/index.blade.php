@extends('admin.layout.admin_layout')
@section('title', 'Recruiters')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Recruiters</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin::recruiters.create') }}" class="btn btn-primary">Add New Recruiter</a>
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
                <div class="card-body p-0">
                    <table class="table table-striped" id="recruitersTable">
                        <thead>
                            <tr>
                                <th>Drag</th>
                                <th>Logo</th>
                                <th>Company Name</th>
                                <th>Website</th>
                                <th>Featured</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recruiters as $recruiter)
                            <tr data-id="{{ $recruiter->id }}">
                                <td style="cursor: grab;"><i class="fas fa-grip-vertical text-muted"></i></td>
                                <td>
                                    @if($recruiter->logo)
                                        <img src="{{ asset('storage/' . $recruiter->logo->storage_key) }}" alt="{{ $recruiter->company_name }}" width="50">
                                    @else
                                        <span class="badge badge-secondary">Text Only</span>
                                    @endif
                                </td>
                                <td>{{ $recruiter->company_name }}</td>
                                <td>{{ $recruiter->company_website ?? 'N/A' }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input toggle-featured" id="featured_{{ $recruiter->id }}" data-id="{{ $recruiter->id }}" {{ $recruiter->is_featured ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="featured_{{ $recruiter->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input toggle-active" id="active_{{ $recruiter->id }}" data-id="{{ $recruiter->id }}" {{ $recruiter->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="active_{{ $recruiter->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin::recruiters.edit', $recruiter->id) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</a>
                                    <form action="{{ route('admin::recruiters.destroy', $recruiter->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this recruiter?');"><i class="fas fa-trash"></i> Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($recruiters->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center">No recruiters found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
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
    // Drag & Drop Sorting
    $("#recruitersTable tbody").sortable({
        helper: function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },
        update: function(event, ui) {
            let order = [];
            $('#recruitersTable tbody tr').each(function() {
                order.push($(this).data('id'));
            });

            $.ajax({
                url: "{{ route('admin::recruiters.reorder') }}",
                type: "POST",
                data: {
                    order: order,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    toastr.success('Order updated successfully.');
                },
                error: function() {
                    toastr.error('Failed to update order.');
                }
            });
        }
    }).disableSelection();

    // Toggle Active
    $('.toggle-active').change(function() {
        let is_active = $(this).prop('checked') ? 1 : 0;
        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('admin::recruiters.toggle-active') }}",
            type: "POST",
            data: {
                id: id,
                is_active: is_active,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                toastr.success('Status updated successfully.');
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
            url: "{{ route('admin::recruiters.toggle-featured') }}",
            type: "POST",
            data: {
                id: id,
                is_featured: is_featured,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                toastr.success('Featured status updated successfully.');
            },
            error: function() {
                toastr.error('Failed to update featured status.');
            }
        });
    });
});
</script>
@endsection
