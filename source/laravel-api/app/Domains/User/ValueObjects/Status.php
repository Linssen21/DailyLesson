<?php

declare(strict_types=1);

namespace App\Domains\User\ValueObjects;

use InvalidArgumentException;

/**
 * Status Value Object
 *
 * @ticket Feature/DL-2
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class Status
{
    private int $intStatus;

    public const INACTIVE = 0;
    public const ACTIVE = 1;
    public const PENDING = 2;
    public const DELETED = 3;

    public function __construct(int $intStatus)
    {
        $blnIsStatusValid = in_array(
            $intStatus,
            [
                self::ACTIVE,
                self::INACTIVE,
                self::PENDING,
                self::DELETED
            ]
        );

        if (!$blnIsStatusValid) {
            throw new InvalidArgumentException('Invalid User Status');
        }

        $this->intStatus = $intStatus;
    }

    public function isInActive(): bool
    {
        return $this->intStatus === self::INACTIVE;
    }

    public function isActive(): bool
    {
        return $this->intStatus === self::ACTIVE;
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
