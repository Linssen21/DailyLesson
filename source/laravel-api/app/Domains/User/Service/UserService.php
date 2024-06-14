<?php

declare(strict_types=1);

namespace App\Domains\User\Service;

use App\Domains\User\Contracts\UserRepository;
use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * User Domain Service
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserService
{
    private string $apiToken;

    public function __construct(
        private UserRepository $userRepository,
    ) {
        $this->apiToken = config('constants.API_TOKEN');
    }

    /**
     * Check user if exist, compare password and return auth token
     *
     * @ticket Feature/DL-2
     *
     * @param UserAuthDTO $userAuthDTO
     * @return string
     */
    public function authentication(UserAuthDTO $userAuthDTO): string
    {
        $user = $this->userRepository->getByColumn(['email' => $userAuthDTO->getEmail()]);
        if (!$user || !Hash::check($userAuthDTO->getPassword(), $user->password)) {
            return '';
        }

        if (!$user->isVerified()) {
            return '';
        }

        if ($user->tokens->isNotEmpty()) {
            $user->tokens()->delete();
        }

        return $user->createToken($this->apiToken)->plainTextToken;
    }

    /**
    * Register new user and return api token
    *
    * @ticket Feature/DL-2
    *
    * @param UserCreateDTO $userCreateDTO
    * @return User
    */
    public function registration(UserCreateDTO $userCreateDTO): User
    {
        return $this->userRepository->create($userCreateDTO);
    }

    /**
    * Check for an existing token and delete it
    *
    * @ticket Feature/DL-2
    *
    * @param string $strUserMail
    * @param string $strPassword
    * @return string
    */
    public function revokeToken(string $strToken): bool
    {
        $objToken = PersonalAccessToken::findToken($strToken);
        if (!$objToken) {
            return false;
        }

        $objToken->delete();
        return true;
    }

    /**
     * Fetch user by id and verify the user
     *
     * @ticket Feature/DL-2
     *
     * @param integer $intId
     * @return boolean
     */
    public function verification(int $intId): bool
    {
        $user = $this->userRepository->findById($intId);
        if (!$user || $user->isVerified()) {
            return false;
        }

        $user->markAsVerified();
        return true;
    }

    /**
     * Check if user is verified and if not will send an email verification
     *
     * @ticket Feature/DL-2
     *
     * @param string $email
     * @return boolean
     */
    public function sendVerification(string $email): bool
    {
        $user = $this->userRepository->getByColumn(['email' => $email]);
        if (!$user || $user->isVerified()) {
            return false;
        }

        $user->sendEmailVerificationNotification();
        return true;
    }

}
