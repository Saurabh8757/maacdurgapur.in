<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create("/v1/cpanel/admin/leads", "GET", [
    "brand_id" => "10",
    "status" => "new",
    "date_from" => "2026-06-23",
    "date_to" => "2026-06-24"
]);
$controller = new App\Http\Controllers\Admin\LeadManagementController();
$response = $controller->index($request);
$leads = $response->getData()["leads"];
echo count($leads);

