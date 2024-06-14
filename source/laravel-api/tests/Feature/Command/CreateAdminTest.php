<?php

declare(strict_types=1);

namespace Tests\Feature\Command;

use App\Domains\User\Contracts\UserMetaRepository;
use App\Domains\User\Contracts\UserRepository;
use App\Domains\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class CreateAdminTest extends TestCase
{
    // Rollback database changes
    use RefreshDatabase;

    private string $nameOps;
    private string $emailOps;
    private string $passwordOps;

    /**
    * @var UserRepository&MockInterface
    */
    private UserRepository $userRepositoryMock;
    /**
     * @var UserMetaRepository&MockInterface
     */
    private UserMetaRepository $userMetaRepositoryMock;

    protected function setUp(): void
    {
        $this->nameOps = 'Admin Name';
        $this->emailOps = 'admin_email@test.com';
        $this->passwordOps = 'testpass';
        parent::setUp();
    }

    public function test_create_admin(): void
    {
        $this->artisan('create:admin', [
            '--name' => $this->nameOps,
            '--email' => $this->emailOps,
            '--password' => $this->passwordOps
        ])->expectsOutput('Admin user created successfully!')
        ->assertExitCode(0)->assertOk();


        // Additional assertions can be added here to verify that the admin was created
        // For example, checking the database to ensure the admin exists
        $this->assertDatabaseHas('users', [
            'name' => $this->nameOps,
            'email' => $this->emailOps
        ]);

        // Assert that the admin meta created for the admin was created
        $admin_meta = User::where('email', $this->emailOps)->first()->user_meta->first();
        $this->assertEquals('capabilities', $admin_meta->meta_key);
        $this->assertEquals('administrator', $admin_meta->meta_value);
    }


    public function test_create_admin_validation_fail(): void
    {
        $this->artisan('create:admin', [
            '--name' => $this->nameOps,
            '--email' => 'email_wrong_format',
            '--password' => $this->passwordOps
        ])->expectsOutput('Validation failed')
        ->assertExitCode(1)->assertFailed();
    }

}
