<?php

declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * UserMeta Entity / Model
 *
 * This model represents the metadata associated with a user in the application.
 * It includes profile information, preferences, activity history, custom fields,
 * and authentication tokens.
 *
 * - Profile Information
 * - Preferences
 * - Activity History
 * - Custom Fields
 * - Authentication Tokens
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class UserMeta extends Model
{
    use HasFactory;

    protected $table = 'user_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meta_key',
        'meta_value',
        'user_id'
    ];

    protected static function newFactory()
    {
        return \Database\Factories\UserMetaFactory::new();
    }

    /**
     * Setup relationship with User entity
     *
     * @ticket Feature/DL-2
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * MetaKey attribute setter and getter.
     *
     * @ticket Feature/DL-2
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
    * MetaValue attribute setter and getter.
    *
    * @ticket Feature/DL-2
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
    * UserId attribute setter and getter.
    *
    * @ticket Feature/DL-2
    *
    * @return Attribute
    */
    protected function user_id(): Attribute
    {
        return Attribute::make(
            get: fn (int $intUserId) => $intUserId,
            set: fn (int $intUserId) => $intUserId
        );
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
            'user_id' => $this->user_id,
            'meta_key' => $this->meta_key,
            'meta_value' => $this->meta_value
        ];
    }

}
