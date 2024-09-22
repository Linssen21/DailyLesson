<?php

declare(strict_types=1);

namespace App\Feature\Upload;

use App\Feature\Upload\Contracts\FileStorage;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        $originalFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = $file instanceof UploadedFile ? $file->getClientOriginalExtension() : pathinfo($fileName, PATHINFO_EXTENSION);

        // Set file name
        $counter = 1;
        $uniqueFileName = $fileName;
        while($this->storage->exists("$path/$uniqueFileName")) {
            $uniqueFileName = "{$originalFileName}_{$counter}.{$extension}";
            $counter++;
        }

        // Save file to storage
        $filePath = $this->storage->putFileAs($path, $file, $uniqueFileName);
        if (!$filePath) {
            Log::channel('applog')->info(
                '[Upload] Save File error',
                ['data' => "Filename: $path/$uniqueFileName"]
            );
            return '';
        }

        return $filePath;
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

    /**
     * Get the file content
     *
     * @param string $path
     * @return string
     */
    public function get(string $path): string
    {
        return $this->storage->get($path);
    }
}
