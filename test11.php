<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create("/v1/cpanel/admin/leads", "GET", [
    "brand_id" => "10",
    "status" => "new"
]);
$controller = new App\Http\Controllers\Admin\LeadManagementController();
\DB::enableQueryLog();
$response = $controller->index($request);
print_r(\DB::getQueryLog());

