<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User;

use App\Domains\User\User;
use App\Domains\User\ValueObjects\Status;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\MockObject\MockObject;

use Tests\TestCase;

// use PHPUnit\Framework\TestCase;

/**
 * Testing User Entity Class
 *
 * Unit test for User Entity
 *
 * @ticket Feature/DL-2
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserTest extends TestCase
{
    /**
     * Create a mock user object, add reference with User Class and Mock Object class
     *
     * @var User&MockObject
     */
    private User $user;

    /**
     * Mock the User Entity
     * - This method is called before each test.
     * - Need to specify which methods to mock [save]
     *
     * @ticket Feature/DL-2
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getMockBuilder(User::class)
            ->onlyMethods(['save'])
            ->getMock();

        // Configure the 'exists' method to return true by default
        $this->user->exists = true;
    }


    /**
     * Successfull change password test
     *
     * @ticket Feature/DL-2
     *
     * @return void
     */
    public function test_change_password(): void
    {
        // Arrange
        $strPassword = 'new_password';
        $strCurrentPass = 'new_password';

        // Act
        $this->user->changePassword($strCurrentPass, $strPassword);

        // When
        $this->assertTrue(Hash::check($strPassword, $this->user->password));
    }

    /**
     * Test if setting and checking active status is working
     *
     * @ticket Feature/DL-2
     *
     * @return void
     */
    public function test_active_status(): void
    {
        // Arrange
        $intStatus = Status::ACTIVE;
        $this->user->status = new Status($intStatus);

        // Act
        $blnStatus = $this->user->isActive();

        // When
        $this->assertTrue($blnStatus);
    }

    public function test_mark_user_as_verified(): void
    {
        // Arrange
        $dtNow = now();

        // Act
        $this->user->markAsVerified();

        // When
        $this->assertNotNull($this->user->email_verified_at);
        $this->assertEquals(new Status(Status::ACTIVE), $this->user->status);
        $this->assertEquals($dtNow->toDateString(), $this->user->email_verified_at->toDateString());
    }

    public function test_if_user_was_verified(): void
    {
        // Arrange
        $this->user->markAsVerified();

        // Act
        $blnIsVerified = $this->user->isVerified();

        // When
        $this->assertTrue($blnIsVerified);
    }

    public function test_soft_delete(): void
    {
        // Arrange
        $this->user->softDelete();

        // Act
        $blnIsDeleted = $this->user->isDeleted();

        // When
        $this->assertTrue($blnIsDeleted);
    }



}
