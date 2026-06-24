<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create(
    '/career-counselling', 'POST',
    [], [], [],
    ['CONTENT_TYPE' => 'application/json', 'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'],
    json_encode([
        'name' => 'Bot Test',
        'phone' => '9988776655',
        'email' => 'bottest@example.com',
        'course_id' => '1',
        'form_type' => 'global_modal',
        'source_page' => 'Global Modal'
    ])
);

$response = $kernel->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";
