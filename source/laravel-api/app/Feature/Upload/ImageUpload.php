<?php

declare(strict_types=1);

namespace App\Feature\Upload;

use App\Feature\Upload\Contracts\FileStorage;
use App\Feature\Upload\Contracts\FileUpload;
use App\Feature\Upload\Contracts\ImageUpload as IImageUpload;
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
class ImageUpload extends FileUpload implements IImageUpload
{
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
     * Return the Concrete Implementation of Scaler (ImageScale)
     *
     * @return Scaler
     */
    public function getScaler(int $maxWidth = 500): Scaler
    {
        return new ImageScale($maxWidth);
    }

    /**
     * File upload to storage and file scan
     *
     * @param UploadedFile $file
     * @return string
     * @throws ScannerException
     */
    public function upload(UploadedFile $file, string $path): string
    {
        $scanImage = $this->getScanner()->scan($file->getPathname());
        $name = $file->getClientOriginalName();
        if (!$scanImage) {
            throw new ScannerException("An issue occur scanning this file: " . $name);
        }

        $scaledImage = $this->getScaler()->scale($file->getPathname());
        $fileUrl = $this->getFileStorage()->upload($scaledImage, 'uploads', $name);

        if(empty($fileUrl)) {
            return '';
        }

        return $fileUrl;
    }

    public function getImageDimension(string $path): Dimension
    {
        $image = $this->getFileStorage()->get($path);
        $imageSize = getimagesizefromstring($image);
        return new Dimension($imageSize[0], $imageSize[1]);
    }

}
