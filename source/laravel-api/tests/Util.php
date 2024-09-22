<?php

namespace Tests;

use App\Domains\User\User;
use App\Domains\User\UserMeta;

class Util
{
    public function __construct(private TestCase $testCase)
    {
    }

    public function authToken(string $email, string $password): string
    {
        $logResponse = $this->testCase->postJson('/api/v2/auth/login', [
            'email' => $email,
            'password' => $password,
        ]);

        return $logResponse->json('token');
    }

    public function authAdminToken(string $email, string $password): string
    {
        $logResponse = $this->testCase->postJson('/api/v2/admin/login', [
            'email' => $email,
            'password' => $password,
        ]);

        return $logResponse->json('token');
    }


    public function createAdminUser(string $email, string $password, int $id = 99): void
    {
        User::factory()->create([
            'id' => $id,
            'name' => 'testnameadmin',
            'email' => $email,
            'password' => $password,
            'display_name' => 'testdispadmin',
            'email_verified_at' => now(),
        ]);

        UserMeta::factory()->create([
            'user_id' => 99,
            'meta_key' => 'capabilities',
            'meta_value' => 'administrator'
        ]);
    }
}
