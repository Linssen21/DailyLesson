<?php

declare(strict_types=1);

namespace App\Domains\Post\Service;

use App\Domains\Post\Common\Attachment;
use App\Domains\Post\Common\Image;
use App\Domains\Post\Common\ImageDetail;
use App\Domains\Post\Common\Post;
use App\Domains\Post\Common\PostMeta;
use App\Domains\Post\Contracts\PostMetaRepository;
use App\Domains\Post\Contracts\PostRepository;
use App\Domains\Post\DTO\AttachmentUpdateDto;
use App\Domains\Post\DTO\PostDto;
use App\Domains\Post\DTO\PostMetaDto;
use App\Domains\Post\ValueObjects\PostStatus;

/**
 * Attachment Domain Service
 *
 * @ticket Feature/DL-4
 *
 * @package Daily Lesson project
 * @author Sen <vmtesterv@gmail.com>
 * @version 1.0.0
 */
class AttachmentService extends PostService
{
    public function __construct(
        private PostMetaRepository $postMetaRepository,
        private PostRepository $postRepository,
    ) {
        parent::__construct($postRepository, $postMetaRepository);
    }

    /**
     * Create a post and meta with a type of attachment
     *
     * @param Attachment $attachment
     * @return void
     */
    public function create(Attachment $attachment): void
    {
        $post = $this->createPostAttachment($attachment);

        // Save attachment object to Post Meta
        $attachmentMeta = new PostMetaDto(
            $post->getKey(),
            $attachment->getMetaKey(),
            $attachment->toJson()
        );

        $this->postMetaRepository->create($attachmentMeta);
    }

    /**
     * Create a post and meta with a type of attachment and image
     *
     * @param Attachment $attachment
     * @param Image $image
     * @return void
     */
    public function createImage(Attachment $attachment, Image $image): void
    {
        $post = $this->createPostAttachment($attachment);

        // Save attachment object to Post Meta
        $attachmentMeta = new PostMetaDto(
            $post->getKey(),
            $attachment->getMetaKey(),
            $attachment->toJson()
        );

        // Save image object to Post Meta
        $imageMeta = new PostMetaDto(
            $post->getKey(),
            $image->getMetaKey(),
            $image->toJson()
        );

        $postMetaCollection = collect([$attachmentMeta, $imageMeta]);

        $this->postMetaRepository->createMany($postMetaCollection);
    }

    /**
     * Create an attachment post
     *
     * @param Attachment $attachment
     * @return Post
     */
    private function createPostAttachment(Attachment $attachment): Post
    {
        // Save to Post as an attachment
        $postDto = new PostDto(
            authorId: $attachment->getAuthor(),
            title: $attachment->getMediaDetail()->getName(),
            type: $attachment->getMetaKey(),
            status: new PostStatus(1)
        );

        return $this->postRepository->create($postDto);
    }

    /**
     * Update an attachment post and create meta for image or default meta
     *
     * @param AttachmentUpdateDto $params
     * @return boolean
     */
    public function updateAttachment(AttachmentUpdateDto $params): bool
    {
        if ($params->isImageDetail()) {
            $isImageUpdate = $this->updateImage($params->getId(), $params->getImageDetail());
            if (!$isImageUpdate) {
                return false;
            }
        }

        $attachmentMeta = $this->postMetaRepository->getByColumns(
            ['post_id' => $params->getId(), 'meta_key' => Attachment::META_KEY]
        );

        /** @var PostMeta|null $attachment */
        $meta = $attachmentMeta->first();

        if (empty($meta)) {
            return false;
        }

        $attachment = Attachment::fromJson($meta->meta_value);
        $attachment->setMediaDetail($params->getMediaDetail());

        return $this->postMetaRepository->updateWithMeta($params->getId(), $attachment->getMetaKey(), ['meta_value' => $attachment->getMetaValue()]);
    }

    /**
     * Update attachment image meta
     *
     * @param integer $id
     * @param ImageDetail $params
     * @return boolean
     */
    private function updateImage(int $id, ImageDetail $params): bool
    {
        /** @var PostMeta|null $imageMeta */
        $imageMeta = $this->postMetaRepository->getByColumns(
            ['post_id' => $id, 'meta_key' => Image::META_KEY]
        )->first();

        if (empty($imageMeta)) {
            return false;
        }

        $image = Image::fromJson($imageMeta->meta_value);
        $image->setImageDetail($params);

        return $this->postMetaRepository->updateWithMeta($id, $image->getMetaKey(), ['meta_value' => $image->getMetaValue()]);
    }
}
