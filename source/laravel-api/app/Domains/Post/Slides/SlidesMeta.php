<?php

declare(strict_types=1);

namespace App\Domains\Common\Post\Slides;

use App\Domains\Post\Common\PostMeta;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlidesMeta extends PostMeta
{
    protected $table = 'slides_post_meta';

    public function post(): BelongsTo
    {
        return $this->belongsTo(Slides::class, 'post_id', 'id');
    }
}
