<?php

declare(strict_types=1);

namespace App\Domains\Post\DTO;

use App\Domains\Post\Common\ImageDetail;
use App\Domains\Post\Common\MediaDetail;

class AttachmentUpdateDto
{
    public function __construct(
        private int $id,
        private MediaDetail $mediaDetail,
        private ?ImageDetail $imageDetail = null
    ) {
    }

    // Getter for id
    public function getId(): int
    {
        return $this->id;
    }

    // Getter for mediaDetail
    public function getMediaDetail(): MediaDetail
    {
        return $this->mediaDetail;
    }

    // Getter for imageDetail
    public function getImageDetail(): ?ImageDetail
    {
        return $this->imageDetail;
    }

    public function isImageDetail(): bool
    {
        return $this->imageDetail !== null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'mediaDetail' => $this->mediaDetail,
            'imageDetail' => $this->imageDetail
        ];
    }
}
