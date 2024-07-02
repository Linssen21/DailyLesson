<?php

declare(strict_types=1);

namespace Tests\Unit\Feature;

use App\Feature\Upload\ClamAvScanner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClamAvScannerTest extends TestCase
{
    private ClamAvScanner $clamAvScanner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clamAvScanner = new ClamAvScanner();
        Storage::fake('local');
    }

    public function test_scanner(): void
    {
        // Arrange
        $file = UploadedFile::fake()->create('test.ppt', 1000);

        // Act
        $result = $this->clamAvScanner->scan($file->getPathname());

        //
        $this->assertTrue($result);
    }

    public function test_scanner_with_fake_virus_file(): void
    {
        // Arrange: Create a fake virus file with EICAR test string
        $eicarTestString = "X5O!P%@AP[4\\PZX54(P^)7CC)7}\$EICAR-STANDARD-ANTIVIRUS-TEST-FILE!\$H+H*";
        // Create a temporary file with the EICAR test string
        $filePath = tempnam(sys_get_temp_dir(), 'test.pptx');
        file_put_contents($filePath, $eicarTestString);

        // Create an UploadedFile instance
        $file = new UploadedFile(
            $filePath,
            'test.pptx',
            'application/text',
            null,
            true // Mark it as test mode
        );

        // Act
        $result = $this->clamAvScanner->scan($file->getPathname());

        //
        $this->assertFalse($result);
    }
}
