<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\LeadFollowup;
use App\Services\AnalyticsService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /*** Dashboard ***/
    public function dashboard(AnalyticsService $analyticsService)
    {
        $conversionMetrics = $analyticsService->getConversionMetrics();
        $followupStats = $analyticsService->getFollowupStats();
        $leadTrend = $analyticsService->getLeadTrend();
        $brandPerformance = $analyticsService->getBrandPerformance();
        $sourceBreakdown = $analyticsService->getSourceBreakdown();
        $statusFunnel = $analyticsService->getLeadStatusFunnel();
        $counsellorPerformance = $analyticsService->getCounsellorPerformance();
        $whatsappAnalytics = $analyticsService->getWhatsappAnalytics();

        return view('admin.pages.dashboard.index', compact(
            'conversionMetrics',
            'followupStats',
            'leadTrend',
            'brandPerformance',
            'sourceBreakdown',
            'statusFunnel',
            'counsellorPerformance',
            'whatsappAnalytics'
        ));
    }

    public function export(AnalyticsService $analyticsService)
    {
        $conversionMetrics = $analyticsService->getConversionMetrics();
        $brandPerformance = $analyticsService->getBrandPerformance();
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=analytics_export_" . date('Y_m_d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Metric', 'Value'];

        $callback = function() use($conversionMetrics, $brandPerformance, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            fputcsv($file, ['Total Leads', $conversionMetrics['total_leads']]);
            fputcsv($file, ['New Today', $conversionMetrics['new_today']]);
            fputcsv($file, ['Converted Leads', $conversionMetrics['converted']]);
            fputcsv($file, ['Conversion Rate', $conversionMetrics['conversion_rate'] . '%']);
            fputcsv($file, ['Growth', $conversionMetrics['growth_percent'] . '%']);

            fputcsv($file, []);
            fputcsv($file, ['Brand Performance', '']);
            fputcsv($file, ['Brand Name', 'Leads', 'Converted']);
            foreach ($brandPerformance as $brand) {
                fputcsv($file, [$brand['brand'], $brand['leads'], $brand['converted']]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
