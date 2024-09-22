<?php

declare(strict_types=1);

namespace App\Domains\Post\Common;

use App\Domains\Post\Contracts\PostMeta;

class Attachment implements PostMeta
{
    public const META_KEY = 'attachment';

    public function __construct(
        private int $author,
        private string $path,
        private string $url,
        private string $mimeType,
        private int $size,
        private MediaDetail $detail
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getAuthor(): int
    {
        return $this->author;
    }

    public function getMetaKey(): string
    {
        return self::META_KEY;
    }

    public function getMediaDetail(): MediaDetail
    {
        return $this->detail;
    }

    public function setMediaDetail(MediaDetail $detail): void
    {
        $this->detail = $detail;
    }

    public function getMetaValue(): string
    {
        return json_encode([
            'author' => $this->author,
            'path' => $this->path,
            'url' => $this->url,
            'mimeType' => $this->mimeType,
            'size' => $this->size,
            'detail' =>  $this->detail->toArray(),
        ]);
    }

    public function toArray(): array
    {
        return [
            'meta_key' => self::META_KEY,
            'meta_value' => [
                'author' => $this->author,
                'path' => $this->path,
                'url' => $this->url,
                'mimeType' => $this->mimeType,
                'size' => $this->size,
                'detail' =>  $this->detail->toArray(),
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
        return new self(
            $data['author'],
            $data['path'],
            $data['url'],
            $data['mimeType'],
            $data['size'],
            MediaDetail::fromArray($data['detail'])
        );
    }
}
