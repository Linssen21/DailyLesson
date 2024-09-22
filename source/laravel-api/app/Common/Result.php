<?php

declare(strict_types=1);

namespace App\Common;

use InvalidArgumentException;

/**
 * Result pattern
 */
class Result
{
    private bool $isSuccess;
    private string $error;

    private function __construct(
        bool $isSuccess,
        string $error = ""
    ) {
        if ($isSuccess && !empty($error)) {
            throw new InvalidArgumentException("Success result cannot have an error.");
        }

        if (!$isSuccess && empty($error)) {
            throw new InvalidArgumentException("Failure result must have an error.");
        }

        $this->isSuccess = $isSuccess;
        $this->error = $error;
    }


    public function getIsSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public static function success(): self
    {
        return new self(true, "");
    }

    public static function failure(string $error): self
    {
        return new self(false, $error);
    }
}
