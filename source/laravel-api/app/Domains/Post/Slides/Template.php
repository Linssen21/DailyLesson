<?php

declare(strict_types=1);

namespace App\Domains\Post\Slides;

use App\Domains\Post\Contracts\PostMeta;

/**
 * Template Value Object(VO)
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class Template implements PostMeta
{
    public const META_KEY = 'slide_template';
    public const GOOGLE_SLIDE_URL_REGEX = '/^https?:\/\/(?:www\.)?docs\.google\.com\/presentation\/d\/[a-zA-Z0-9_-]+\/?.*$/';
    public const CANVA_URL_REGEX = '/^https?:\/\/(?:www\.)?canva\.com\/design\/[a-zA-Z0-9_-]+\/?.*$/';

    private string $pptPath;
    private string $googleSlideUrl;
    private string $canvaUrl;

    public function __construct(
        string $pptPath = '',
        string $googleSlideUrl = '',
        string $canvaUrl = ''
    ) {

        $this->pptPath = $pptPath;
        $this->googleSlideUrl = $googleSlideUrl;
        $this->canvaUrl = $canvaUrl;

        $this->validateInitialUrl();
    }

    private function validateInitialUrl(): void
    {
        $aryUrls = [
            'Google Slide' => $this->googleSlideUrl,
            'Canva' => $this->canvaUrl
        ];

        foreach ($aryUrls as $key => $url) {
            $this->validateUrl($url, $key);
        }

        if (!empty($this->googleSlideUrl)) {
            $this->isValidGoogleSlideUrl($this->googleSlideUrl);
        }

        if (!empty($this->canvaUrl)) {
            $this->isValidCanvaUrl($this->canvaUrl);
        }

    }

    public function validateUrl(string $url, string $property): void
    {
        if (!empty($url) && !filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException("Invalid {$property} URL");
        }
    }

    public function isValidGoogleSlideUrl(string $url): void
    {
        if (!preg_match(self::GOOGLE_SLIDE_URL_REGEX, $url)) {
            throw new \InvalidArgumentException("Invalid Google Slide URL");
        }
    }

    public function isValidCanvaUrl(string $url): void
    {
        if (!preg_match(self::CANVA_URL_REGEX, $url)) {
            throw new \InvalidArgumentException("Invalid Canva URL");
        }
    }

    public function getPpt(): string
    {
        return $this->pptPath;
    }

    public function getGoogleSlide(): string
    {
        return $this->googleSlideUrl;
    }

    public function getCanva(): string
    {
        return $this->canvaUrl;
    }

    public function getMetaKey(): string
    {
        return self::META_KEY;
    }

    public function getMetaValue(): string
    {
        return json_encode(
            [
                'pptPath' => $this->pptPath,
                'googleSlideUrl' => $this->googleSlideUrl,
                'canvaUrl' => $this->canvaUrl,
            ]
        );
    }

    public function toArray(): array
    {
        return [
            'meta_key' => self::META_KEY,
            'meta_value' => [
                'pptPath' => $this->pptPath,
                'googleSlideUrl' => $this->googleSlideUrl,
                'canvaUrl' => $this->canvaUrl,
            ]
        ];
    }

    public function equals(self $other): bool
    {
        return $this->pptPath === $other->pptPath
            && $this->googleSlideUrl === $other->googleSlideUrl
            && $this->canvaUrl === $other->canvaUrl;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        return new self(
            $data['meta_value']['pptPath'],
            $data['meta_value']['googleSlideUrl'],
            $data['meta_value']['canvaUrl']
        );
    }

    public function isEmpty(): bool
    {
        return empty($this->pptPath)
            && empty($this->canvaUrl)
            && empty($this->googleSlideUrl);
    }

}
