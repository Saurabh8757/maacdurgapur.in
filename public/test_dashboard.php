<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (!$app->environment('local')) {
    http_response_code(404);
    exit;
}

use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Services\AnalyticsService;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\Cache;

// Clear cache to test fresh load
Cache::flush();

$startTime = microtime(true);

$controller = $app->make(DashboardController::class);
$service = $app->make(AnalyticsService::class);
$controller->dashboard($service);

$freshLoadTime = microtime(true) - $startTime;
$freshQueryCount = AppServiceProvider::$queryCount;

// Test Cached load
AppServiceProvider::$queryCount = 0;
$startTime = microtime(true);
$controller->dashboard($service);
$cachedLoadTime = microtime(true) - $startTime;
$cachedQueryCount = AppServiceProvider::$queryCount;

echo "--- DASHBOARD PERFORMANCE REPORT ---\n";
echo "FRESH LOAD:\n";
echo "- Execution Time: " . round($freshLoadTime, 4) . " seconds\n";
echo "- SQL Queries: " . $freshQueryCount . "\n\n";

echo "CACHED LOAD:\n";
echo "- Execution Time: " . round($cachedLoadTime, 4) . " seconds\n";
echo "- SQL Queries: " . $cachedQueryCount . "\n";
