<?php

namespace Tests\Unit\Feature;

use App\Feature\Upload\ImageScale;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageScaleTest extends TestCase
{
    private ImageScale $imageScale;
    private string $scaledImageFilePath;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->imageScale = new ImageScale();
    }

    public function test_scale(): void
    {
        // Arrange
        $file = UploadedFile::fake()->image('test.jpg');

        // Act
        $this->scaledImageFilePath = $this->imageScale->scale($file->getPathname(), 500);

        // When
        $this->assertNotEmpty($this->scaledImageFilePath);
        $this->assertFileExists($this->scaledImageFilePath);
    }

    protected function tearDown(): void
    {
        // Clean up: delete the scaled image file
        if (isset($this->scaledImageFilePath) && file_exists($this->scaledImageFilePath)) {
            unlink($this->scaledImageFilePath);
        }

        parent::tearDown();
    }
}
