<?php

declare(strict_types=1);

namespace App\Domains\Post\Common;

class ImageDetail
{
    public function __construct(
        private string $title,
        private string $altText = ""
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'altText' => $this->altText,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['altText'] ?? '',
        );
    }

    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true));
    }
}
