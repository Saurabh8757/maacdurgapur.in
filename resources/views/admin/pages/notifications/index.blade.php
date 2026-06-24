@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Notification Center</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <form action="{{ route('admin::notifications.markAllRead') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-primary"><i class="fas fa-check-double"></i> Mark All as Read</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Filter Box -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Filters</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin::notifications.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search Title or Message" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary w-100">Filter</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin::notifications.index') }}" class="btn btn-default w-100">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="card">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($notifications as $notif)
                        <li class="list-group-item {{ !$notif->is_read ? 'bg-light font-weight-bold' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">
                                        @if($notif->icon) <i class="{{ $notif->icon }} mr-2 text-{{ $notif->color ?? 'primary' }}"></i> @endif
                                        {{ $notif->title }}
                                    </h5>
                                    <p class="mb-1 text-muted">{{ $notif->message }}</p>
                                    <small class="text-muted"><i class="far fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="text-right d-flex justify-content-end align-items-center">
                                    @if(!$notif->is_read)
                                        <button class="btn btn-sm btn-success mark-read-btn mr-1" data-id="{{ $notif->id }}">Mark Read</button>
                                    @endif
                                    @if($notif->action_url)
                                        <a href="{{ $notif->action_url }}" class="btn btn-sm btn-outline-primary mr-1">View</a>
                                    @endif
                                    <form action="{{ route('admin::notifications.destroy', $notif->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this notification?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-4">
                            <span class="text-muted">No notifications found.</span>
                        </li>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer">
                    {{ $notifications->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.mark-read-btn').click(function() {
            var btn = $(this);
            var id = btn.data('id');
            $.ajax({
                url: "{{ url('admin/notifications') }}/" + id + "/read",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.success) {
                        btn.closest('.list-group-item').removeClass('bg-light font-weight-bold');
                        btn.remove();
                        toastr.success('Notification marked as read');
                    }
                }
            });
        });
    });
</script>
@endpush
