<?php

declare(strict_types=1);

namespace Tests\Unit\Middleware;

use App\Domains\User\Contracts\UserMetaRepository;
use App\Domains\User\Contracts\UserRepository;
use App\Domains\User\Service\AdminService;
use App\Domains\User\User;
use App\Domains\User\UserMeta;
use App\Http\Middleware\AdminAuthentication;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Http\Request;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticationTest extends TestCase
{
    // Rollback database changes
    use DatabaseTransactions;

    private AdminService $adminService;
    /**
    * @var UserRepository&MockInterface
    */
    private UserRepository $userRepositoryMock;
    /**
     * @var UserMetaRepository&MockInterface
     */
    private UserMetaRepository $userMetaRepositoryMock;
    private User $userMock;
    private UserMeta $userMetaMock;

    protected function setUp(): void
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

    private function createUser(): void
    {
        $this->userMock = new User();
        $this->userMock->id = 55;
        $this->userMock->email = 'test@test.com';
        $this->userMock->name = 'testname';
        $this->userMock->display_name = 'Test Name';
    }

    private function createAdminMeta(): void
    {
        $this->userMetaMock = new UserMeta();
        $this->userMetaMock->id = 1;
        $this->userMetaMock->user_id = $this->userMock->id;
        $this->userMetaMock->meta_key = AdminService::ADMIN_KEY;
        $this->userMetaMock->meta_value = AdminService::ADMIN_VAL;
        $this->userMetaMock->deleted = 0;

        $this->userMock->user_meta = collect([$this->userMetaMock]);
    }

    /**
     * A basic unit test example.
     */
    public function test_admin_authentication_no_token(): void
    {
        // Arrange
        $request = Request::create('/api/v2/slide/upload');

        $next = function () {
            return response('This is an upload route');
        };

        // Act
        $middleware = new AdminAuthentication($this->adminService);
        $response = $middleware->handle($request, $next);

        // Assert
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertEquals('No bearer token', json_decode($response->getContent())->message);
    }

    public function test_admin_authentication_expired_token(): void
    {
        // Arrange
        $expiredToken = $this->userMock->createToken(config('constants.ADMIN_TOKEN'), ['*'], Carbon::now())->plainTextToken;
        $request = Request::create('/api/v2/slide/upload', 'POST', [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $expiredToken,
        ]);
        $next = function () {
            return response('This is an upload route');
        };

        // Act
        $middleware = new AdminAuthentication($this->adminService);
        $response = $middleware->handle($request, $next);

        // Assert
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertEquals('Authentication token expired', json_decode($response->getContent())->message);
    }


    public function test_admin_authentication_user_not_admin(): void
    {
        // Arrange
        $token = $this->userMock->createToken(config('constants.ADMIN_TOKEN'), ['*'], Carbon::now()->addMinutes(60))->plainTextToken;
        $request = Request::create('/api/v2/slide/upload', 'POST', [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        $next = function () {
            return response('This is an upload route');
        };

        $aryCol = [
            'meta_key' => AdminService::ADMIN_KEY,
            'meta_value' => AdminService::ADMIN_VAL,
            'deleted' => 0
        ];

        $this->userRepositoryMock->shouldReceive('getByColumnWithUserMeta')
            ->withArgs([$this->userMock->id, $aryCol])
            ->andReturn($this->userMock);

        // Act
        $middleware = new AdminAuthentication($this->adminService);
        $response = $middleware->handle($request, $next);

        // Assert
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertEquals('The user is not an admin', json_decode($response->getContent())->message);
    }

    public function test_admin_authentication_success(): void
    {
        // Arrange
        $this->createAdminMeta();
        $token = $this->userMock->createToken(config('constants.ADMIN_TOKEN'), ['*'], Carbon::now()->addMinutes(60))->plainTextToken;
        $request = Request::create('/api/v2/slide/upload', 'POST', [], [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
        ]);

        $next = function () {
            return response('This is an upload route');
        };

        $aryCol = [
            'meta_key' => AdminService::ADMIN_KEY,
            'meta_value' => AdminService::ADMIN_VAL,
            'deleted' => 0
        ];

        $this->userRepositoryMock->shouldReceive('getByColumnWithUserMeta')
            ->withArgs([$this->userMock->id, $aryCol])
            ->andReturn($this->userMock);

        // Act
        $middleware = new AdminAuthentication($this->adminService);
        $response = $middleware->handle($request, $next);

        // Assert
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('This is an upload route', $response->getContent());
    }

}
