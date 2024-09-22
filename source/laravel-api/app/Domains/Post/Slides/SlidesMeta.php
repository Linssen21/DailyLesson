<?php

declare(strict_types=1);

namespace App\Domains\Post\Slides;

use App\Domains\Post\Common\PostMeta;
use App\Domains\Post\Slides\Slides;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlidesMeta extends PostMeta
{
    public function post(): BelongsTo
    {
        return $this->belongsTo(Slides::class, 'post_id', 'id');
    }
}
