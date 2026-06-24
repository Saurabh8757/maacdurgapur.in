<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create(
    '/career-counselling', 'POST',
    ['name' => 'Bot Test', 'phone' => '1234567890', 'email' => 'bot@test.com', 'course_id' => '1', 'brand_id' => '10', 'form_type' => 'global_modal', 'source_page' => 'Global Modal'],
    [], [],
    ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']
);
$app->instance('request', $request);
// Bypass CSRF by removing the middleware
$kernel->prependMiddleware(App\Http\Middleware\TrustProxies::class);

try {
    $response = app(\App\Http\Controllers\Web\PageController::class)->counselling($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    echo "Content: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
