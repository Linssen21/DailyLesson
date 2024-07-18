<?php

declare(strict_types=1);

namespace App\Feature\Upload\Contracts;

interface Scanner
{
    /**
     * Scan if an issue is detected
     *
     * @param string $fileName
     * @return boolean
     */
    public function scan(string $fileName): bool;
}
