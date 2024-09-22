<?php

namespace App\Feature\Upload\Exceptions;

use Exception;

class ScannerException extends Exception
{
    public function __construct(
        string $message = 'An unexpected error occurred while scanning your file',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
