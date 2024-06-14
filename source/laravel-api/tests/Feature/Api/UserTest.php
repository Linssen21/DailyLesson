<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domains\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    // Rollback database changes
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Arrange
        User::factory()->create([
           'name' => 'testname',
           'email' => 'test@test.com',
           'password' => 'testpass',
           'display_name' => 'testdisp',
           'email_verified_at' => now(),
        ]);
    }

    public function test_registration(): void
    {
        // Act
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'testname1',
            'email' => 'test1@test.com',
            'password' => 'testpass',
            'password_confirmation' => 'testpass',
            'display_name' => 'testdisp1'
        ]);

        // When
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS'),
            ]);
    }


    public function test_registration_failing(): void
    {
        // Act
        $response = $this->postJson('/api/v2/auth/register', [
          'name' => 'testname',
          'email' => 'test@test.com',
          'password' => 'testpass',
          'password_confirmation' => 'testpass',
          'display_name' => 'testdisp'
        ]);

        // When
        $response->assertStatus(200)
            ->assertJson([
                'status' => false,
            ]);
    }

    public function test_login(): void
    {
        // Act
        $response = $this->postJson('/api/v2/auth/login', [
            'email' => 'test@test.com',
            'password' => 'testpass',
        ]);

        // When
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS'),
            ]);
    }

    public function test_login_fails(): void
    {
        // Act
        $response = $this->postJson('/api/v2/auth/login', [
            'email' => 'test@test.com',
            'password' => 'testpass_failed',
        ]);

        // When
        $response->assertStatus(500)
            ->assertJson([
                'status' => config('constants.STATUS_FAILED'),
            ]);
    }


    public function test_send_verification(): void
    {
        // Act
        $response = $this->postJson('/api/v2/verify/email/verification-notification', [
            'email' => 'test@test.com',
        ]);

        // When
        $response->assertStatus(200)
        ->assertJson([
            'status' => config('constants.STATUS_SUCCESS'),
        ]);
    }

    public function test_logout(): void
    {
        $logResponse = $this->postJson('/api/v2/auth/login', [
            'email' => 'test@test.com',
            'password' => 'testpass',
        ]);

        $token = $logResponse->json('token');

        // Logout request with bearer token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/v2/auth/logout');

        // Assert logout response
        $response->assertStatus(200)
        ->assertJson([
            'message' => 'Logout successful',
        ]);
    }

    public function test_logout_fail(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . 'Fail',
        ])->postJson('/api/v2/auth/logout');

        // Assert logout response
        $response->assertStatus(401)
        ->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    }
}
