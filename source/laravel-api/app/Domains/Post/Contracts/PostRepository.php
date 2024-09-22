<?php

declare(strict_types=1);

namespace App\Domains\Post\Contracts;

use App\Common\QueryParams;
use App\Domains\Post\Common\Post;
use App\Domains\Post\DTO\PostDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PostRepository
{
    public function create(PostDto $postDto): Post;
    public function getByColumns(array $column, mixed $operator = "="): Collection;
    public function getAllByColumn(QueryParams $params): Collection;
    public function update(int $id, array $column): bool;
    public function find(int $id): Post;
    public function getWithPagination(QueryParams $params): LengthAwarePaginator;
}
