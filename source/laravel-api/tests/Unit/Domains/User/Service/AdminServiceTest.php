<?php

namespace Tests\Unit\Domains\User\Service;

use App\Domains\User\Contracts\UserMetaRepository;
use App\Domains\User\Contracts\UserRepository;
use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\DTO\UserMetaCreateDTO;
use App\Domains\User\Service\AdminService;
use App\Domains\User\User;
use App\Domains\User\UserMeta;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    // Rollback database changes
    use RefreshDatabase;

    /**
    * @var UserRepository&MockInterface
    */
    private UserRepository $userRepositoryMock;
    /**
     * @var UserMetaRepository&MockInterface
     */
    private UserMetaRepository $userMetaRepositoryMock;
    private AdminService $adminService;
    private User $userMock;
    private UserMeta $userMetaMock;

    private const ADMIN_KEY = 'capabilities';
    private const ADMIN_VAL = 'administrator';

    // Runs before each test cases
    protected function setup(): void
    {
        parent::setUp();
        $this->userRepositoryMock = $this->mock(UserRepository::class);
        $this->userMetaRepositoryMock = $this->mock(UserMetaRepository::class);

        $this->adminService = new AdminService(
            $this->userRepositoryMock,
            $this->userMetaRepositoryMock
        );

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
        $this->userMock->password = Hash::make('password');

        $this->userMetaMock = new UserMeta();
        $this->userMetaMock->id = 1;
        $this->userMetaMock->user_id = $this->userMock->id;
        $this->userMetaMock->meta_key = self::ADMIN_KEY;
        $this->userMetaMock->meta_value = self::ADMIN_VAL;
        $this->userMetaMock->deleted = 0;

        $this->userMock->user_meta = collect([$this->userMetaMock]);
    }


    public function test_create_admin(): void
    {
        // Arrange
        $userCreateDTO = new UserCreateDTO(
            'testname',
            'test@test.com',
            'Test Name',
            'password',
        );

        $this->userRepositoryMock->shouldReceive('create')
            ->with($userCreateDTO)
            ->andReturn($this->userMock);

        // Expecting create method in User Meta
        // Need to be set in this way since we are not passing the UserMetaCreateDTO as an argument to createAdmin
        $this->userMetaRepositoryMock->shouldReceive('create')
        ->withArgs(function ($arg) {
            return $arg instanceof UserMetaCreateDTO &&
            $arg->getUserId() === $this->userMock->id &&
            $arg->getMetaKey() ===  self::ADMIN_KEY &&
            $arg->getMetaValue() === self::ADMIN_VAL;
        })
        ->andReturn($this->userMetaMock);

        // Act
        $userMeta = $this->adminService->createAdmin($userCreateDTO);

        // When
        $this->assertInstanceOf(UserMeta::class, $userMeta);
        $this->assertEquals(self::ADMIN_KEY, $userMeta->meta_key);
        $this->assertEquals(self::ADMIN_VAL, $userMeta->meta_value);
    }

    public function test_is_admin(): void
    {
        // Arrange
        $id = $this->userMock->id;
        $aryCol = [
            'meta_key' => self::ADMIN_KEY,
            'meta_value' => self::ADMIN_VAL,
            'deleted' => 0
        ];

        $this->userRepositoryMock->shouldReceive('getByColumnWithUserMeta')
            ->withArgs([$id, $aryCol])
            ->andReturn($this->userMock);

        // Act
        $blnResult = $this->adminService->isAdmin($id);
        // when
        $this->assertTrue($blnResult);
    }

    public function test_is_admin_fail(): void
    {
        // Arrange
        $id = $this->userMock->id;
        $this->userMetaMock->meta_key = 'USER_KEY';
        $aryCol = [
            'meta_key' => self::ADMIN_KEY,
            'meta_value' => self::ADMIN_VAL,
            'deleted' => 0
        ];

        $this->userRepositoryMock->shouldReceive('getByColumnWithUserMeta')
            ->withArgs([$id, $aryCol])
            ->andReturn($this->userMock);

        // Act
        $blnResult = $this->adminService->isAdmin($id);
        // when
        $this->assertFalse($blnResult);

    }


    public function test_admin_authentication(): void
    {
        // Arrange
        $this->userRepositoryMock->shouldReceive('getByColumn')
            ->with(['email' => $this->userMock->email])
            ->once()
            ->andReturn($this->userMock);

        $userAuthDTO = new UserAuthDTO($this->userMock->email, 'password');

        // Act
        $strResToken = $this->adminService->authentication($userAuthDTO);

        // When
        $this->assertNotEmpty($strResToken);
        $this->assertEquals(1, $this->userMock->tokens()->count());
    }

    public function test_admin_authentication_meta_fail(): void
    {
        // Arrange
        $this->userMetaMock->meta_key = "FAIL_KEY";
        $this->userRepositoryMock->shouldReceive('getByColumn')
            ->with(['email' => $this->userMock->email])
            ->once()
            ->andReturn($this->userMock);

        $userAuthDTO = new UserAuthDTO($this->userMock->email, 'password');

        // Act
        $strResToken = $this->adminService->authentication($userAuthDTO);

        // When
        $this->assertEmpty($strResToken);
        $this->assertEquals(0, $this->userMock->tokens()->count());
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up mocks after each test
        \Mockery::close();
    }

}
