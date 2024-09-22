<?php

declare(strict_types=1);

namespace App\Domains\Post\Contracts;

use App\Domains\Post\DTO\SlideCreateDto;
use App\Domains\Post\Slides\Slides;
use Illuminate\Support\Collection;

interface SlideRepository
{
    public function create(SlideCreateDto $slideCreateDto): Slides;
    public function get(int $id): Slides;
    public function getByColumn(array $column, mixed $operator = "="): Slides;
}
