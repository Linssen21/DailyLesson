<?php

declare(strict_types=1);

namespace App\Feature\Upload;

class Dimension
{
    public function __construct(private int $width, private int $height)
    {
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function toArray(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['width'],
            $data['height'],
        );
    }
}
