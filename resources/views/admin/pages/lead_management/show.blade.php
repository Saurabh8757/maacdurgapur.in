@extends('admin.layout.admin_layout')
@section('content')
    <div class="content-wrapper" style="min-height: 357px;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            Lead Details
                            @php
                                $lastWa = $lead->whatsappMessages()->latest()->first();
                            @endphp
                            @if($lastWa)
                                <span class="badge badge-{{ $lastWa->status == 'read' ? 'success' : ($lastWa->status == 'failed' ? 'danger' : 'info') }} ml-2" style="font-size: 0.5em;">
                                    WA: {{ ucfirst($lastWa->status) }}
                                </span>
                            @endif
                        </h1>
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

                        <!-- CRM Timeline Section -->
                        <div class="card card-default mt-4">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-history"></i> CRM Timeline</h3>
                            </div>
                            <div class="card-body">
                                <!-- Add Note Form -->
                                <form action="{{ route('admin::leads.add_note', $lead->id) }}" method="POST" class="mb-4">
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="note" class="form-control" rows="3" placeholder="Add a note..." required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" id="is_pinned" name="is_pinned" value="1">
                                            <label for="is_pinned" class="custom-control-label">Pin this note</label>
                                        </div>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Note</button>
                                    </div>
                                </form>
                                <hr>

                                <!-- Timeline -->
                                <div class="timeline">
                                    @forelse($lead->activities as $activity)
                                        <!-- timeline time label -->
                                        @if($loop->first || $activity->created_at->format('d M. Y') !== $lead->activities[$loop->index - 1]->created_at->format('d M. Y'))
                                            <div class="time-label">
                                                <span class="{{ $activity->created_at->isToday() ? 'bg-success' : 'bg-secondary' }} text-white">
                                                    {{ $activity->created_at->format('d M. Y') }}
                                                </span>
                                            </div>
                                        @endif
                                        <!-- /.timeline-label -->
                                        
                                        <!-- timeline item -->
                                        <div>
                                            @php
                                                $icon = 'fas fa-info';
                                                $bgClass = 'bg-primary';
                                                switch($activity->activity_type) {
                                                    case 'LEAD_CREATED': $icon = 'fas fa-user-plus'; $bgClass = 'bg-success'; break;
                                                    case 'STATUS_CHANGED': $icon = 'fas fa-sync'; $bgClass = 'bg-info'; break;
                                                    case 'ASSIGNED': $icon = 'fas fa-user-check'; $bgClass = 'bg-warning'; break;
                                                    case 'NOTE_ADDED': $icon = 'fas fa-comment'; $bgClass = 'bg-secondary'; break;
                                                    case 'WHATSAPP_SENT': $icon = 'fab fa-whatsapp'; $bgClass = 'bg-success'; break;
                                                    case 'EMAIL_SENT': $icon = 'fas fa-envelope'; $bgClass = 'bg-primary'; break;
                                                    case 'CONVERTED': $icon = 'fas fa-trophy'; $bgClass = 'bg-success'; break;
                                                    case 'CLOSED': $icon = 'fas fa-ban'; $bgClass = 'bg-danger'; break;
                                                }
                                                if($activity->is_pinned) {
                                                    $bgClass = 'bg-warning';
                                                    $icon = 'fas fa-thumbtack';
                                                }
                                            @endphp
                                            <i class="{{ $icon }} {{ $bgClass }}"></i>
                                            <div class="timeline-item {{ $activity->is_pinned ? 'border border-warning' : '' }}">
                                                <span class="time"><i class="fas fa-clock"></i> {{ $activity->created_at->format('h:i A') }}</span>
                                                <h3 class="timeline-header">
                                                    <strong>{{ $activity->title }}</strong>
                                                    @if($activity->user)
                                                        <span class="text-muted text-sm ml-2">by {{ $activity->user->name }}</span>
                                                    @endif
                                                    @if($activity->is_pinned)
                                                        <span class="badge badge-warning ml-2">Pinned</span>
                                                    @endif
                                                </h3>
                                                @if($activity->description)
                                                    <div class="timeline-body">
                                                        {{ $activity->description }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- END timeline item -->
                                    @empty
                                        <div>
                                            <i class="fas fa-clock bg-gray"></i>
                                            <div class="timeline-item">
                                                <span class="time"></span>
                                                <h3 class="timeline-header no-border">No activities recorded yet.</h3>
                                            </div>
                                        </div>
                                    @endforelse
                                    <div>
                                        <i class="fas fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
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

                        <!-- Followup Card -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Schedule Followup</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin::leads.followups.store', $lead->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label>Date *</label>
                                        <input type="date" name="followup_date" class="form-control" required min="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Time (Optional)</label>
                                        <input type="time" name="followup_time" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Assign To User</label>
                                        <select name="assigned_user_id" class="form-control">
                                            <option value="">-- Me / Default --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Remarks *</label>
                                        <textarea name="remarks" class="form-control" rows="2" placeholder="What to discuss..." required></textarea>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" type="checkbox" id="send_whatsapp_reminder" name="send_whatsapp_reminder" value="1">
                                        <label for="send_whatsapp_reminder" class="custom-control-label">Send WhatsApp Reminder</label>
                                    </div>
                                    <button type="submit" class="btn btn-info btn-block"><i class="fas fa-calendar-plus"></i> Create Followup</button>
                                </form>
                            </div>
                        </div>

                        <!-- WhatsApp Actions Card -->
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fab fa-whatsapp"></i> WhatsApp Actions</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-column gap-2">
                                    <button class="btn btn-success btn-sm mb-2" onclick="alert('Quick Message feature coming soon.')"><i class="fas fa-paper-plane"></i> Send Message</button>
                                    <button class="btn btn-outline-success btn-sm mb-2" onclick="alert('Resend feature coming soon.')"><i class="fas fa-sync"></i> Resend Last Message</button>
                                    <a href="#timeline" class="btn btn-outline-secondary btn-sm" onclick="document.querySelector('.timeline').scrollIntoView();"><i class="fas fa-history"></i> View History</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
