<?php

declare(strict_types=1);

namespace App\Repositories\Mysql\User;

use App\Domains\User\Contracts\UserRepository as UserRepositoryInterface;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\User;
use Illuminate\Support\Collection;

/**
 * Fetch user repository implementation
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Constructor Dependency Injection
     *
     * @ticket Feature/DL-2
     *
     * @param User $userModel
     */
    public function __construct(private User $userModel)
    {
    }

    /**
     * Fetch by columns and returns user collection
     *
     * @ticket Feature/DL-2
     *
     * @param array $aryColumn
     * @param mixed $mixOperator
     * @return Collection
     */
    public function getByColumns(array $aryColumn, mixed $mixOperator = "="): Collection
    {
        $queryUser = $this->userModel->query();
        foreach ($aryColumn as $columnField => $columnValue) {
            // Use where() or orWhere() based on the presence of previous conditions
            if ($queryUser->getQuery()->wheres) {
                $queryUser->orWhere($columnField, $mixOperator, $columnValue);
            } else {
                $queryUser->where($columnField, $mixOperator, $columnValue);
            }
        }
        return $queryUser->get();
    }

    /**
     * Fetch by columns and returns a user
     *
     * @ticket Feature/DL-2
     *
     * @param array $aryColumn
     * @param mixed $mixOperator
     * @return Collection
     */
    public function getByColumn(array $aryColumn, mixed $mixOperator = "="): ?User
    {
        $queryUser = $this->userModel->query();
        foreach ($aryColumn as $columnField => $columnValue) {
            // Use where() or orWhere() based on the presence of previous conditions
            if ($queryUser->getQuery()->wheres) {
                $queryUser->orWhere($columnField, $mixOperator, $columnValue);
            } else {
                $queryUser->where($columnField, $mixOperator, $columnValue);
            }
        }
        return $queryUser->first();
    }


    /**
     * Fetch by columns with user meta and return user collection
     *
     * @ticket Feature/DL-2
     *
     * @param array $aryColumn
     * @param mixed $mixOperator
     * @return Collection
     */
    public function getByColumnWithUserMeta(int $userId, array $aryColumn, mixed $mixOperator = "="): User
    {
        // $query = DB::table('users as u')
        //     ->join('user_meta as um', 'u.id', '=', 'um.user_id')
        //     ->select([
        //         'u.*',
        //         'um.id AS user_meta_id',
        //         'um.user_id AS meta_user_id',
        //         'um.meta_key',
        //         'um.meta_value',
        //         'um.deleted',
        //         'um.created_at AS meta_created_at',
        //         'um.updated_at AS meta_updated_at'
        //     ])
        //     ->where('u.id', $userId);

        // foreach ($aryColumn as $column => $value) {
        //     $query->where($column, $value);
        // }

        // return $query->first();
        $queryUser = $this->userModel
            ->with(['user_meta' => function ($query) use ($aryColumn) {
                foreach ($aryColumn as $column => $value) {
                    $query->where($column, $value);
                }
                $query->limit(1);
            }])
            ->where('id', $userId);

        return $queryUser->firstOrNew([]);
    }

    /**
     * Strictly find a single user
     *
     * @ticket Feature/DL-2
     *
     * @param array $aryColumn
     * @param mixed $mixOperator
     * @return Collection
     */
    public function findById(int $intId): ?User
    {
        return $this->userModel->query()->where('id', $intId)->first();
    }

    public function update(int $intId, array $aryColumn): void
    {
        $this->userModel->whereId($intId)->update($aryColumn);
    }

    /**
     * Delete user by id and return true if deleted
     *
     * @ticket Feature/DL-2
     *
     * @param integer $intId
     * @return boolean
     */
    public function delete(int $intId): bool
    {
        $user = $this->userModel->whereId($intId);
        $user->softDelete();

        return $user->isDeleted();
    }


    public function create(UserCreateDTO $userCreateDTO): User
    {
        return $this->userModel->createUser($userCreateDTO);
    }
}
