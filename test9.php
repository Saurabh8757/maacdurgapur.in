<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create("/v1/cpanel/admin/leads", "GET", ["brand_id" => "10"]);
$controller = new App\Http\Controllers\Admin\LeadManagementController();
$response = $controller->index($request);
echo get_class($response) . "\n";
$html = $response->render();
if (preg_match("/<table.*?>(.*?)<\/table>/s", $html, $matches)) {
    echo "Table found. Row count: " . substr_count($matches[1], "<tr>");
} else {
    echo "No table found.";
}

