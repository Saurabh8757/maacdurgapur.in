<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_default_unrecognized_test_host_returns_not_found()
    {
        $response = $this->get('/');

        $response->assertStatus(404);
    }
}
