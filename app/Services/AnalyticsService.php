<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\WhatsappMessage;
use App\Models\User;
use App\Models\Brand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    protected $cacheDuration = 300; // 5 minutes

    public function getConversionMetrics()
    {
        return Cache::remember('analytics_conversion_metrics', $this->cacheDuration, function () {
            $totalLeads = Lead::count();
            $newToday = Lead::whereDate('created_at', Carbon::today())->count();
            $converted = Lead::where('status', 'converted')->count();
            $conversionRate = $totalLeads > 0 ? round(($converted / $totalLeads) * 100, 1) : 0;

            // Growth %
            $last30Days = Lead::where('created_at', '>=', Carbon::now()->subDays(30))->count();
            $prev30Days = Lead::whereBetween('created_at', [Carbon::now()->subDays(60), Carbon::now()->subDays(31)])->count();
            
            $growth = 0;
            if ($prev30Days > 0) {
                $growth = round((($last30Days - $prev30Days) / $prev30Days) * 100, 1);
            } elseif ($last30Days > 0) {
                $growth = 100;
            }

            return [
                'total_leads' => $totalLeads,
                'new_today' => $newToday,
                'converted' => $converted,
                'conversion_rate' => $conversionRate,
                'growth_percent' => $growth
            ];
        });
    }

    public function getFollowupStats()
    {
        return Cache::remember('analytics_followups', $this->cacheDuration, function () {
            return [
                'pending' => LeadFollowup::where('status', 'pending')->whereDate('followup_date', '>=', Carbon::today())->count(),
                'overdue' => LeadFollowup::where('status', 'pending')->whereDate('followup_date', '<', Carbon::today())->count(),
                'completed' => LeadFollowup::where('status', 'completed')->count(),
                'missed' => LeadFollowup::where('status', 'missed')->count(),
            ];
        });
    }

    public function getLeadTrend()
    {
        return Cache::remember('analytics_leads_trend', $this->cacheDuration, function () {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $leads = Lead::whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            // Fill missing dates
            $trend = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $trend[$date] = 0;
            }

            foreach ($leads as $lead) {
                $trend[$lead->date] = (int)$lead->count;
            }

            return [
                'labels' => array_keys($trend),
                'data' => array_values($trend)
            ];
        });
    }

    public function getBrandPerformance()
    {
        return Cache::remember('analytics_brands', $this->cacheDuration, function () {
            $brands = Brand::all();
            $stats = [];
            
            foreach ($brands as $brand) {
                $leads = Lead::where('brand_id', $brand->id)->count();
                $converted = Lead::where('brand_id', $brand->id)->where('status', 'converted')->count();
                
                if ($leads > 0) {
                    $stats[] = [
                        'brand' => $brand->name,
                        'leads' => $leads,
                        'converted' => $converted,
                    ];
                }
            }
            
            // Handle leads with null brand if any exist
            $nullBrandLeads = Lead::whereNull('brand_id')->count();
            if ($nullBrandLeads > 0) {
                $nullConverted = Lead::whereNull('brand_id')->where('status', 'converted')->count();
                $stats[] = [
                    'brand' => 'Unassigned',
                    'leads' => $nullBrandLeads,
                    'converted' => $nullConverted,
                ];
            }

            return $stats;
        });
    }

    public function getSourceBreakdown()
    {
        return Cache::remember('analytics_sources', $this->cacheDuration, function () {
            return Lead::select('source_page', DB::raw('count(*) as total'))
                ->groupBy('source_page')
                ->orderBy('total', 'desc')
                ->get()
                ->toArray();
        });
    }

    public function getLeadStatusFunnel()
    {
        return Cache::remember('analytics_status_funnel', $this->cacheDuration, function () {
            return Lead::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get()
                ->toArray();
        });
    }

    public function getCounsellorPerformance()
    {
        return Cache::remember('analytics_counsellors', $this->cacheDuration, function () {
            $counsellors = User::whereHas('roles', function($q) {
                $q->whereIn('code', ['counsellor', 'admin', 'super_admin']);
            })->get();

            $stats = [];
            foreach ($counsellors as $counsellor) {
                $assignedLeads = Lead::where('assigned_to', $counsellor->id)->count();
                
                if ($assignedLeads > 0) {
                    $completedFollowups = LeadFollowup::where('assigned_user_id', $counsellor->id)
                                        ->where('status', 'completed')
                                        ->count();
                    $pendingFollowups = LeadFollowup::where('assigned_user_id', $counsellor->id)
                                        ->where('status', 'pending')
                                        ->count();
                    $conversions = Lead::where('assigned_to', $counsellor->id)
                                        ->where('status', 'converted')
                                        ->count();
                                        
                    $conversionRate = round(($conversions / $assignedLeads) * 100, 1);

                    $stats[] = [
                        'name' => $counsellor->name,
                        'assigned' => $assignedLeads,
                        'pending_followups' => $pendingFollowups,
                        'completed_followups' => $completedFollowups,
                        'conversions' => $conversions,
                        'conversion_rate' => $conversionRate
                    ];
                }
            }
            
            // Sort by conversion rate descending
            usort($stats, function($a, $b) {
                return $b['conversion_rate'] <=> $a['conversion_rate'];
            });

            return $stats;
        });
    }

    public function getWhatsappAnalytics()
    {
        return Cache::remember('analytics_whatsapp', $this->cacheDuration, function () {
            $total = WhatsappMessage::count();
            if ($total === 0) {
                return null; // Empty state indicator
            }

            $delivered = WhatsappMessage::where('status', 'delivered')->count();
            $read = WhatsappMessage::where('status', 'read')->count();
            $failed = WhatsappMessage::where('status', 'failed')->count();

            $deliveryRate = $total > 0 ? round(($delivered / $total) * 100, 1) : 0;
            $readRate = $total > 0 ? round(($read / $total) * 100, 1) : 0;

            // Daily Delivery Trend (Last 7 days)
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $trendData = WhatsappMessage::where('status', 'delivered')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get();

            $trend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $trend[$date] = 0;
            }
            foreach ($trendData as $row) {
                $trend[$row->date] = (int)$row->count;
            }

            return [
                'total' => $total,
                'delivered' => $delivered,
                'read' => $read,
                'failed' => $failed,
                'delivery_rate' => $deliveryRate,
                'read_rate' => $readRate,
                'daily_trend' => [
                    'labels' => array_keys($trend),
                    'data' => array_values($trend)
                ]
            ];
        });
    }
}
