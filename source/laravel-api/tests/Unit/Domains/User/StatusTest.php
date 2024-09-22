<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\User;

use App\Domains\User\ValueObjects\Status;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_creation_with_valid_status(): void
    {
        // Arrange
        $intCurrentStatus = Status::ACTIVE;
        $status = new Status($intCurrentStatus);

        // Act
        $intFetchedStatus = $status->getStatus();

        // Assert
        $this->assertEquals($intCurrentStatus, $intFetchedStatus);
    }

    public function test_creation_with_invalid_status(): void
    {
        // Arrange
        $intInvalidStatus = 999;

        // Assert
        $this->expectException(InvalidArgumentException::class);

        // Act
        new Status($intInvalidStatus);
    }

    public function test_is_active(): void
    {
        // Arrange
        $intStatusActive = Status::ACTIVE;
        $status = new Status($intStatusActive);

        // Act
        $blnStatusActive = $status->isActive();

        $this->assertTrue($blnStatusActive);
    }

    public function test_is_inactive(): void
    {
        // Arrange
        $intStatusInActive = Status::INACTIVE;
        $status = new Status($intStatusInActive);

        // Act
        $blnStatusInActive = $status->isInActive();

        $this->assertTrue($blnStatusInActive);
    }

    public function test_is_pending(): void
    {
        // Arrange
        $intStatusPending = Status::PENDING;
        $status = new Status($intStatusPending);

        // Act
        $blnStatusPending = $status->isPending();

        $this->assertTrue($blnStatusPending);
    }

    public function test_is_deleted(): void
    {
        // Arrange
        $intStatusDeleted = Status::DELETED;
        $status = new Status($intStatusDeleted);

        // Act
        $blnStatusDeleted = $status->isDeleted();

        // Assert
        $this->assertTrue($blnStatusDeleted);
    }
}
