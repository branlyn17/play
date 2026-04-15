<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_root_redirects_to_the_default_locale(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/es');
    }

    public function test_the_localized_pages_return_a_successful_response(): void
    {
        $response = $this->get('/es');

        $response->assertStatus(200);

        $catalogResponse = $this->get('/en/catalog');

        $catalogResponse->assertStatus(200);
    }
}
