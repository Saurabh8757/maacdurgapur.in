<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = [
    ['code' => 'cms.showcase.view', 'domain' => 'cms', 'resource' => 'showcase', 'action' => 'view', 'name' => 'View Showcase', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.showcase.create', 'domain' => 'cms', 'resource' => 'showcase', 'action' => 'create', 'name' => 'Create Showcase', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.showcase.edit', 'domain' => 'cms', 'resource' => 'showcase', 'action' => 'edit', 'name' => 'Edit Showcase', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.showcase.delete', 'domain' => 'cms', 'resource' => 'showcase', 'action' => 'delete', 'name' => 'Delete Showcase', 'scope_type' => 'brand', 'risk_level' => 'medium'],
    ['code' => 'cms.showcase.publish', 'domain' => 'cms', 'resource' => 'showcase', 'action' => 'publish', 'name' => 'Publish Showcase', 'scope_type' => 'brand', 'risk_level' => 'high']
];

App\Models\Permission::insert($data);
echo "Showcase permissions inserted successfully.\n";
