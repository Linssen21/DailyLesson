<?php

declare(strict_types=1);

namespace App\Feature\Upload\Contracts;

use Illuminate\Http\UploadedFile;

interface FileStorage
{
    /**
     * Upload file to file storage
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    public function upload(UploadedFile|string $file, string $path = '', string $name = ''): string;

    /**
     * Download file based on the path
     *
     * @param string $path
     * @return string
     */
    public function downloadUrl(string $path): string;
}
