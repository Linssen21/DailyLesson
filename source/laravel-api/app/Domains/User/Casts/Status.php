<?php

declare(strict_types=1);

namespace App\Domains\User\Casts;

use App\Domains\User\ValueObjects\Status as StatusValueObject;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Status VO Casts
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class Status implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): StatusValueObject
    {
        return new StatusValueObject(
            $attributes['status']
        );
    }

    /**
     * When setting the value for the status field it check if the value is set as a Status Object
     *
     * @ticket Feature/DL-2
     *
     * @param Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return integer|null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof StatusValueObject) {
            throw new InvalidArgumentException('The given value is not an StatusValueObject instance.');
        }

        return $value->getStatus();
    }
}
