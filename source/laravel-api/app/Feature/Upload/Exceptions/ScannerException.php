<?php

namespace App\Feature\Upload\Exceptions;

use Exception;

class ScannerException extends Exception
{
    public function __construct(
        string $message = 'A Virus is Detected',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
