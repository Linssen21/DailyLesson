<?php

declare(strict_types=1);

namespace App\Feature\Upload;

use App\Feature\Upload\Contracts\FileStorage;
use App\Feature\Upload\Contracts\FileUpload;
use App\Feature\Upload\Contracts\Scaler;
use App\Feature\Upload\Contracts\Scanner;
use App\Feature\Upload\Exceptions\ScannerException;
use Illuminate\Http\UploadedFile;

/**
 * [Concrete Implementation of FileUpload] Image Upload
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class ImageUpload extends FileUpload
{
    public function __construct(private Scaler $imageScale)
    {
    }

    /**
     * Return the concrete implementation of Scanner (ClamAvScanner)
     *
     * @return Scanner
     */
    public function getScanner(): Scanner
    {
        return new ClamAvScanner();
    }

    /**
     * Return the Concrete Implementation of FileStorage (Local Storage)
     *
     * @return FileStorage
     */
    public function getFileStorage(): FileStorage
    {
        return new LocalStorage();
    }

    /**
     * File upload to storage and file scan
     *
     * @param UploadedFile $file
     * @return boolean
     * @throws ScannerException
     */
    public function upload(UploadedFile $file, string $path): bool
    {
        $scanImage = $this->getScanner()->scan($file->getPathname());
        $name = $file->getClientOriginalName();
        if (!$scanImage) {
            throw new ScannerException("A virus is detectected to this file: " . $name);
        }

        $scaledImage = $this->imageScale->scale($file->getPathname(), 500);
        $fileUrl = $this->getFileStorage()->upload($scaledImage, 'uploads', $name);

        if(empty($fileUrl)) {
            return false;
        }

        return true;
    }

}
