<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/career-counselling', 'POST', [
    'brand_id' => 9, 
    'name' => 'MAAC Test', 
    'phone' => '1112223334', 
    'email' => 'maactest@maac.com', 
    'course_id' => 'Test Course', 
    'location' => 'Durgapur'
]);
$response = $kernel->handle($request);
echo $response->getContent();
