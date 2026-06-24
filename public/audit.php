<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\LeadActivity;
use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\DB;

$stats = [];

// LEADS
$stats['leads']['total'] = Lead::count();
$stats['leads']['by_brand'] = Lead::select('brand_id', DB::raw('count(*) as total'))->groupBy('brand_id')->get()->toArray();
$stats['leads']['by_status'] = Lead::select('status', DB::raw('count(*) as total'))->groupBy('status')->get()->toArray();
$stats['leads']['by_source'] = Lead::select('source_page', DB::raw('count(*) as total'))->groupBy('source_page')->get()->toArray();

// FOLLOWUPS
$stats['followups']['pending'] = LeadFollowup::where('status', 'pending')->where('followup_date', '>=', date('Y-m-d'))->count();
$stats['followups']['completed'] = LeadFollowup::where('status', 'completed')->count();
$stats['followups']['missed'] = LeadFollowup::where('status', 'missed')->count();
$stats['followups']['overdue'] = LeadFollowup::where('status', 'pending')->where('followup_date', '<', date('Y-m-d'))->count();

// CRM ACTIVITIES
$stats['activities']['total'] = LeadActivity::count();
$stats['activities']['status_changes'] = LeadActivity::where('activity_type', 'STATUS_CHANGED')->count();

// WHATSAPP
$totalWa = WhatsappMessage::count();
$deliveredWa = WhatsappMessage::where('status', 'delivered')->count();
$readWa = WhatsappMessage::where('status', 'read')->count();

$stats['whatsapp']['total'] = $totalWa;
$stats['whatsapp']['delivered'] = $deliveredWa;
$stats['whatsapp']['read'] = $readWa;
$stats['whatsapp']['failed'] = WhatsappMessage::where('status', 'failed')->count();
$stats['whatsapp']['delivery_rate'] = $totalWa > 0 ? round(($deliveredWa / $totalWa) * 100, 2) . '%' : '0%';
$stats['whatsapp']['read_rate'] = $totalWa > 0 ? round(($readWa / $totalWa) * 100, 2) . '%' : '0%';

echo json_encode($stats, JSON_PRETTY_PRINT);
