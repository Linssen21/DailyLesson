<?php

declare(strict_types=1);

namespace App\Domains\Common\Post\Slides;

use App\Domains\Post\Common\Post;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slides extends Post
{
    protected $table = 'slides_post';

    public function postMeta(): HasMany
    {
        return $this->hasMany(SlidesMeta::class, 'post_id', 'id');
    }
}
