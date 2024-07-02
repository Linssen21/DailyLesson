<?php

declare(strict_types=1);

namespace App\Feature\Upload\Contracts;

use App\Feature\Upload\Contracts\FileStorage;
use App\Feature\Upload\Contracts\Scanner;
use Illuminate\Http\UploadedFile;

/**
 * [FileUpload] Factory for File Storage and Scanner
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */

abstract class FileUpload
{
    abstract public function getScanner(): Scanner;
    abstract public function getFileStorage(): FileStorage;
    abstract public function upload(UploadedFile $file, string $path): bool;

    /**
     * Fetching the download url
     *
     * @param string $path
     * @return string
     */
    public function downloadUrl(string $path): string
    {
        $storage = $this->getFileStorage();
        return $storage->downloadUrl($path);
    }
}
