<?php

declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Permission Entity / Model
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class Permission extends Model
{
    use HasFactory;

    protected $table = 'permission';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'key',
        'description',
    ];

    /**
     * Name attribute setter and getter.
     *
     * @ticket Feature/DL-2
     *
     * @return Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $strName) => $strName,
            set: fn (string $strName) => $strName
        );
    }

    /**
    * Key attribute setter and getter.
    *
    * @ticket Feature/DL-2
    *
    * @return Attribute
    */
    protected function key(): Attribute
    {
        return Attribute::make(
            get: fn (string $strKey) => $strKey,
            set: fn (string $strKey) => $strKey
        );
    }

    /**
     * Description attribute setter and getter.
     *
     * @ticket Feature/DL-2
     *
     * @return Attribute
     */
    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn (string $strDescription) => $strDescription,
            set: fn (string $strDescription) => $strDescription
        );
    }

}
