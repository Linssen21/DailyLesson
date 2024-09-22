<?php

declare(strict_types=1);

namespace App\Domains\Post\Slides;

use App\Domains\Post\Common\Post;
use App\Domains\Post\DTO\SlideCreateDto;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slides extends Post
{
    public const TYPE = 'slide';

    public function post_meta(): HasMany
    {
        return $this->hasMany(SlidesMeta::class, 'post_id', 'id');
    }

    public function create(SlideCreateDto $slideCreateDto): self
    {
        return $this->query()->create($slideCreateDto->getPostArray());
    }
}
