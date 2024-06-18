<?php

declare(strict_types=1);

namespace App\Domains\User\Service;

use App\Domains\User\Contracts\UserMetaRepository;
use App\Domains\User\Contracts\UserRepository;
use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\DTO\UserMetaCreateDTO;
use App\Domains\User\User;
use App\Domains\User\ValueObjects\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Two\User as UserSocialite;

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
        private UserMetaRepository $userMetaRepository
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

    /**
     * Authenticate user by social media and return token
     *
     * @param string $provider
     * @param UserSocialite $socialUser
     * @return string
     */
    public function registerWithSocial(string $provider, UserSocialite $socialUser): string
    {
        // Check by user_meta
        $socialMeta = $this->userMetaRepository->fetchBySocialMeta('user_social', $provider, $socialUser->getId());

        if ($socialMeta) {
            $socialMeta->delete();
            $existUser = $this->userRepository->getByColumn(['email' => $socialUser->getEmail()]);
            return $this->generateSocialToken($provider, $existUser, $socialUser);
        }

        $newUser = $this->userRepository->updateOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName(),
                'display_name' => $socialUser->getName(),
                'email_verified_at' => Carbon::now(),
                'status' => new Status(1)
            ]
        );

        return $this->generateSocialToken($provider, $newUser, $socialUser);
    }

    /**
     * Generate token for user using social
     *
     * @param string $provider
     * @param User $user
     * @param UserSocialite $socialUser
     * @return string
     */
    private function generateSocialToken(string $provider, User $user, UserSocialite $socialUser): string
    {
        $arySocialMeta = $socialUser->getRaw();
        $arySocialMeta['provider'] = $provider;
        $this->userMetaRepository->create(
            new UserMetaCreateDTO($user->id, 'user_social', json_encode([$arySocialMeta]))
        );

        if ($user->tokens->isNotEmpty()) {
            $user->tokens()->delete();
        }

        return $user->createToken($this->apiToken, ['*'], Carbon::now()->addSeconds($user->expiresIn))->plainTextToken;
    }

}
