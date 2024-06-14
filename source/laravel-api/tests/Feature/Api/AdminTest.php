<?php

namespace Tests\Feature\Api;

use App\Domains\User\User;
use App\Domains\User\UserMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    // Rollback database changes
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create([
            'id' => 99,
            'name' => 'testnameadmin',
            'email' => 'testadmin@test.com',
            'password' => 'testpassadmin',
            'display_name' => 'testdispadmin',
            'email_verified_at' => now(),
        ]);

        UserMeta::factory()->create([
            'user_id' => 99,
            'meta_key' => 'capabilities',
            'meta_value' => 'administrator'
        ]);
    }

    public function test_admin_login(): void
    {
        // Act
        $response = $this->postJson('/api/v2/admin/login', [
            'email' => 'testadmin@test.com',
            'password' => 'testpassadmin'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS'),
            ]);
    }

    public function test_admin_login_fail(): void
    {
        $response = $this->postJson('/api/v2/admin/login', [
            'email' => 'testadmin1@test.com',
            'password' => 'testpassadmin'
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'status' => config('constants.STATUS_FAILED'),
            ]);
    }
}
