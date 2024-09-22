<?php

declare(strict_types=1);

namespace App\Domains\Post\Common;

use App\Domains\Post\Casts\Status;
use App\Domains\Post\ValueObjects\PostStatus;
use App\Domains\User\User;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Post Entity / Model
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected int $excerpt_length = 100;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'author_id', // user_id
        'content',
        'title',
        'excerpt',
        'status', // 'publish', 'draft', 'pending'
        'type',
        'slug',
        'like_count',
        'published_at'
    ];

    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }

    /**
     * Setup relationship with PostMeta entity
     *
     * @ticket Feature/DL-4
     *
     * @return HasMany
     */
    public function post_meta(): HasMany
    {
        return $this->hasMany(PostMeta::class, 'post_id', 'id');
    }

    /**
     * Setup relationship with User entity
     *
     * @ticket Feature/DL-4
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Converts published_at to a DateTime when fetch.
     * Get the attributes that should be cast.
     *
     * @ticket Feature/DL-4
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'status' => Status::class
        ];
    }

    /**
     * Content attribute setter and getter.
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn (string $strContent) => $strContent,
            set: fn (string $strContent) => $strContent
        );
    }

    /**
     * Title attribute setter and getter.
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $strTitle) => $strTitle,
            set: fn (string $strTitle) => $strTitle
        );
    }


    /**
     * Excerpt attribute setter and getter.
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn (string $strExcerpt) => $strExcerpt,
            set: function (string $strExcerpt): string {

                $contentToUse = empty($strExcerpt) ? $this->content : $strExcerpt;

                if (empty($contentToUse)) {
                    return '';
                }

                $excerpt = substr($contentToUse, 0, $this->getExcerptLength());

                if (strlen($contentToUse) > $this->getExcerptLength()) {
                    $excerpt .= '...';
                }

                return $excerpt;
            }
        );
    }

    /**
     * Type attribute setter and getter.
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn (string $strType) => $strType,
            set: fn (string $strType) => $strType
        );
    }

    /**
     * Slug attribute setter and getter.
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn (string $strSlug) => $strSlug,
            set: function (string $strSlug): string {

                if (empty($strSlug)) {
                    $slugText = $this->title;
                } else {
                    $slugText = $strSlug;
                }

                return self::generateSlug($slugText);
            }
        );
    }

    /**
     * Generate a slug from the given text.
     *
     * @param string $text
     * @return string
     */
    public static function generateSlug(string $text): string
    {
        $text = strtolower($text);
        // Replace spaces and non-alphanumeric characters with hyphens
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        // Trim hyphens from the beginning and end
        $text = trim($text, '-');

        return $text;
    }

    /**
     * Like Count attribute setter and getter.
     *
     * @ticket Feature/DL-4
     *
     * @return Attribute
     */
    protected function likeCount(): Attribute
    {
        return Attribute::make(
            get: fn (int $intCount) => $intCount,
            set: fn (int $intCount) => $intCount
        );
    }

    /**
     * Increase the number of like
     *
     * @ticket Feature/DL-4
     *
     * @param integer $numOfIncrement
     * @return void
     */
    public function incrementLikeCount(int $numOfIncrement = 1): void
    {
        $this->like_count += $numOfIncrement;
        $this->save();
    }

    /**
     * Decrease the number of like
     *
     * @ticket Feature/DL-4
     *
     * @param integer $numOfDecrement
     * @return void
     */
    public function decrementLikeCount(int $numOfDecrement = 1): void
    {
        $this->like_count -= $numOfDecrement;
        $this->save();
    }

    /**
     * Soft delete the post by setting the status as delete
     *
     * @ticket Feature/DL-4
     *
     * @return void
     */
    public function softDelete(): void
    {
        $this->status = new PostStatus(3);
        $this->save();
    }

    /**
     * Check if the post is deleted
     *
     * @ticket Feature/DL-4
     *
     * @return boolean
     */
    public function isDeleted(): bool
    {
        return $this->status->isDeleted();
    }

    /**
     * Get the length of the excerpt
     *
     * @ticket Feature/DL-4
     *
     * @return integer
     */
    public function getExcerptLength(): int
    {
        return $this->excerpt_length;
    }

    /**
     * Set the length of the excerpt
     *
     * @ticket Feature/DL-4
     *
     * @return integer
     */
    public function setExcerptLength(int $excerpt_length): int
    {
        return $this->excerpt_length = $excerpt_length;
    }

    /**
     * Scope a query to only include published posts.
     * usage: Post::publish()->get()
     *
     * @param Builder $query
     * @return void
     */
    public function scopePublish(Builder $query): void
    {
        $query->where('status', PostStatus::PUBLISH);
    }

    /**
     * Scope a query to only include deleted posts.
     * usage: Post::deletedPost()->get()
     *
     * @param Builder $query
     * @return void
     */
    public function scopeDeletedPost(Builder $query): void
    {
        $query->where('status', PostStatus::DELETED);
    }

    /**
     * Scope a query to only include active posts.
     *
     * @param Builder $query
     * @return void
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', '!=', PostStatus::DELETED);
    }

}
