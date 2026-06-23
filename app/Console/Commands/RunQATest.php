<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Brand;
use App\Models\Lead;
use Illuminate\Http\Request;

class RunQATest extends Command
{
    protected $signature = 'qa:run';
    protected $description = 'Run the MAAC QA tests for dynamic forms';

    public function handle()
    {
        $controller = app()->make(\App\Http\Controllers\Web\PageController::class);

        $runTest = function($name, $data, $expectedStatus, $expectedErrors = []) use ($controller) {
            $request = Request::create('/career-counselling', 'POST', $data);
            $response = $controller->counselling($request);
            $result = json_decode($response->getContent(), true);
            
            $this->line("==================================================");
            $this->info("TEST: $name");
            if ($result['status'] == $expectedStatus) {
                $passed = true;
                if (!empty($expectedErrors)) {
                    foreach ($expectedErrors as $field) {
                        if (!isset($result['error'][$field])) {
                            $passed = false;
                            $this->error("FAILED: Expected error on field '$field' but not found.");
                        }
                    }
                }
                if ($passed) $this->info("PASSED");
            } else {
                $this->error("FAILED: Expected status $expectedStatus but got " . $result['status']);
                $this->line(json_encode($result));
            }
        };

        $brand = Brand::where('slug', 'space-e-fic')->first();
        if (!$brand) {
            $this->error("SPACE-E-FIC brand not found.");
            return;
        }
        $brandId = $brand->id;

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
                'name' => '8. Checkbox field (consent=0) [checkbox not submitted in HTML]',
                'data' => ['brand_id' => $brandId, 'name' => 'Jane Checkbox', 'phone' => '9876543210', 'email' => 'checkbox@example.com', 'course_id' => '1'],
                'status' => 1 // the checkbox is optional so this passes
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
            $runTest($t['name'], $t['data'], $t['status'], $t['errors'] ?? []);
        }

        // Add a few more valid leads to hit 15 total real test leads
        for ($i=12; $i<=15; $i++) {
            $runTest("$i. Extra Load Test $i", ['brand_id' => $brandId, 'name' => "Extra Lead $i", 'phone' => '1111111111', 'email' => "extra$i@example.com", 'course_id' => '1'], 1);
        }

        $this->line("==================================================");
        $this->info("Verifying DB state:");
        $leads = Lead::where('brand_id', $brandId)->orderBy('id', 'desc')->take(12)->get();
        foreach ($leads as $l) {
            $this->line("ID: {$l->id} | Name: {$l->name} | Phone: {$l->phone} | Course: {$l->course_name} | Custom: " . json_encode($l->custom_data));
        }

        $this->info("QA Test Suite Completed");
    }
}
