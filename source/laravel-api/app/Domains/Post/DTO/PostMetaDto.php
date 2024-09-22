<?php

declare(strict_types=1);

namespace App\Domains\Post\DTO;

class PostMetaDto
{
    public function __construct(
        private int $post_id,
        private string $meta_key,
        private string $meta_value
    ) {
    }

    // Getters
    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function getMetaKey(): string
    {
        return $this->meta_key;
    }

    public function getMetaValue(): string
    {
        return $this->meta_value;
    }

    public function toArray(): array
    {
        return [
            'post_id' => $this->post_id,
            'meta_key' => $this->meta_key,
            'meta_value' => $this->meta_value
        ];
    }
}
