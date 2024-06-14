<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\Service\UserService as UserDomainService;
use Illuminate\Support\Facades\DB;
use Log;

/**
 * UserService
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserService
{
    public function __construct(
        private UserDomainService $userDomainService,
    ) {
    }

    /**
     * Authenticate and create
     *
     * @ticket Feature/DL-2
     *
     * @param UserAuthDTO $userAuthDTO
     * @return array
     */
    public function authentication(UserAuthDTO $userAuthDTO): array
    {
        try {
            DB::beginTransaction();
            $strToken = $this->userDomainService->authentication($userAuthDTO);
            if (empty($strToken)) {
                return [
                    'token' => '',
                    'message' => 'Authentication failed, please check your username and password',
                    'status' => config('constants.STATUS_FAILED')
                ];
            }
            DB::commit();
            return [
                'token' => $strToken,
                'message' => 'Authentication successful',
                'status' => config('constants.STATUS_SUCCESS')
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Authentication] An error occurred during authentication',
                ['data' => json_encode($userAuthDTO), 'message: ' => $th->getMessage()]
            );
            return [
                'token' => '',
                'message' => 'Authentication failed',
                'status' => config('constants.STATUS_FAILED')
            ];
        }
    }

    /**
     * Register new user
     *
     * @ticket Feature/DL-2
     *
     * @param UserCreateDTO $userCreateDTO
     * @return array
     */
    public function registration(UserCreateDTO $userCreateDTO): array
    {
        try {
            DB::beginTransaction();
            $this->userDomainService->registration($userCreateDTO);
            DB::commit();
            return [
                'message' => 'Registration successful',
                'status' => config('constants.STATUS_SUCCESS')
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Registration] An error occurred during registration',
                ['data' => $userCreateDTO->toArray(),'message' => $th->getMessage()]
            );
            return [
                'message' => 'Registration failed',
                'status' => config('constants.STATUS_FAILED')
            ];
        }
    }

    /**
     * Delete Token
     *
     * @ticket Feature/DL-2
     *
     * @param string $strToken
     * @return array
     */
    public function revokeToken(string $strToken): array
    {
        try {
            DB::beginTransaction();
            $this->userDomainService->revokeToken($strToken);
            DB::commit();
            return [
                'message' => 'Logout successful',
                'status' => config('constants.STATUS_SUCCESS')
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Revoke token ] An error occurred revoking the token',
                ['token' => $strToken,'message: ' => $th->getMessage()]
            );
            return [
                'message:' => 'Logout failed',
                'status' => config('constants.STATUS_FAILED')
            ];
        }
    }

    public function verify(int $intId): array
    {
        try {
            DB::beginTransaction();
            $blnVerificaiton = $this->userDomainService->verification($intId);
            if (!$blnVerificaiton) {
                Log::channel('applog')->error('[Verification] Verification failed, user already verified');
                return [
                    'message' => 'Verification failed, user already verified',
                    'status' => config('constants.STATUS_FAILED')
                ];
            }
            DB::commit();
            Log::channel('applog')->info(
                '[Verification] User successfully verified',
                ['id' => $intId]
            );
            return [
                'message' => 'Verification successful',
                'status' => config('constants.STATUS_SUCCESS')
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Verification] An error occurred during verificaiton',
                ['id' => $intId ,'message: ' => $th->getMessage()]
            );
            return [
                'message' => 'Verification failed',
                'status' => config('constants.STATUS_FAILED')
            ];
        }
    }

    public function sendVerification(string $email): array
    {
        try {
            $this->userDomainService->sendVerification($email);
            return [
                'message' => 'Verification email sent, please check your inbox',
                'status' => config('constants.STATUS_SUCCESS')
            ];
        } catch (\Throwable $th) {
            Log::channel('applog')->error(
                '[Send Verification] An error occurred sending verificaiton',
                ['email' => $email ,'message: ' => $th->getMessage()]
            );
            return [
                'message' => 'Send Verification failed',
                'status' => config('constants.STATUS_FAILED')
            ];
        }
    }
}
