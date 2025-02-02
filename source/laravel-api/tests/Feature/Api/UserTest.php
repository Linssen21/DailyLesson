<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domains\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\Util;

class UserTest extends TestCase
{
    // Rollback database changes
    use DatabaseTransactions;
    private Util $util;

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
        $this->util = new Util($this);
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

        // Assert
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

        // Assert
        $response->assertStatus(422)
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

        // Assert
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

        // Assert
        $response->assertStatus(400)
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

        // Assert
        $response->assertStatus(200)
        ->assertJson([
            'status' => config('constants.STATUS_SUCCESS'),
        ]);
    }

    public function test_logout(): void
    {

        $token = $this->util->authToken('test@test.com', 'testpass');

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

    public function test_social_redirect_google(): void
    {
        $response = $this->getJson('/api/v2/auth/redirect/google');
        $url = $response->json('url');

        $response->assertOk();
        $response->assertJson([
            'url' => $url,
            'message' => 'Url is generated',
            'status' => true
        ]);
        $this->assertStringStartsWith('https://accounts.google.com/', $url);
    }

    public function test_social_redirect_facebook(): void
    {
        $response = $this->getJson('/api/v2/auth/redirect/facebook');
        $url = $response->json('url');

        $response->assertOk();
        $response->assertJson([
            'url' => $url,
            'message' => 'Url is generated',
            'status' => true
        ]);
        $this->assertStringStartsWith('https://www.facebook.com/', $url);
    }

    public function test_social_redirect_failing(): void
    {
        $response = $this->getJson('/api/v2/auth/redirect/failing');

        $response->assertBadRequest();
        $response->assertJson([
            'url' => '',
            'message' => 'Provider is not supported',
            'status' => false
        ]);
    }

}
