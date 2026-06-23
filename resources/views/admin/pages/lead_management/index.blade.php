@extends('admin.layout.admin_layout')
@section('content')
    <div class="content-wrapper" style="min-height: 357px;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Lead Management 
                            @if(request('status'))
                                - {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                            @else
                                - All Leads
                            @endif
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin::dashboard')}}">Dashboard</a> </li>
                            <li class="breadcrumb-item active">Leads</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->
        @include('admin.layout.message')
        <section class="content">
            <div class="container-fluid">
                <!-- Filters -->
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Filters</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin::leads.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Brand</label>
                                        <select name="brand_id" class="form-control">
                                            <option value="">All Brands</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All Statuses</option>
                                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                                            <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                            <option value="follow_up" {{ request('status') == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                                            <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ request('name') }}" placeholder="Search Name">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ request('phone') }}" placeholder="Search Phone">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Course</label>
                                        <input type="text" name="course_name" class="form-control" value="{{ request('course_name') }}" placeholder="Search Course">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Date From</label>
                                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Date To</label>
                                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('admin::leads.index') }}" class="btn btn-default">Reset</a>
                                    
                                    <div class="float-right">
                                        <button type="submit" formaction="{{ route('admin::leads.export.csv') }}" class="btn btn-success"><i class="fas fa-file-csv"></i> Export CSV</button>
                                        <button type="submit" formaction="{{ route('admin::leads.export.excel') }}" class="btn btn-info"><i class="fas fa-file-excel"></i> Export Excel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Leads List</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Brand</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Course</th>
                                        <th>Source</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Submitted At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($leads as $lead)
                                        <tr class="clickable-row" data-href="{{ route('admin::leads.show', $lead->id) }}" style="cursor: pointer;">
                                            <td>{{ $lead->id }}</td>
                                            <td>{{ $lead->brand ? $lead->brand->name : 'N/A' }}</td>
                                            <td>{{ $lead->name }}</td>
                                            <td>{{ $lead->phone }}</td>
                                            <td>{{ $lead->course_name }}</td>
                                            <td>
                                                @if($lead->source_url)
                                                    <a href="{{ $lead->source_url }}" target="_blank">{{ $lead->source_page ?: 'Link' }}</a>
                                                @else
                                                    {{ $lead->source_page }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($lead->status == 'new')
                                                    <span class="badge badge-info">New</span>
                                                @elseif($lead->status == 'contacted')
                                                    <span class="badge badge-primary">Contacted</span>
                                                @elseif($lead->status == 'follow_up')
                                                    <span class="badge badge-warning">Follow Up</span>
                                                @elseif($lead->status == 'converted')
                                                    <span class="badge badge-success">Converted</span>
                                                @elseif($lead->status == 'closed')
                                                    <span class="badge badge-danger">Closed</span>
                                                @endif
                                            </td>
                                            <td>{{ $lead->assignedUser ? $lead->assignedUser->name : 'Unassigned' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($lead->created_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
                                            <td>
                                                <a href="{{ route('admin::leads.show', $lead->id) }}" class="btn btn-sm btn-primary">View</a>
                                                
                                                <form action="{{ route('admin::leads.destroy', $lead->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this lead?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No leads found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {{ $leads->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function($) {
        $(".clickable-row").click(function(e) {
            // Prevent click if clicking on a button, link, or form element
            if (!$(e.target).closest('a, button, form, input, select').length) {
                window.location = $(this).data("href");
            }
        });
    });
</script>
@endpush
