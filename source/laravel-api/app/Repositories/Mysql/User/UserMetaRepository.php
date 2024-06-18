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
        return $this->userMeta->query()->create($userMetaCreateDTO->toArray());
    }

    /**
     * Fetch social meta by id and provider
     *
     * @ticket Feature/DL-2
     *
     * @param string $key
     * @param string $provider
     * @param string $id
     * @return UserMeta|null
     */
    public function fetchBySocialMeta(string $key, string $provider, string $id): ?UserMeta
    {
        return $this->userMeta->query()->where(function ($query) use ($key, $id, $provider) {
            return $query->whereRaw("meta_key = ?", $key)
                ->whereRaw('JSON_VALID(meta_value)')
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(meta_value, '$[0].id')) = ?", $id)
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(meta_value, '$[0].provider')) = ?", [$provider]);

        })->first();
    }

}
