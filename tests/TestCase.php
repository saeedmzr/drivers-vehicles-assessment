<?php

namespace Tests;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Create authenticated user for testing
     */
    protected function authenticatedUser(): Collection|Model
    {
        return \Models\User::factory()->create([
            'is_admin' => true,
        ]);
    }

    /**
     * Assert JSON response structure
     */
    protected function assertSuccessResponse($response, $status = 200): void
    {
        $response->assertStatus($status)
            ->assertJsonStructure([
                'success',
                'data',
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Assert error response structure
     */
    protected function assertErrorResponse($response, $status = 400): void
    {
        $response->assertStatus($status)
            ->assertJsonStructure([
                'success',
                'error',
            ])
            ->assertJson([
                'success' => false,
            ]);
    }
}
