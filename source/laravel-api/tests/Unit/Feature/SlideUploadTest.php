<?php

namespace Tests\Unit\Feature;

use App\Feature\Upload\Contracts\FileUpload;
use App\Feature\Upload\SlideUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SlideUploadTest extends TestCase
{
    private FileUpload $slideUpload;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->slideUpload = new SlideUpload();
    }


    public function test_slide_upload(): void
    {
        // Arrange
        $file = UploadedFile::fake()->create('slides.ppt', 1000);

        // Act
        $result = $this->slideUpload->upload($file, 'uploads');

        // Assert
        $this->assertNotEmpty($result);
        Storage::disk('local')->assertExists($result);
        $this->assertEquals('uploads/slides.ppt', $result);
    }
}
