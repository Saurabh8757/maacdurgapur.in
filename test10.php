<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create("/v1/cpanel/admin/leads", "GET", ["brand_id" => "10", "status" => "new"]);
$controller = new App\Http\Controllers\Admin\LeadManagementController();
$response = $controller->index($request);
$html = $response->render();
echo strpos($html, "value=\"10\" selected") !== false ? "AKSHA IS SELECTED" : "AKSHA IS NOT SELECTED";

