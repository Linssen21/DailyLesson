<?php

declare(strict_types=1);

namespace App\Domains\Post\Service;

use App\Common\QueryParams;
use App\Domains\Post\Common\Post;
use App\Domains\Post\Contracts\PostMetaRepository;
use App\Domains\Post\Contracts\PostRepository;
use App\Domains\Post\DTO\PostDto;
use App\Domains\Post\ValueObjects\PostStatus;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Post Domain Service
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class PostService
{
    public function __construct(
        private PostRepository $postRepository,
        private PostMetaRepository $postMetaRepository
    ) {
    }

    /**
     * Add increment to title if already exist
     *
     * @param string $title
     * @return string
     */
    protected function incrementTitle(string $title): string
    {
        $counter = $this->checkAndIncrement("title", $title);
        if (empty($counter)) {
            return $title;
        }

        return "{$title} ({$counter})";
    }

    /**
     * Add increment to slug if already exist
     *
     * @param string $slug
     * @return string
     */
    protected function incrementSlug(string $slug): string
    {
        if (empty($slug)) {
            return $slug;
        }

        $slug = Post::generateSlug($slug);
        $counter = $this->checkAndIncrement("slug", $slug);

        if (empty($counter)) {
            return $slug;
        }

        return "{$slug}-{$counter}";
    }

    /**
     * Check if the value exist and return an incremented count
     *
     * @param string $column
     * @param string $value
     * @return integer
     */
    private function checkAndIncrement(string $column, string $value): int
    {
        $columns = $this->postRepository->getByColumns([$column => "{$value}%"], 'like');

        if ($columns->isEmpty()) {
            return 0;
        }

        return $columns->count() + 1;
    }

    /**
     * Fetch data based on query parameters
     *
     * @param QueryParams $params
     * @return Collection
     */
    public function get(QueryParams $params): Collection
    {
        return $this->postRepository->getAllByColumn($params);
    }

    /**
     * Fetch Data with query parameters and return with pagination
     *
     * @param QueryParams $params
     * @param integer $page
     * @return LengthAwarePaginator
     */
    public function getWithPagination(QueryParams $params): LengthAwarePaginator
    {
        return $this->postRepository->getWithPagination($params);
    }

    /**
     * Soft delete post and meta data
     *
     * @param integer $id
     * @return boolean
     */
    public function softDelete(int $id, mixed $metaKey = null): bool
    {
        $post = $this->postRepository->find($id);
        if (empty($post) || $post->isDeleted()) {
            return false;
        }

        $post->softDelete();

        if (!empty($metaKey)) {
            $this->postMetaRepository->updateWithMeta($id, $metaKey, ['is_deleted' => 1]);
        } else {
            $this->postMetaRepository->update($id, ['is_deleted' => 1]);
        }

        return true;
    }

    /**
     * Update post and return true if exist and updated
     *
     * @param integer $id
     * @param PostDto $postDto
     * @return boolean
     */
    protected function updatePost(int $id, PostDto $postDto): bool
    {
        // Fetch if exist
        $post = $this->postRepository->find($id);
        if (empty($post->getKey())) {
            return false;
        }

        // Update Post
        $updateList = $postDto->toFilteredArray();

        $title = $postDto->getTitle();
        if (!empty($title)) {
            if ($post->title === $title) {
                $title = $this->incrementTitle($postDto->getTitle());
            }

            $updateList['title'] = $title;
        }

        $slug = $postDto->getSlug();
        if (!empty($slug)) {
            if ($post->slug === $slug) {
                $slug = $this->incrementSlug($postDto->getSlug());
            }

            $updateList['slug'] = $slug;
        }

        if ($postDto->getStatus() instanceof PostStatus) {
            $updateList['status'] = $postDto->getStatus()->getStatus();
        }

        $isUpdated = $this->postRepository->update($id, $updateList);

        if (!$isUpdated) {
            return false;
        }

        return true;
    }

    /**
     * Create a Post
     *
     * @param PostDto $postDto
     * @return Post
     */
    protected function createPost(PostDto $postDto): Post
    {
        // Check if title already exist and add - (1) increment
        $title = $this->incrementTitle($postDto->getTitle());
        $postDto->setTitle($title);

        // Check if slug already exist and add - (1) increment
        $slug = $this->incrementSlug($postDto->getSlug());
        $postDto->setSlug($slug);

        // Save to Post
        return $this->postRepository->create($postDto);
    }

}
