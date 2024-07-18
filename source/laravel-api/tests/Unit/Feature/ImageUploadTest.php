<?php

namespace Tests\Unit\Feature;

use App\Feature\Upload\Contracts\FileUpload;
use App\Feature\Upload\ImageUpload;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    private FileUpload $imageUpload;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->imageUpload = new ImageUpload();
    }


    public function test_image_upload(): void
    {
        // Arrange
        $file = UploadedFile::fake()->image('test_image_upload.jpg');
        chmod($file->getPathname(), 0644);

        // Act
        $result = $this->imageUpload->upload($file, 'uploads');

        // When
        $this->assertNotEmpty($result);
        Storage::disk('local')->assertExists($result);
        $this->assertEquals('uploads/test_image_upload.jpg', $result);
    }



}
