@extends('admin.layout.admin_layout')
@section('content')
    <div class="content-wrapper" style="min-height: 357px;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Lead Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin::dashboard')}}">Dashboard</a> </li>
                            <li class="breadcrumb-item"><a href="{{route('admin::leads.index')}}">Leads</a> </li>
                            <li class="breadcrumb-item active">Lead Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->
        @include('admin.layout.message')
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Core Information</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 30%">Name</th>
                                        <td>{{ $lead->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $lead->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $lead->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Course</th>
                                        <td>{{ $lead->course_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td>{{ $lead->location }}</td>
                                    </tr>
                                    <tr>
                                        <th>Message</th>
                                        <td>{{ $lead->message }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        @if(!empty($lead->custom_data) && is_array($lead->custom_data))
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Dynamic Fields (Custom Data)</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    @foreach($lead->custom_data as $key => $value)
                                    <tr>
                                        <th style="width: 30%">{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                                        <td>
                                            @if(is_array($value))
                                                {{ implode(', ', $value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-md-4">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Tracking Information</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Brand:</strong> {{ $lead->brand ? $lead->brand->name : 'N/A' }}</p>
                                <p><strong>Source Page:</strong> {{ $lead->source_page }}</p>
                                <p><strong>Source URL:</strong> 
                                    @if($lead->source_url)
                                        <a href="{{ $lead->source_url }}" target="_blank">{{ $lead->source_url }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Submitted At:</strong> {{ \Carbon\Carbon::parse($lead->created_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</p>
                            </div>
                        </div>

                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Lead Management Actions</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin::leads.update_status', $lead->id) }}" method="POST" class="mb-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Change Status</label>
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <option value="new" {{ $lead->status == 'new' ? 'selected' : '' }}>New</option>
                                            <option value="contacted" {{ $lead->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                            <option value="follow_up" {{ $lead->status == 'follow_up' ? 'selected' : '' }}>Follow Up</option>
                                            <option value="converted" {{ $lead->status == 'converted' ? 'selected' : '' }}>Converted</option>
                                            <option value="closed" {{ $lead->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                </form>

                                <form action="{{ route('admin::leads.assign', $lead->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Assign To User</label>
                                        <select name="assigned_to" class="form-control" onchange="this.form.submit()">
                                            <option value="">-- Unassigned --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ $lead->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
