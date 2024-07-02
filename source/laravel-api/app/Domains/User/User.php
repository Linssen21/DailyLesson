<?php

declare(strict_types=1);

namespace App\Domains\User;

use App\Domains\User\Casts\Status as StatusCast;
use App\Domains\User\DTO\UserCreateDTO;
use App\Domains\User\ValueObjects\Status as StatusValueObject;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use RuntimeException;

/**
 * User Entity / Model
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'email',
        'display_name',
        'role_id',
        'email_verified_at',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    /**
     * Converts emailed_verified_at to a DateTime when fetch.
     * Get the attributes that should be cast.
     *
     * @ticket Feature/DL-2
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'registered_date' => 'datetime',
            'status' => StatusCast::class
        ];
    }

    /**
     * Setup relationship with Role entity
     *
     * @ticket Feature/DL-2
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * Setup relationship with UserMeta entity
     *
     * @ticket Feature/DL-2
     *
     * @return HasMany
     */
    public function user_meta(): HasMany
    {
        return $this->hasMany(UserMeta::class, 'user_id', 'id');
    }

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
     * Password attribute setter and getter.
     * Hash the password before saving in database
     *
     * @ticket Feature/DL-2
     *
     * @return Attribute
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn (string $strPassword) => $strPassword,
            set: fn (string $strPassword) => bcrypt($strPassword)
        );
    }

    /**
     * Email attribute setter and getter.
     *
     * @ticket Feature/DL-2
     *
     * @return Attribute
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn (string $strEmail) => $strEmail,
            set: fn (string $strEmail) => $strEmail
        );
    }

    /**
     * Display Name attribute setter and getter.
     *
     * @ticket Feature/DL-2
     *
     * @return Attribute
     */
    protected function display_name(): Attribute
    {
        return Attribute::make(
            get: fn (string $strDisplayName) => $strDisplayName,
            set: fn (string $strDisplayName) => $strDisplayName
        );
    }

    /**
     * Change password
     *  - Accept a plain text password
     *  - Check if the user exist
     *  - Hash and the plain text password and save
     *
     * @ticket Feature/DL-2
     *
     * @return void
     * @throws RuntimeException
     */
    public function changePassword(string $strPlainTextPass)
    {
        // Since we are saving using this an existed user need's to be selected first before changing password
        if (!$this->exists) {
            throw new \RuntimeException('User does not exist.');
        }

        $this->password = $strPlainTextPass;
        $this->save();
    }

    /**
     * Check if the the current User is active
     *
     * @ticket Feature/DL-2
     *
     * @return boolean
     */
    public function isActive(): bool
    {
        // Get the status value object
        $status = $this->status;

        // Check if the status is active
        return $status->isActive();

    }

    /**
     * Check if the user's has a current role
     *
     * @ticket Feature/DL-2
     *
     * @return boolean
     */
    public function hasRole(): bool
    {
        $isRoleNotEmpty = !empty($this->role->name);
        return $isRoleNotEmpty;
    }

    /**
     * Set the current user as verified
     *
     * @ticket Feature/DL-2
     *
     * @return void
     */
    public function markAsVerified(): void
    {
        $this->email_verified_at = Carbon::now();
        $this->status = new StatusValueObject(StatusValueObject::ACTIVE);
        $this->save();
    }

    /**
     * Check if the user was verified
     *
     * @ticket Feature/DL-2
     *
     * @return boolean
     */
    public function isVerified(): bool
    {
        return isset($this->email_verified_at) && $this->email_verified_at->isPast();
    }


    /**
     * Soft delete the user by setting the status as delete
     *
     * @ticket Feature/DL-2
     *
     * @return void
     */
    public function softDelete(): void
    {
        $this->status = new StatusValueObject(3);
        $this->save();
    }

    /**
     * Check if the user is deleted
     *
     * @ticket Feature/DL-2
     *
     * @return boolean
     */
    public function isDeleted(): bool
    {
        return $this->status->isDeleted();
    }

    /**
     * Create a user and call Registered event to send email verification
     *
     * @ticket Feature/DL-2
     *
     * @param UserCreateDTO $userCreateDTO
     * @return self
     */
    public function createUser(UserCreateDTO $userCreateDTO): self
    {
        $user = $this->query()->create($userCreateDTO->toArray());
        event(new Registered($user));
        return $user;
    }

}
