<?php

namespace App\Feature\Upload\Contracts;

interface Scaler
{
    /**
     * Scale the image and return the output file
     *
     * @param string $fileName
     * @param integer $maxWidth
     * @return string
     */
    public function scale(string $fileName, int $maxWidth): string;


    /**
     * Returns wheter the image scaler supports scaling the given mime type
     *
     * @param string $mimeType
     * @return boolean
     */
    public function supported(string $mimeType): bool;
}
