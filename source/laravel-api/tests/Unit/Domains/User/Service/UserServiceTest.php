<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User\Service;

use App\Domains\User\Contracts\UserMetaRepository;
use App\Domains\User\Contracts\UserRepository;
use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\DTO\UserMetaCreateDTO;
use App\Domains\User\Service\UserService;
use App\Domains\User\User;
use App\Domains\User\UserMeta;
use App\Domains\User\ValueObjects\Status;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Two\User as SocialUser;
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
    /**
     * Create a mock user meta repository object, add reference with UserMetaRepository Class and Mock Object class
     *
     * @var UserMetaRepository&MockInterface
     */
    private UserMetaRepository $userMetaRepositoryMock;
    private PersonalAccessToken $personalAccessTokenMock;
    private UserService $userService;

    private User $userMock;
    private SocialUser $socialUserMock;
    private Status $statusMock;
    private UserMeta $userMetaMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepositoryMock = $this->mock(UserRepository::class);
        $this->userMetaRepositoryMock = $this->mock(UserMetaRepository::class);
        $this->personalAccessTokenMock = $this->mock(PersonalAccessToken::class);
        $this->userService = new UserService($this->userRepositoryMock, $this->userMetaRepositoryMock);
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

    private function prepareSocialUser(): void
    {
        $this->socialUserMock = $this->mock(SocialUser::class);
        $this->statusMock = $this->mock(Status::class);
        $this->userMetaMock = $this->mock(UserMeta::class);
        $this->socialUserMock = new SocialUser();
        $this->socialUserMock->id = '1';
        $this->socialUserMock->name = 'testname';
        $this->socialUserMock->email = 'test@test.com';
        $this->statusMock = new Status(1);
        Carbon::setTestNow(Carbon::parse('2024-01-01 00:00:00'));
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

        // Assert
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

        // Assert
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

        // Assert
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

        // Assert
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

        // Assert
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

        // Assert
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

        // Assert
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

        // Assert
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

        // Assert
        $this->assertFalse($blnVerify);
    }

    public function test_register_with_social(): void
    {
        // Arrange
        $provider = 'google';
        $meta_key = 'user_social';
        $this->prepareSocialUser();

        $this->userMetaRepositoryMock->shouldReceive('fetchBySocialMeta')
            ->with($meta_key, $provider, $this->socialUserMock->getId())
            ->once();

        $this->userRepositoryMock->shouldReceive('updateOrCreate')
        ->with(
            ['email' => $this->socialUserMock->getEmail()],
            [
            'email' => $this->socialUserMock->getEmail(),
            'name' => $this->socialUserMock->getName(),
            'display_name' => $this->socialUserMock->getName(),
            'email_verified_at' => now(),
            'status' => $this->statusMock
            ]
        )->once()
        ->andReturn($this->userMock);

        $arySocialMeta = $this->socialUserMock->getRaw();
        $arySocialMeta['provider'] = $provider;

        $this->userMetaRepositoryMock->shouldReceive('create')
        ->withArgs(function ($arg) use ($meta_key, $arySocialMeta) {
            return $arg instanceof UserMetaCreateDTO &&
            $arg->getUserId() === $this->userMock->id &&
            $arg->getMetaKey() ===  $meta_key &&
            $arg->getMetaValue() === json_encode([$arySocialMeta]);
        })->once();

        // Act
        $token = $this->userService->registerWithSocial($provider, $this->socialUserMock);

        // Assert
        $this->assertNotEmpty($token);
        $this->assertEquals(1, $this->userMock->tokens()->count());
    }

    public function test_register_with_existing_social(): void
    {
        // Arrange
        $provider = 'google';
        $meta_key = 'user_social';
        $this->prepareSocialUser();

        $arySocialMeta = $this->socialUserMock->getRaw();
        $arySocialMeta['provider'] = $provider;

        // Mock existing user_social meta
        $socialMeta = new UserMeta();
        $socialMeta->user_id = $this->userMock->id;
        $socialMeta->meta_key = $meta_key;
        $socialMeta->meta_value = $arySocialMeta;

        $this->userMetaRepositoryMock->shouldReceive('fetchBySocialMeta')
            ->with($meta_key, $provider, $this->socialUserMock->getId())
            ->once()
            ->andReturn($socialMeta);


        $this->userRepositoryMock->shouldReceive('getByColumn')
            ->with(['email' => $this->socialUserMock->getEmail()])
            ->once()
            ->andReturn($this->userMock);

        $this->userMetaRepositoryMock->shouldReceive('create')
        ->withArgs(function ($arg) use ($meta_key, $arySocialMeta) {
            return $arg instanceof UserMetaCreateDTO &&
            $arg->getUserId() === $this->userMock->id &&
            $arg->getMetaKey() ===  $meta_key &&
            $arg->getMetaValue() === json_encode([$arySocialMeta]);
        })->once();

        // Act
        $token = $this->userService->registerWithSocial($provider, $this->socialUserMock);

        // Assert
        $this->assertNotEmpty($token);
        $this->assertEquals(1, $this->userMock->tokens()->count());
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up mocks after each test
        \Mockery::close();
    }


}
