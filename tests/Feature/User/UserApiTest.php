<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\FeatureTestCase;

class UserApiTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        User::query()->forceDelete();
    }

    #[Test]
    public function it_logs_in_user_and_returns_token(): void
    {
        $password = 'secret123';
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make($password),
        ]);

        $response = $this->apiPost('/api/auth/login', [
            'email' => 'john@example.com',
            'password' => $password,
        ]);

        $this->assertSuccessResponse($response);
        $data = $response->json('data');

        $this->assertArrayHasKey('token', $data);
        $this->assertEquals($user->id, $data['id']);
        $this->assertEquals($user->email, $data['email']);
    }

    #[Test]
    public function it_fails_login_with_wrong_credentials(): void
    {
        $password = 'correct-password';
        $wrongPassword = 'wrong-password';
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make($password),
        ]);

        $response = $this->apiPost('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => Hash::make($wrongPassword),
        ]);

        $response->assertStatus(401);
        $this->assertFalse($response->json('success'));
        $response->assertJsonFragment(
            ['error' =>
                ['message' => 'Invalid credentials']
            ]
        );
    }

    #[Test]
    public function it_requires_email_and_password_fields_on_login(): void
    {
        $response = $this->apiPost('/api/auth/login', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    #[Test]
    public function it_returns_authenticated_user_details(): void
    {
        $password = 'password';
        $user = User::factory()->create([
            'email' => 'me@example.com',
            'password' => Hash::make($password),
        ]);

        // Login to get token
        $loginResponse = $this->apiPost('/api/auth/login', [
            'email' =>'me@example.com',
            'password' => $password,
        ]);
        $token = $loginResponse->json('data.token');

        $response = $this->apiGet('/api/auth/user', [
            'Authorization' => "Bearer {$token}",
        ]);


        $this->assertSuccessResponse($response);
        $response->assertJsonFragment([
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }

    #[Test]
    public function it_logs_out_authenticated_user(): void
    {
        $password = 'password';
        $user = User::factory()->create([
            'email' => 'me@example.com',
            'password' => Hash::make($password),
        ]);


        $loginResponse = $this->apiPost('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);
        $token = $loginResponse->json('data.token');

        $response = $this->apiPost('/api/auth/logout', [], [
            'Authorization' => "Bearer {$token}",
        ]);

        $this->assertSuccessResponse($response);
    }
}
