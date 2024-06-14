<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User\Service;

use App\Domains\User\Contracts\UserRepository;
use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\Service\UserService;
use App\Domains\User\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Mockery\MockInterface;
use Tests\TestCase;

/**
 * Testing UserService Class
 *
 * Unit test for UserService
 *
 * @ticket Feature/DL-2
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserServiceTest extends TestCase
{
    // Rollback database changes
    use RefreshDatabase;

    /**
     * Create a mock user repository object, add reference with UserRepository Class and Mock Object class
     *
     * @var UserRepository&MockInterface
     */
    private UserRepository $userRepositoryMock;
    private PersonalAccessToken $personalAccessTokenMock;
    private UserService $userService;

    private User $userMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = $this->mock(UserRepository::class);
        $this->personalAccessTokenMock = $this->mock(PersonalAccessToken::class);
        $this->userService = new UserService($this->userRepositoryMock);

        $this->createUser();
    }

    /**
    * Create a Mock User
    *
    * @return void
    */
    private function createUser(): void
    {
        $this->userMock = new User();
        $this->userMock->id = 1;
        $this->userMock->email = 'test@test.com';
        $this->userMock->name = 'testname';
        $this->userMock->display_name = 'Test Name';
        $this->userMock->password = 'password';
    }

    public function test_authentication(): void
    {
        // Arrange
        $this->userMock->email_verified_at = Carbon::yesterday();
        $this->userRepositoryMock->shouldReceive('getByColumn')
            ->with(['email' => 'test@test.com'])
            ->once()
            ->andReturn($this->userMock);

        Hash::shouldReceive('check')
            ->with('password', $this->userMock->password)
            ->once()
            ->andReturn(true);

        $userAuthDTO = new UserAuthDTO('test@test.com', 'password');

        // Act
        $token = $this->userService->authentication($userAuthDTO);

        // When
        $this->assertNotEmpty($token);
        $this->assertEquals(1, $this->userMock->tokens()->count());
    }

    public function test_authentication_fail(): void
    {
        // Arrange
        $this->userRepositoryMock->shouldReceive('getByColumn')
            ->with(['email' => 'test@test.com'])
            ->once()
            ->andReturn($this->userMock);

        Hash::shouldReceive('check')
            ->with('failing_password', $this->userMock->password)
            ->once()
            ->andReturn(false);

        $userAuthDTO = new UserAuthDTO('test@test.com', 'failing_password');

        // Act
        $token = $this->userService->authentication($userAuthDTO);

        // When
        $this->assertEmpty($token);
    }

    public function test_registration(): void
    {
        //  Arrange
        $userCreateDTO = new UserCreateDTO('testname', 'test@test.com', 'Test Name', 'password');

        // Act
        $this->userRepositoryMock->shouldReceive('create')
            ->with($userCreateDTO)
            ->andReturn($this->userMock);

        $createdUser = $this->userService->registration($userCreateDTO);

        // When
        $this->assertInstanceOf(User::class, $createdUser);
        $this->assertEquals($userCreateDTO->getEmail(), $createdUser->email);
        $this->assertEquals($userCreateDTO->getName(), $createdUser->name);
        $this->assertEquals($userCreateDTO->getDisplayName(), $createdUser->display_name);
        $this->assertTrue(Hash::check($userCreateDTO->getPassword(), $createdUser->password));
    }

    public function test_revoke_token(): void
    {
        // Arrange
        $name = (string) config('constants.API_TOKEN');
        $token = $this->userMock->createToken($name)->plainTextToken;
        $this->userRepositoryMock->shouldReceive('findToken')
            ->with($token)
            ->andReturn($this->userMock);

        // Act
        $blnToken = $this->userService->revokeToken($token);

        // When
        $this->assertTrue($blnToken);
    }

    public function test_revoke_token_failing(): void
    {
        // Arrange
        $token = '1|unexisting-token-123';
        $this->userRepositoryMock->shouldReceive('findToken')
            ->with($token)
            ->andReturn($this->userMock);

        // Act
        $blnToken = $this->userService->revokeToken($token);

        // When
        $this->assertFalse($blnToken);
    }

    public function test_verification(): void
    {
        // Arrange
        $intId = $this->userMock->id;
        $this->userRepositoryMock->shouldReceive('findById')
            ->with($intId)
            ->andReturn($this->userMock);

        // Act
        $blnVerify = $this->userService->verification($intId);

        // When
        $this->assertTrue($blnVerify);
    }

    public function test_failing_verification(): void
    {
        // Arrange
        $intId = $this->userMock->id;
        $this->userMock->email_verified_at = Carbon::yesterday();
        $this->userRepositoryMock->shouldReceive('findById')
            ->with($intId)
            ->andReturn($this->userMock);

        // Act
        $blnVerify = $this->userService->verification($intId);

        // When
        $this->assertFalse($blnVerify);
    }

    public function test_send_verification(): void
    {
        // Arrange
        $email = $this->userMock->email;
        $this->userRepositoryMock->shouldReceive('getByColumn')
            ->with(['email' => 'test@test.com'])
            ->once()
            ->andReturn($this->userMock);

        // Act
        $blnVerify = $this->userService->sendVerification($email);

        // When
        $this->assertTrue($blnVerify);
    }

    public function test_send_verification_failing(): void
    {
        // Arrange
        $email = $this->userMock->email;
        $this->userMock->email_verified_at = Carbon::yesterday();
        $this->userRepositoryMock->shouldReceive('getByColumn')
            ->with(['email' => 'test@test.com'])
            ->once()
            ->andReturn($this->userMock);

        // Act
        $blnVerify = $this->userService->sendVerification($email);

        // When
        $this->assertFalse($blnVerify);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up mocks after each test
        \Mockery::close();
    }


}
