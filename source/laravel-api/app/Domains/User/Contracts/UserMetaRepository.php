<?php

declare(strict_types=1);

namespace App\Domains\User\Contracts;

use App\Domains\User\DTO\UserMetaCreateDTO;
use App\Domains\User\UserMeta;

/**
 * User Meta repository
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
interface UserMetaRepository
{
    public function create(UserMetaCreateDTO $userMetaCreateDTO): UserMeta;
    public function fetchBySocialMeta(string $key, string $provider, string $id): ?UserMeta;
}
