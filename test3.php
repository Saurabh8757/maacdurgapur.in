<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$brands = App\Models\Brand::all();
foreach($brands as $brand) {
    echo "<option value=\"".$brand->id."\">".$brand->name."</option>\n";
}

