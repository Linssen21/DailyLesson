<?php

declare(strict_types=1);

namespace App\Domains\Post\Common;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * PostMeta Entity / Model
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class PostMeta extends Model
{
    protected $fillable = [
        'meta_key',
        'meta_value',
        'post_id'
    ];

    /**
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function meta_key(): Attribute
    {
        return Attribute::make(
            get: fn (string $strMetaKey) => $strMetaKey,
            set: fn (string $strMetaKey) => $strMetaKey
        );
    }

    /**
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function meta_value(): Attribute
    {
        return Attribute::make(
            get: fn (string $strMetaValue) => $strMetaValue,
            set: fn (string $strMetaValue) => $strMetaValue
        );
    }

    /**
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function post_id(): Attribute
    {
        return Attribute::make(
            get: fn (int $intPostId) => $intPostId,
            set: fn (int $intPostId) => $intPostId
        );
    }

    /**
     *
     * @ticket Feature/DL-4
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'post_id' => $this->post_id,
            'meta_key' => $this->meta_key,
            'meta_value' => $this->meta_value
        ];
    }


}
