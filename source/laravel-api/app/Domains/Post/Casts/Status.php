<?php

declare(strict_types=1);

namespace App\Domains\Post\Casts;

use App\Domains\Post\ValueObjects\PostStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Status implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): PostStatus
    {
        return new PostStatus(
            $attributes['status']
        );
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof PostStatus) {
            throw new InvalidArgumentException('The given value is not an PostStatus instance.');
        }

        return $value->getStatus();
    }
}
