<?php

declare(strict_types=1);

namespace App\Domains\User\Service;

use App\Domains\User\Contracts\UserMetaRepository;
use App\Domains\User\Contracts\UserRepository;
use App\Domains\User\DTO\UserAuthDTO;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\DTO\UserMetaCreateDTO;
use App\Domains\User\UserMeta;
use Carbon\Carbon;

/**
 * Admin Domain Service
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class AdminService
{
    public const ADMIN_KEY = 'capabilities';
    public const ADMIN_VAL = 'administrator';
    private string $adminToken;

    public function __construct(
        private UserRepository $userRepository,
        private UserMetaRepository $userMetaRepository
    ) {
        $this->adminToken = config('constants.ADMIN_TOKEN');
    }

    /**
     * Create a user with an admin meta
     *
     * @ticket Feature/DL-2
     *
     * @param UserCreateDTO $userCreateDTO
     * @return UserMeta
     */
    public function createAdmin(UserCreateDTO $userCreateDTO): UserMeta
    {
        // Create User
        $user = $this->userRepository->create($userCreateDTO);
        // Assign user as administrator
        $userMetaCreateDTO = new UserMetaCreateDTO(
            $user->id,
            self::ADMIN_KEY,
            self::ADMIN_VAL
        );
        // Create User Meta
        $userMeta = $this->userMetaRepository->create($userMetaCreateDTO);

        return $userMeta;
    }

    /**
     * Fetch user by id and check if admin
     *
     * @ticket Feature/DL-2
     *
     * @param integer $userId
     * @return boolean
     */
    public function isAdmin(int $userId): bool
    {
        $aryMeta = [
            'meta_key' => self::ADMIN_KEY,
            'meta_value' => self::ADMIN_VAL,
            'deleted' => 0
        ];

        $user = $this->userRepository->getByColumnWithUserMeta($userId, $aryMeta);
        $userMeta = $user->user_meta->first();

        // Check if the user meta is an instace of UserMeta
        if (!($userMeta instanceof UserMeta)) {
            return false;
        }

        if ($userMeta->meta_key != self::ADMIN_KEY || $userMeta->meta_value != self::ADMIN_VAL) {
            return false;
        }

        return true;
    }

    /**
     * Admin authentication
     *
     * @ticket Feature/DL-2
     *
     * @param UserAuthDTO $userAuthDTO
     * @return string
     */
    public function authentication(UserAuthDTO $userAuthDTO): string
    {
        $user = $this->userRepository->getByColumn(['email' => $userAuthDTO->getEmail()]);

        $userMeta = $user->user_meta->first();
        if (!($userMeta instanceof UserMeta)) {
            return '';
        }

        if ($userMeta->meta_key != self::ADMIN_KEY || $userMeta->meta_value != self::ADMIN_VAL) {
            return '';
        }

        if ($user->tokens->isNotEmpty()) {
            $user->tokens()->delete();
        }

        return $user->createToken($this->adminToken, ['*'], Carbon::now()->addMinutes(60))->plainTextToken;
    }

}
