<?php

declare(strict_types=1);

namespace App\Domains\Post\Common;

use App\Domains\Post\Contracts\PostMeta;
use App\Feature\Upload\Dimension;

class Image implements PostMeta
{
    public const META_KEY = 'image';

    public function __construct(
        private int $author,
        private Dimension $dimension,
        private ImageDetail $detail
    ) {
    }

    public function getMetaKey(): string
    {
        return self::META_KEY;
    }

    public function getMetaValue(): string
    {
        return json_encode([
            'author' => $this->author,
            'dimension' => $this->dimension->toArray(),
            'detail' => $this->detail->toArray(),
        ]);
    }

    public function setImageDetail(ImageDetail $detail): void
    {
        $this->detail = $detail;
    }

    public function toArray(): array
    {
        return [
            'meta_key' => self::META_KEY,
            'meta_value' => [
                'author' => $this->author,
                'dimension' => $this->dimension->toArray(),
                'detail' => $this->detail->toArray(),
            ]
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }


    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);

        $dimension = new Dimension(
            $data['dimension']['width'],
            $data['dimension']['height']
        );
        $image = new ImageDetail($data['detail']['title'], $data['detail']['altText']);

        return new self(
            $data['author'],
            $dimension,
            $image
        );
    }
}
