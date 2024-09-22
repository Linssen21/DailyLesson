<?php

declare(strict_types=1);

namespace App\Domains\Post\Service;

use App\Domains\Post\Contracts\PostMetaRepository;
use App\Domains\Post\Contracts\PostRepository;
use App\Domains\Post\Contracts\SlideRepository;
use App\Domains\Post\DTO\PostDto;
use App\Domains\Post\DTO\PostMetaDto;
use App\Domains\Post\DTO\SlideCreateDto;
use App\Domains\Post\Slides\Template;

/**
 * Slide Domain Service
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class SlideService extends PostService
{
    public function __construct(
        private PostMetaRepository $postMetaRepository,
        private SlideRepository $slideRepository,
        private PostRepository $postRepository
    ) {
        parent::__construct($postRepository, $postMetaRepository);
    }

    /**
     * Create slide and increment slug and title if already existed
     *
     * @param SlideCreateDto $slideCreateDto
     * @return void
     */
    public function create(SlideCreateDto $slideCreateDto): void
    {
        // Check if slide title already exist and add - (1) increment
        $title = $this->incrementTitle($slideCreateDto->getTitle());
        $slideCreateDto->setTitle($title);

        // Check if slide slug already exist and add - (1) increment
        $slug = $this->incrementSlug($slideCreateDto->getSlug());
        $slideCreateDto->setSlug($slug);

        // Save to Slide Post
        $slide = $this->slideRepository->create($slideCreateDto);

        // Save Template meta
        $template = new Template($slideCreateDto->getPpt(), $slideCreateDto->getGoogle(), $slideCreateDto->getCanva());
        $slideMeta = new PostMetaDto($slide->getKey(), Template::META_KEY, $template->toJson());
        $this->postMetaRepository->create($slideMeta);
    }

    /**
     * Update by Id
     *
     * @param integer $id
     * @param PostDto $postDto
     * @param Template $template
     * @return bool
     */
    public function update(int $id, PostDto $postDto, Template $template): bool
    {
        // Update Post
        $isUpdated = $this->updatePost($id, $postDto);
        if (!$isUpdated) {
            return false;
        }

        // Update Meta
        if (!$template->isEmpty()) {
            $this->postMetaRepository->updateWithMeta($id, $template->getMetaKey(), ['meta_value' => $template->getMetaValue()]);
        }
        return true;
    }

    /**
     * Soft delete post and meta data
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id): bool
    {
        return $this->softDelete($id, Template::META_KEY);
    }

}
