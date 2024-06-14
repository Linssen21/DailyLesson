<?php

declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Role Entity / Model
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    private const ADMIN = 'administrator';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'permissions',
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

    /**
     * Permissions attribute setter and getter.
     *
     * @ticket Feature/DL-2
     *
     * @return Attribute
     */
    protected function permissions(): Attribute
    {
        return Attribute::make(
            get: fn (string $strPermissions) => explode(',', $strPermissions),
            set: fn (array $aryPermissions) => implode(',', $aryPermissions)
        );
    }


    protected function isDelete(): Attribute
    {
        return Attribute::make(
            get: fn (int $intDelete) => $intDelete,
            set: fn (int $intDelete) => $intDelete,
        );
    }


    /**
     * Setup relationship with Role entity
     *
     * @ticket Feature/DL-2
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function softDelete(): void
    {
        $this->is_delete = 1;
    }


    public function isAdmin(): bool
    {
        if ($this->name == self::ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * Transform property to array value
     *
     * @ticket Feature/DL-2
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'permissions' => $this->permissions
        ];
    }


}
