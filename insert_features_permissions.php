<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = [
    ['code' => 'cms.features.view', 'domain' => 'cms', 'resource' => 'features', 'action' => 'view', 'name' => 'View Features', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.features.create', 'domain' => 'cms', 'resource' => 'features', 'action' => 'create', 'name' => 'Create Features', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.features.edit', 'domain' => 'cms', 'resource' => 'features', 'action' => 'edit', 'name' => 'Edit Features', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.features.delete', 'domain' => 'cms', 'resource' => 'features', 'action' => 'delete', 'name' => 'Delete Features', 'scope_type' => 'brand', 'risk_level' => 'medium']
];

App\Models\Permission::insert($data);
echo "Features permissions inserted successfully.\n";
