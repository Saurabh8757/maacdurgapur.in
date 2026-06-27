@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Followups</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Filters -->
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Filter Followups</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin::followups.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="missed" {{ request('status') == 'missed' ? 'selected' : '' }}>Missed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Assigned User</label>
                            <select name="assigned_user_id" class="form-control">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('assigned_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Lead Name</th>
                                <th>Phone</th>
                                <th>Assigned To</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($followups as $followup)
                            <tr>
                                <td>
                                    @if($followup->lead)
                                        <a href="{{ route('admin::leads.show', $followup->lead->id) }}">{{ $followup->lead->name }}</a>
                                    @else
                                        <span class="text-muted">Deleted / missing lead</span>
                                    @endif
                                </td>
                                <td>{{ optional($followup->lead)->phone ?? 'N/A' }}</td>
                                <td>{{ $followup->assignedUser->name ?? 'Unassigned' }}</td>
                                <td>
                                    <strong>{{ $followup->followup_date->format('d M Y') }}</strong><br>
                                    <small>{{ $followup->followup_time ? \Carbon\Carbon::parse($followup->followup_time)->format('h:i A') : 'Anytime' }}</small>
                                </td>
                                <td>
                                    @if($followup->status == 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($followup->status == 'completed')
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($followup->status == 'missed')
                                        <span class="badge badge-danger">Missed</span>
                                    @else
                                        <span class="badge badge-secondary">Cancelled</span>
                                    @endif
                                </td>
                                <td style="white-space: normal; min-width: 200px;">{{ Str::limit($followup->remarks, 50) }}</td>
                                <td>
                                    @if($followup->status == 'pending' || $followup->status == 'missed')
                                        <form action="{{ route('admin::followups.complete', $followup->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success" title="Mark Complete"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('admin::followups.cancel', $followup->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Cancel Followup"><i class="fas fa-times"></i></button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin::followups.destroy', $followup->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this followup permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Followup"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No followups found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $followups->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
