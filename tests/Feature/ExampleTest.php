<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test root route redirects to documentation.
     */
    public function test_the_root_redirects_to_documentation(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/api/documentation');
    }

    /**
     * Test documentation viewer is accessible.
     */
    public function test_documentation_page_returns_successful_response(): void
    {
        $response = $this->get('/api/documentation');
        $response->assertStatus(200);
        $response->assertSee('API Wilayah Indonesia 2026');
    }

    /**
     * Test demo playground is accessible.
     */
    public function test_demo_playground_returns_successful_response(): void
    {
        $response = $this->get('/demo');
        $response->assertStatus(200);
        $response->assertSee('Developer Playground');
    }
}
