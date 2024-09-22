<?php

declare(strict_types=1);

namespace App\Feature\Upload\Contracts;

use App\Feature\Upload\Dimension;

/**
 * [ImageUpload] Factory for File Storage and Scanner
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */

interface ImageUpload
{
    public function getScaler(int $maxWidth): Scaler;
    public function getImageDimension(string $path): Dimension;
}
