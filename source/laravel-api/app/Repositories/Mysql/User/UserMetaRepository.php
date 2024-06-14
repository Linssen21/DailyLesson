<?php

declare(strict_types=1);

namespace App\Repositories\Mysql\User;

use App\Domains\User\Contracts\UserMetaRepository as UserMetaRepositoryInterface;
use App\Domains\User\DTO\UserMetaCreateDTO;
use App\Domains\User\UserMeta;

class UserMetaRepository implements UserMetaRepositoryInterface
{
    public function __construct(private UserMeta $userMeta)
    {
    }

    public function create(UserMetaCreateDTO $userMetaCreateDTO): UserMeta
    {
        $test = $this->userMeta->query()->create($userMetaCreateDTO->toArray());

        return $test;
    }
}
