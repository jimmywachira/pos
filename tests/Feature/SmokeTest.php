<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_basic_pages_load()
    {
        $paths = ['/', '/login', '/shifts', '/pos'];

        foreach ($paths as $path) {
            $response = $this->get($path);
            $this->assertContains($response->status(), [200, 302], "Unexpected status for $path: {$response->status()}");
        }
    }
}
