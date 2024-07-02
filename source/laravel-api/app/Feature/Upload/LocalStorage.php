<?php

declare(strict_types=1);

namespace App\Feature\Upload;

use App\Feature\Upload\Contracts\FileStorage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * [Concrete File Storage] Local Storage
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class LocalStorage implements FileStorage
{
    private FilesystemAdapter $storage;

    public function __construct(string $driver = 'local')
    {
        $this->storage = Storage::disk($driver);
    }

    /**
     * Upload the file on local storage
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    public function upload(UploadedFile|string $file, string $path = '', string $name = ''): string
    {
        $fileName = $file instanceof UploadedFile ? $file->getClientOriginalName() : $name;

        if ($this->storage->exists("$path/$fileName")) {
            return '';
        }

        $fileUrl = $this->storage->putFileAs($path, $file, $fileName);
        if (!$fileUrl) {
            return '';
        }

        return $fileUrl;
    }

    /**
     * Create a download url for the file stored in local storage
     *
     * @param string $path
     * @return string
     */
    public function downloadUrl(string $path): string
    {
        // Check if file exist
        $isExist = $this->storage->exists($path);
        if (!$isExist) {
            return '';
        }

        return rtrim(config('app.url'), '/') . '/' . $path;
    }
}
