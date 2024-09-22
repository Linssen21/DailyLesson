<?php

declare(strict_types=1);

namespace App\Domains\Post\DTO;

use App\Domains\Post\Slides\Slides;
use App\Domains\Post\ValueObjects\PostStatus;
use DateTime;

class SlideCreateDto
{
    public function __construct(
        private int $authorId,
        private string $content,
        private string $title,
        private string $excerpt,
        private PostStatus $status,
        private string $slug,
        private string $ppt = '',
        private string $google = '',
        private string $canva = '',
        private int $likeCount = 0,
        private string $type = Slides::TYPE,
        private DateTime $publishedAt = new DateTime(),
    ) {
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getAuthor(): int
    {
        return $this->authorId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    public function getStatus(): PostStatus
    {
        return $this->status;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getLike(): int
    {
        return $this->likeCount;
    }

    public function getPublishedAt(): DateTime
    {
        return $this->publishedAt;
    }

    public function getPpt(): string
    {
        return $this->ppt;
    }

    public function getGoogle(): string
    {
        return $this->google;
    }

    public function getCanva(): string
    {
        return $this->canva;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPostArray(): array
    {
        return [
            'author_id' => $this->authorId,
            'content' => $this->content,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'status' => $this->status,
            'type' => $this->type,
            'slug' => $this->slug,
            'like_count' => $this->likeCount,
            'published_at' => $this->publishedAt
        ];
    }

    public function getSlideMetaArray(): array
    {
        return [
            'ppt' => $this->ppt,
            'google' => $this->google,
            'canva' => $this->canva,
        ];
    }

}
