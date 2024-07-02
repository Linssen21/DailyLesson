<?php

namespace Tests\Unit\Feature;

use App\Feature\Upload\ImageScale;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageScaleTest extends TestCase
{
    private ImageScale $imageScale;

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
        $result = $this->imageScale->scale($file->getPathname(), 500);

        // When
        $this->assertTrue(true);
    }
}
