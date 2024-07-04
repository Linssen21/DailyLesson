<?php

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
