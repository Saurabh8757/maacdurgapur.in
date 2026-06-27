@extends('admin.layout.admin_layout')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Analytics Dashboard</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin::dashboard.export') }}" class="btn btn-primary">
                        <i class="fas fa-download"></i> Export Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Top Widgets -->
            <div class="row">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $conversionMetrics['total_leads'] }}</h3>
                            <p>Total Leads</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $conversionMetrics['new_today'] }}</h3>
                            <p>New Today</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-plus"></i></div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $conversionMetrics['conversion_rate'] }}<sup style="font-size: 20px">%</sup></h3>
                            <p>Conversion Rate</p>
                        </div>
                        <div class="icon"><i class="fas fa-chart-line"></i></div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box {{ $conversionMetrics['growth_percent'] >= 0 ? 'bg-success' : 'bg-danger' }}">
                        <div class="inner">
                            <h3>{{ $conversionMetrics['growth_percent'] > 0 ? '+' : '' }}{{ $conversionMetrics['growth_percent'] }}<sup style="font-size: 20px">%</sup></h3>
                            <p>Lead Growth (30d)</p>
                        </div>
                        <div class="icon"><i class="fas fa-level-up-alt"></i></div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $followupStats['pending'] }}</h3>
                            <p>Pending Followups</p>
                        </div>
                        <div class="icon"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $followupStats['overdue'] }}</h3>
                            <p>Overdue Followups</p>
                        </div>
                        <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Lead Trend (Last 30 Days)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="leadTrendChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Lead Status Funnel</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="statusFunnelChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Brand Performance (Leads & Converted)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="brandPerformanceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Lead Source Breakdown</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="sourceBreakdownChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Counsellor & WhatsApp -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Counsellor Performance</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Assigned Leads</th>
                                            <th>Pending Followups</th>
                                            <th>Completed Followups</th>
                                            <th>Conversions</th>
                                            <th>Conv. Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($counsellorPerformance as $counsellor)
                                        <tr>
                                            <td>{{ $counsellor['name'] }}</td>
                                            <td>{{ $counsellor['assigned'] }}</td>
                                            <td><span class="badge badge-warning">{{ $counsellor['pending_followups'] }}</span></td>
                                            <td><span class="badge badge-success">{{ $counsellor['completed_followups'] }}</span></td>
                                            <td>{{ $counsellor['conversions'] }}</td>
                                            <td>
                                                <div class="progress progress-xs">
                                                    <div class="progress-bar bg-success" style="width: {{ $counsellor['conversion_rate'] }}%"></div>
                                                </div>
                                                <small>{{ $counsellor['conversion_rate'] }}%</small>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="6" class="text-center">No counsellor performance data found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fab fa-whatsapp"></i> WhatsApp Analytics</h3>
                        </div>
                        <div class="card-body">
                            @if(!$whatsappAnalytics)
                                <div class="text-center py-5">
                                    <i class="fab fa-whatsapp text-muted fa-3x mb-3"></i>
                                    <p class="text-muted">WhatsApp data will appear after first campaign/message.</p>
                                </div>
                            @else
                                <div class="row mb-3">
                                    <div class="col-6 text-center">
                                        <h4 class="text-success">{{ $whatsappAnalytics['delivery_rate'] }}%</h4>
                                        <span>Delivery Rate</span>
                                    </div>
                                    <div class="col-6 text-center">
                                        <h4 class="text-primary">{{ $whatsappAnalytics['read_rate'] }}%</h4>
                                        <span>Read Rate</span>
                                    </div>
                                </div>
                                <canvas id="whatsappTrendChart" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                                <ul class="list-group list-group-unbordered mt-3">
                                    <li class="list-group-item">
                                        <b>Total Messages</b> <a class="float-right">{{ $whatsappAnalytics['total'] }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Delivered</b> <a class="float-right text-success">{{ $whatsappAnalytics['delivered'] }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Failed</b> <a class="float-right text-danger">{{ $whatsappAnalytics['failed'] }}</a>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        // Lead Trend Chart
        var leadTrendCtx = document.getElementById('leadTrendChart').getContext('2d');
        var leadTrendData = @json($leadTrend);
        new Chart(leadTrendCtx, {
            type: 'line',
            data: {
                labels: leadTrendData.labels,
                datasets: [{
                    label: 'Leads',
                    data: leadTrendData.data,
                    backgroundColor: 'rgba(60,141,188,0.2)',
                    borderColor: 'rgba(60,141,188,1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { maintainAspectRatio: false, responsive: true }
        });

        // Brand Performance Chart
        var brandPerfCtx = document.getElementById('brandPerformanceChart').getContext('2d');
        var brandData = @json($brandPerformance);
        var brandLabels = brandData.map(b => b.brand);
        var brandLeads = brandData.map(b => b.leads);
        var brandConverted = brandData.map(b => b.converted);
        
        new Chart(brandPerfCtx, {
            type: 'bar',
            data: {
                labels: brandLabels,
                datasets: [
                    {
                        label: 'Total Leads',
                        data: brandLeads,
                        backgroundColor: 'rgba(23, 162, 184, 0.8)'
                    },
                    {
                        label: 'Converted',
                        data: brandConverted,
                        backgroundColor: 'rgba(40, 167, 69, 0.8)'
                    }
                ]
            },
            options: { maintainAspectRatio: false, responsive: true }
        });

        // Lead Source Chart
        var sourceBreakdownCtx = document.getElementById('sourceBreakdownChart').getContext('2d');
        var sourceData = @json($sourceBreakdown);
        new Chart(sourceBreakdownCtx, {
            type: 'doughnut',
            data: {
                labels: sourceData.map(s => s.source_page),
                datasets: [{
                    data: sourceData.map(s => s.total),
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de']
                }]
            },
            options: { maintainAspectRatio: false, responsive: true }
        });

        // Status Funnel
        var statusFunnelCtx = document.getElementById('statusFunnelChart').getContext('2d');
        var statusData = @json($statusFunnel);
        new Chart(statusFunnelCtx, {
            type: 'pie',
            data: {
                labels: statusData.map(s => s.status.toUpperCase()),
                datasets: [{
                    data: statusData.map(s => s.total),
                    backgroundColor: ['#f39c12', '#00c0ef', '#3c8dbc', '#00a65a', '#f56954']
                }]
            },
            options: { maintainAspectRatio: false, responsive: true }
        });

        // WhatsApp Daily Trend
        @if($whatsappAnalytics)
        var waTrendCtx = document.getElementById('whatsappTrendChart').getContext('2d');
        var waTrendData = @json($whatsappAnalytics['daily_trend']);
        new Chart(waTrendCtx, {
            type: 'line',
            data: {
                labels: waTrendData.labels,
                datasets: [{
                    label: 'Delivered',
                    data: waTrendData.data,
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { 
                maintainAspectRatio: false, 
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
        @endif
    });
</script>
@endpush
