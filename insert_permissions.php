<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = [
    ['code' => 'cms.courses.view', 'domain' => 'cms', 'resource' => 'courses', 'action' => 'view', 'name' => 'View Courses', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.courses.create', 'domain' => 'cms', 'resource' => 'courses', 'action' => 'create', 'name' => 'Create Courses', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.courses.edit', 'domain' => 'cms', 'resource' => 'courses', 'action' => 'edit', 'name' => 'Edit Courses', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.courses.delete', 'domain' => 'cms', 'resource' => 'courses', 'action' => 'delete', 'name' => 'Delete Courses', 'scope_type' => 'brand', 'risk_level' => 'medium']
];

App\Models\Permission::insert($data);
echo "Permissions inserted successfully.\n";
