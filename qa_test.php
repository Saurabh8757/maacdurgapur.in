<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$controller = app()->make(\App\Http\Controllers\Web\PageController::class);

function runTest($name, $data, $expectedStatus, $expectedErrors = []) {
    global $controller;
    $request = Illuminate\Http\Request::create('/career-counselling', 'POST', $data);
    $response = $controller->counselling($request);
    $result = json_decode($response->getContent(), true);
    
    echo "==================================================\n";
    echo "TEST: $name\n";
    if ($result['status'] == $expectedStatus) {
        $passed = true;
        if (!empty($expectedErrors)) {
            foreach ($expectedErrors as $field) {
                if (!isset($result['error'][$field])) {
                    $passed = false;
                    echo "FAILED: Expected error on field '$field' but not found.\n";
                }
            }
        }
        if ($passed) echo "PASSED\n";
    } else {
        echo "FAILED: Expected status $expectedStatus but got " . $result['status'] . "\n";
        print_r($result);
    }
}

// Ensure MAAC brand ID is known
$brandId = \App\Models\Brand::where('slug', 'maac')->first()->id;

$tests = [
    [
        'name' => '1. Valid submission',
        'data' => ['brand_id' => $brandId, 'name' => 'John Valid', 'phone' => '9876543210', 'email' => 'john.valid@example.com', 'course_id' => '1', 'location' => 'Durgapur', 'message' => 'Hello!', 'consent' => '1'],
        'status' => 1
    ],
    [
        'name' => '2. Missing required field (name)',
        'data' => ['brand_id' => $brandId, 'phone' => '9876543210', 'email' => 'john@example.com', 'course_id' => '1'],
        'status' => 0,
        'errors' => ['name']
    ],
    [
        'name' => '3. Invalid email',
        'data' => ['brand_id' => $brandId, 'name' => 'Jane Email', 'phone' => '9876543210', 'email' => 'not-an-email', 'course_id' => '1'],
        'status' => 0,
        'errors' => ['email']
    ],
    [
        'name' => '4. Invalid phone',
        'data' => ['brand_id' => $brandId, 'name' => 'Jane Phone', 'phone' => 'abcde', 'email' => 'jane@example.com', 'course_id' => '1'],
        'status' => 0,
        'errors' => ['phone']
    ],
    [
        'name' => '5. Long message',
        'data' => ['brand_id' => $brandId, 'name' => 'John Long', 'phone' => '9876543210', 'email' => 'long@example.com', 'course_id' => '1', 'message' => str_repeat('Long message here. ', 50)],
        'status' => 1
    ],
    [
        'name' => '6. Empty optional fields (location, message)',
        'data' => ['brand_id' => $brandId, 'name' => 'John Optional', 'phone' => '9876543210', 'email' => 'optional@example.com', 'course_id' => '2'],
        'status' => 1
    ],
    [
        'name' => '7. Select field (course_id tests raw name mapping vs id mapping)',
        'data' => ['brand_id' => $brandId, 'name' => 'Jane Select', 'phone' => '9876543210', 'email' => 'select@example.com', 'course_id' => 'Custom Course Not Number'],
        'status' => 1
    ],
    [
        'name' => '8. Checkbox field (consent=0)',
        'data' => ['brand_id' => $brandId, 'name' => 'Jane Checkbox', 'phone' => '9876543210', 'email' => 'checkbox@example.com', 'course_id' => '1', 'consent' => '0'],
        'status' => 1
    ],
    [
        'name' => '9. Multiple submissions 1',
        'data' => ['brand_id' => $brandId, 'name' => 'John Multi 1', 'phone' => '9876543210', 'email' => 'multi1@example.com', 'course_id' => '1'],
        'status' => 1
    ],
    [
        'name' => '10. Multiple submissions 2',
        'data' => ['brand_id' => $brandId, 'name' => 'John Multi 2', 'phone' => '9876543210', 'email' => 'multi2@example.com', 'course_id' => '1'],
        'status' => 1
    ],
    [
        'name' => '11. Rapid repeated submissions',
        'data' => ['brand_id' => $brandId, 'name' => 'Jane Rapid', 'phone' => '9876543210', 'email' => 'rapid@example.com', 'course_id' => '1'],
        'status' => 1
    ]
];

foreach ($tests as $t) {
    runTest($t['name'], $t['data'], $t['status'], $t['errors'] ?? []);
}

// Add a few more valid leads to hit 15 total real test leads
for ($i=12; $i<=15; $i++) {
    runTest("$i. Extra Load Test $i", ['brand_id' => $brandId, 'name' => "Extra Lead $i", 'phone' => '1111111111', 'email' => "extra$i@example.com", 'course_id' => '1'], 1);
}

echo "==================================================\n";
echo "Verifying DB state:\n";
$leads = \App\Models\Lead::where('brand_id', $brandId)->orderBy('id', 'desc')->take(10)->get();
foreach ($leads as $l) {
    echo "ID: {$l->id} | Name: {$l->name} | Phone: {$l->phone} | Course: {$l->course_name} | Custom: " . json_encode($l->custom_data) . "\n";
}
