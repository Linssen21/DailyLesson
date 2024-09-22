<?php

declare(strict_types=1);

namespace App\Domains\Post\Contracts;

use App\Domains\Post\Common\PostMeta;
use App\Domains\Post\DTO\PostMetaDto;
use Illuminate\Support\Collection;

interface PostMetaRepository
{
    public function create(PostMetaDto $postMetaDto): PostMeta;
    public function update(int $post_id, array $column): bool;
    public function updateWithMeta(int $post_id, string $key, array $column): bool;

    /**
     * Create many post meta
     *
     * @param Collection<PostMetaDto> $postMeta
     * @return Collection
     */
    public function createMany(Collection $postMeta): Collection;
    public function getByColumns(array $column, mixed $operator = "="): Collection;
}
