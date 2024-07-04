<?php

declare(strict_types=1);

namespace App\Domains\Post\ValueObjects;

use InvalidArgumentException;

class PostStatus
{
    private int $intStatus;

    public const DRAFT = 0;
    public const PUBLISH = 1;
    public const PENDING = 2;
    public const DELETED = 3;

    public function __construct(int $intStatus)
    {
        $blnIsStatusValid = in_array(
            $intStatus,
            [
                self::DRAFT,
                self::PUBLISH,
                self::PENDING,
                self::DELETED
            ]
        );

        if (!$blnIsStatusValid) {
            throw new InvalidArgumentException('Invalid Post Status');
        }

        $this->intStatus = $intStatus;
    }

    public function isDraft(): bool
    {
        return $this->intStatus === self::DRAFT;
    }

    public function isPublish(): bool
    {
        return $this->intStatus === self::PUBLISH;
    }

    public function isPending(): bool
    {
        return $this->intStatus === self::PENDING;
    }

    public function isDeleted(): bool
    {
        return $this->intStatus === self::DELETED;
    }

    public function getStatus(): int
    {
        return $this->intStatus;
    }

}
