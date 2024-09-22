<?php

declare(strict_types=1);

namespace App\Repositories\Mysql\Post;

use App\Domains\Post\Contracts\SlideRepository as ISlideRepository;
use App\Domains\Post\DTO\SlideCreateDto;
use App\Domains\Post\Slides\Slides;
use Illuminate\Database\Eloquent\Builder;

class SlideRepository implements ISlideRepository
{
    public function __construct(private Slides $slides)
    {
    }

    private function queryWithMeta(): Builder
    {
        return $this->slides->query()->with('post_meta');
    }

    public function create(SlideCreateDto $slideCreateDto): Slides
    {
        return $this->slides->query()->create($slideCreateDto->getPostArray());
    }

    public function get(int $id): Slides
    {
        return $this->queryWithMeta()->find($id);
    }

    public function getByColumn(array $column, mixed $operator = "="): Slides
    {
        $query = $this->queryWithMeta();
        foreach ($column as $field => $value) {
            $query->where($field, $operator, $value);
        }
        return $query->first();
    }



}
