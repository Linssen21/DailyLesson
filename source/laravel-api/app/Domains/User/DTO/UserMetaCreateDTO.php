<?php

declare(strict_types=1);

namespace App\Domains\User\DTO;

class UserMetaCreateDTO
{
    public function __construct(
        private int $user_id,
        private string $meta_key,
        private string $meta_value
    ) {
    }

    // Getters
    public function getUserId(): int
    {
        return $this->user_id;
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
            'user_id' => $this->user_id,
            'meta_key' => $this->meta_key,
            'meta_value' => $this->meta_value
        ];
    }
}
