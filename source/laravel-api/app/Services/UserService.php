<?php

declare(strict_types=1);

namespace App\Services;

use App\Common\Service;
use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\Service\UserService as UserDomainService;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

/**
 * UserService
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserService extends Service
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
                return $this->tokenResponse('', 'Authentication failed, please check your username and password', false);
            }
            DB::commit();
            return $this->tokenResponse($strToken, 'Authentication successful', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Authentication] An error occurred during authentication',
                ['data' => json_encode($userAuthDTO), 'message: ' => $th->getMessage()]
            );

            return $this->tokenResponse('', 'Authentication failed', false);
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
            return $this->messageReponse('Registration successful', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Registration] An error occurred during registration',
                ['data' => $userCreateDTO->toArray(),'message' => $th->getMessage()]
            );
            return $this->messageReponse('Registration failed', false);
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
            return $this->messageReponse('Logout successful', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Revoke token ] An error occurred revoking the token',
                ['token' => $strToken,'message: ' => $th->getMessage()]
            );
            return $this->messageReponse('Logout failed', false);
        }
    }

    public function verify(int $intId): array
    {
        try {
            DB::beginTransaction();
            $blnVerificaiton = $this->userDomainService->verification($intId);
            if (!$blnVerificaiton) {
                $verificationMsg = 'Verification failed, user already verified';
                Log::channel('applog')->error('[Verification] ' . $verificationMsg);
                return $this->messageReponse($verificationMsg, false);
            }
            DB::commit();
            Log::channel('applog')->info(
                '[Verification] User successfully verified',
                ['id' => $intId]
            );
            return $this->messageReponse('Verification successful', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Verification] An error occurred during verificaiton',
                ['id' => $intId ,'message: ' => $th->getMessage()]
            );
            return $this->messageReponse('Verification failed', false);
        }
    }

    public function sendVerification(string $email): array
    {
        try {
            $this->userDomainService->sendVerification($email);
            return $this->messageReponse('Verification email sent, please check your inbox', true);
        } catch (\Throwable $th) {
            Log::channel('applog')->error(
                '[Send Verification] An error occurred sending verificaiton',
                ['email' => $email ,'message: ' => $th->getMessage()]
            );
            return $this->messageReponse('Send Verification failed', false);
        }
    }

    /**
     * Execute redirect to provider
     *
     * @ticket Feature/DL-2
     *
     * @param string $provider
     * @return array
     */
    public function redirectToProvider(string $provider): array
    {
        try {
            if (!in_array($provider, ['google', 'facebook'])) {
                return $this->urlReponse('', 'Provider is not supported', false);
            }

            /** @disregard uses abstraction and depends on One or Two Implementation */
            $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
            return $this->urlReponse($url, 'Url is generated', true);
        } catch (\Throwable $th) {
            Log::channel('applog')->error(
                '[Redirect] An error occurred during redirection',
                ['message: ' => $th->getMessage()]
            );
        }
    }

    /**
     * Callback function for login with socials
     *
     * @ticket Feature/DL-2
     *
     * @param string $provider
     * @return array
     */
    public function handleProviderCallback(string $provider): array
    {
        try {
            DB::beginTransaction();
            if (!in_array($provider, ['google', 'facebook'])) {
                return $this->tokenResponse('', 'Provider is not supported', false);
            }

            /** @disregard uses abstraction and depends on One or Two Implementation */
            /** @var \Laravel\Socialite\Two\User */
            $user = Socialite::driver($provider)->stateless()->user();

            $token = $this->userDomainService->registerWithSocial($provider, $user);

            if (empty($token)) {
                return $this->tokenResponse('', 'Authentication failed, please check your username and password', false);
            }

            DB::commit();
            return $this->tokenResponse($token, 'Authentication successful', true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('applog')->error(
                '[Authentication] An error occurred during authentication',
                ['data' => json_encode($provider), 'message: ' => $th->getMessage()]
            );

            return $this->tokenResponse('', 'Authentication failed', false);
        }
    }
}
