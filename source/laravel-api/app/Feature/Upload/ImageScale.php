<?php

namespace App\Feature\Upload;

use App\Feature\Upload\Contracts\Scaler;
use GdImage;

class ImageScale implements Scaler
{
    public function __construct(private int $maxWidth)
    {
    }

    /**
     * Scale the image and store in the temporary file path (/tmp)
     *
     * @param string $fileName
     * @param integer $maxWidth
     * @return string
     */
    public function scale(string $fileName): string
    {
        $image = $this->createImage($fileName);

        // Create Scaled Image
        $width = imagesx($image);
        $height = imagesy($image);
        $newWidth = min($width, $this->maxWidth);
        $newHeight = $height * ($newWidth / $width);
        $scaledImage = imagescale($image, $newWidth, $newHeight);

        // Get Mimetype and Extension
        $mimeType = mime_content_type($fileName);
        $extension = $this->getExtensionFromMimeType($mimeType);

        // Create a temporary file to save the scaled image
        $tmpFile = tempnam(sys_get_temp_dir(), 'scaled_image');
        $tmpFilePath = "$tmpFile.$extension";

        // Save the file
        $this->saveFile($scaledImage, $tmpFilePath, $mimeType);

        // Clean up resources
        imagedestroy($image);
        imagedestroy($scaledImage);

        return $tmpFilePath;
    }


    public function supported(string $mimeType): bool
    {
        return $mimeType == 'image/jpeg' ||
            $mimeType == 'image/png' ||
            $mimeType == 'image/gif';
    }

    private function createImage(string $fileName): GdImage
    {
        list($width, $height, $type) = getimagesize($fileName);
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($fileName);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($fileName);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($fileName);
                break;
                // Add more cases as needed for other image types
            default:
                // Handle unsupported image types or errors
                die('Unsupported image type.');
        }

        return $source;
    }


    private function getExtensionFromMimeType(string $mimeType): string
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return 'jpg';
            case 'image/png':
                return 'png';
            case 'image/gif':
                return 'gif';
                // Add more cases as needed for other image types
            default:
                // Handle unsupported image types or errors
                die('Unsupported image type.');
        }
    }


    private function saveFile(GdImage $scaledImage, string $tmpFilePath, string $mimeType): void
    {
        // Save the scaled image to the temporary file
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($scaledImage, $tmpFilePath);
                break;
            case 'image/png':
                imagepng($scaledImage, $tmpFilePath);
                break;
            case 'image/gif':
                imagegif($scaledImage, $tmpFilePath);
                break;
                // Add more cases as needed for other image types
            default:
                // Handle unsupported image types or errors
                die('Unsupported image type.');
        }
    }
}
