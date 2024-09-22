<?php

declare(strict_types=1);

namespace App\Repositories\Mysql\Post;

use App\Domains\Post\Common\PostMeta;
use App\Domains\Post\Contracts\PostMetaRepository as IPostMetaRepository;
use App\Domains\Post\DTO\PostMetaDto;
use Illuminate\Support\Collection;

class PostMetaRepository implements IPostMetaRepository
{
    public function __construct(private PostMeta $postMeta)
    {
    }

    public function create(PostMetaDto $postMetaDto): PostMeta
    {
        return $this->postMeta->query()->create($postMetaDto->toArray());
    }

    public function update(int $post_id, array $column): bool
    {
        $row = $this->postMeta->query()
            ->where('post_id', '=', $post_id)
            ->update($column);
        return $row > 0;
    }

    public function updateWithMeta(int $post_id, string $key, array $column): bool
    {
        $row = $this->postMeta->query()
            ->where('post_id', '=', $post_id)
            ->where('meta_key', '=', $key)
            ->update($column);
        return $row > 0;
    }

    public function createMany(Collection $postMeta): Collection
    {
        return $postMeta->map(function (PostMetaDto $postMetaDto) {
            return $this->create($postMetaDto);
        });
    }

    public function getByColumns(array $column, mixed $operator = "="): Collection
    {
        $query = $this->postMeta->query();
        foreach ($column as $field => $value) {
            $query->where($field, $operator, $value);
        }
        return $query->get();
    }

}
