<?php

declare(strict_types=1);

namespace App\Domains\Post\Common;

class MediaDetail
{
    public function __construct(
        private string $name,
        private string $caption = "",
        private string $description = "",
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCaption(): string
    {
        return $this->caption;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'caption' => $this->caption,
            'description' => $this->description,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'],
            $data['caption'] ?? '',
            $data['description'] ?? ''
        );
    }

    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true));
    }
}
