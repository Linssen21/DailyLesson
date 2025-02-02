<?php

declare(strict_types=1);

namespace Tests\Feature\Web;

use App\Domains\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class UserTest extends TestCase
{
    // Rollback database changes
    use DatabaseTransactions;
    private User $user;

    public function test_verification(): void
    {
        // Arrange
        $this->user = User::factory()->create();
        $this->user->email_verified_at = null;
        $id = $this->user->getKey();
        $hash = sha1($this->user->getEmailForVerification());
        $verificationUrl = URL::signedRoute('verification.verify', ['id' => $id, 'hash' => $hash]);

        // Act
        $response = $this->get($verificationUrl);

        // Assert
        $redirectUrl = config('app.frontend_url').'?verified=1';
        $response->assertRedirect($redirectUrl);
    }

    public function test_verification_failing(): void
    {
        // Arrange
        // Will fail because already verified
        $this->user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->user->email_verified_at = null;
        $id = $this->user->getKey();
        $hash = sha1($this->user->getEmailForVerification());
        $verificationUrl = URL::signedRoute('verification.verify', ['id' => $id, 'hash' => $hash]);

        // Act
        $response = $this->get($verificationUrl);

        // Assert
        $response->assertStatus(422);
    }
}
