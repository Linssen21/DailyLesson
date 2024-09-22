<?php

declare(strict_types=1);

namespace App\Repositories\Mysql\Post;

use App\Common\QueryParams;
use App\Domains\Post\Common\Post;
use App\Domains\Post\Contracts\PostRepository as IPostRepository;
use App\Domains\Post\DTO\PostDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PostRepository implements IPostRepository
{
    public function __construct(private Post $post)
    {
    }

    public function create(PostDto $postDto): Post
    {
        return $this->post->query()->create($postDto->toArray());
    }

    public function getByColumns(array $column, mixed $operator = "="): Collection
    {
        $queryPost = $this->post->query();
        foreach ($column as $field => $value) {
            $queryPost->where($field, $operator, $value);
        }
        return $queryPost->get();
    }

    public function getAllByColumn(QueryParams $params): Collection
    {
        $query = $this->post->query()->with('post_meta');
        $query->select($params->getFields());

        $columns = $params->getColumns();
        if ($columns->isNotEmpty()) {
            foreach ($columns as $column) {
                $query->where($column->getColumn(), $column->getOperator(), $column->getValue(), $column->getBoolean());
            }
        }

        $order = $params->getOrder();
        foreach ($order as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        $offset = ($params->getPage() - 1) * $params->getPerPage();

        return $query->skip($offset)->take($params->getPerPage())->get();
    }

    public function update(int $id, array $column): bool
    {
        $row = $this->post->query()->whereId($id)->update($column);
        return $row > 0;
    }

    public function find(int $id): Post
    {
        return $this->post->active()->whereId($id)->first();
    }

    public function getWithPagination(QueryParams $params): LengthAwarePaginator
    {
        $query = $this->post->active()->query()->with('post_meta');
        $query->select($params->getFields());

        $operator = $params->getOperator();
        $columns = $params->getColumns();

        foreach ($columns as $field => $value) {
            $query->where($field, $operator, $value);
        }

        $order = $params->getOrder();
        foreach ($order as $field => $direction) {
            $query->orderBy($field, $direction);
        }

        return $query->paginate(perPage: $params->getPerPage(), page: $params->getPage());
    }
}
