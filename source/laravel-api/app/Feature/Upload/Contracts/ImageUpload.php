<?php

declare(strict_types=1);

namespace App\Feature\Upload\Contracts;

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
    public function getScaler(): Scaler;
}
