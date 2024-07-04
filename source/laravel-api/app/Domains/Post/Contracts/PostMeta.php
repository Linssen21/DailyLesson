<?php

namespace App\Domains\Post\Contracts;

interface PostMeta
{
    public function getMetaKey(): string;
    public function getMetaValue(): string;
    public function toArray(): array;
    public function toJson(): string;
    public static function fromJson(string $json): self;
}
