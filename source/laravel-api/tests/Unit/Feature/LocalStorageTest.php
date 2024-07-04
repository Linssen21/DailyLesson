<?php

namespace Tests\Unit\Feature;

use App\Feature\Upload\LocalStorage;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LocalStorageTest extends TestCase
{
    private LocalStorage $localStorage;

    protected function setup(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->localStorage = new LocalStorage();
    }

    public function test_upload(): void
    {
        // Arrange
        $file = UploadedFile::fake()->create('test.ppt', 1000);

        // Act
        $path = $this->localStorage->upload($file, 'uploads');

        // When
        Storage::disk('local')->assertExists($path);
        $this->assertNotEmpty($path);
        $this->assertEquals('uploads/test.ppt', $path);
    }


    public function test_upload_fail(): void
    {
        // Arrange
        $file = UploadedFile::fake()->create('test.ppt', 1000);
        $duplicateFile = UploadedFile::fake()->create('test.ppt', 1000);
        $this->localStorage->upload($file, 'uploads');

        // Act
        $path = $this->localStorage->upload($duplicateFile, 'uploads');

        // When
        $this->assertEmpty($path);
    }


    public function test_download_url(): void
    {
        // Arrange
        $file = UploadedFile::fake()->create('test.ppt', 1000);
        $path = $this->localStorage->upload($file, 'uploads');

        // Act
        $downloadUrl = $this->localStorage->downloadUrl($path);

        // When
        $this->assertNotEmpty($downloadUrl);

    }

    public function test_download_url_fail(): void
    {
        // Arrange
        $path = 'uploads/donotexist.ppt';

        // Act
        $downloadUrl = $this->localStorage->downloadUrl($path);

        // When
        $this->assertEmpty($downloadUrl);
    }
}
