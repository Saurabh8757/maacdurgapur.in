<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create("/v1/cpanel/admin/leads", "GET");
$controller = new App\Http\Controllers\Admin\LeadManagementController();
$response = $controller->index($request);
echo str_contains($response->render(), "<option value=\"10\" >AKSHA</option>") ? "TRUE" : "FALSE";
echo "\n";

