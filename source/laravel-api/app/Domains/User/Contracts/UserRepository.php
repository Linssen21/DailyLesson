<?php

declare(strict_types=1);

namespace App\Domains\User\Contracts;

use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\User;
use Illuminate\Support\Collection;

/**
 * User repository
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
interface UserRepository
{
    public function getByColumns(array $aryColumn, mixed $mixOperator = "="): Collection;
    public function getByColumn(array $aryColumn, mixed $mixOperator = "="): ?User;
    public function getByColumnWithUserMeta(int $userId, array $aryColumn, mixed $mixOperator = "="): User;
    public function findById(int $intId): ?User;
    public function create(UserCreateDTO $userCreateDTO): User;
    public function update(int $intId, array $aryColumn): void;
    public function delete(int $intId): bool;
    public function updateOrCreate(array $attributes, array $values): User;
}
