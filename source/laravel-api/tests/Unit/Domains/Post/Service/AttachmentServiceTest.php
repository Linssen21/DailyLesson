<?php

namespace Tests\Unit\Domains\Post\Service;

use App\Domains\Post\Common\Attachment;
use App\Domains\Post\Common\Image;
use App\Domains\Post\Common\ImageDetail;
use App\Domains\Post\Common\MediaDetail;
use App\Domains\Post\Common\Post;
use App\Domains\Post\Common\PostMeta;
use App\Domains\Post\Contracts\PostMetaRepository;
use App\Domains\Post\Contracts\PostRepository;
use App\Domains\Post\DTO\AttachmentUpdateDto;
use App\Domains\Post\DTO\PostDto;
use App\Domains\Post\DTO\PostMetaDto;
use App\Domains\Post\Service\AttachmentService;
use App\Domains\Post\ValueObjects\PostStatus;
use App\Feature\Upload\Dimension;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class AttachmentServiceTest extends TestCase
{
    // Rollback database changes
    use RefreshDatabase;

    private AttachmentService $attachmentService;
    /**
    * @var PostRepository&MockInterface
    */
    private PostRepository $postRepositoryMock;
    /**
    * @var PostMetaRepository&MockInterface
    */
    private PostMetaRepository $postMetaRepositoryMock;
    private Post $postMock;
    private Post $postImageMock;
    private PostMeta $attachmentMeta;
    private PostMeta $imageMeta;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postRepositoryMock = $this->mock(PostRepository::class);
        $this->postMetaRepositoryMock = $this->mock(PostMetaRepository::class);
        $this->attachmentService = new AttachmentService($this->postMetaRepositoryMock, $this->postRepositoryMock);
        $this->createPost();

    }

    private function createPost(): void
    {
        $this->postMock = new Post();
        $this->postMock->id = 1;
        $this->postMock->author_id = 1;
        $this->postMock->title = "test.pptx";
        $this->postMock->type = "attachment";
        $this->postMock->status = new PostStatus(1);
    }

    private function createImagePost(): void
    {
        $this->postImageMock = new Post();
        $this->postImageMock->id = 2;
        $this->postImageMock->author_id = 1;
        $this->postImageMock->title = "test_image_upload.jpg";
        $this->postImageMock->type = "attachment";
        $this->postImageMock->status = new PostStatus(1);
    }

    private function createAttachmentMeta(int $postId, Attachment $attachment): void
    {
        $this->attachmentMeta = new PostMeta();
        $this->attachmentMeta->id = 1;
        $this->attachmentMeta->meta_key = $attachment->getMetaKey();
        $this->attachmentMeta->meta_value = $attachment->getMetaValue();
        $this->attachmentMeta->post_id = $postId;
    }

    private function createImageMeta(int $postId, Image $image): void
    {
        $this->imageMeta = new PostMeta();
        $this->imageMeta->id = 1;
        $this->imageMeta->meta_key = $image->getMetaKey();
        $this->imageMeta->meta_value = $image->getMetaValue();
        $this->imageMeta->post_id = $postId;
    }


    public function test_create(): void
    {
        // Arrange
        $attachment = new Attachment(
            1,
            'uploads/test.pptx',
            'https://127.0.0.1/uploads/test.pptx',
            'file',
            8812709,
            new MediaDetail('test.pptx')
        );

        $this->postRepositoryMock->shouldReceive('create')
            ->withArgs(function ($arg) {
                return $arg instanceof PostDto &&
                    $arg->getAuthor() == 1 &&
                    $arg->getTitle() == 'test.pptx' &&
                    $arg->getType() == Attachment::META_KEY &&
                    $arg->getStatus() == new PostStatus(1);
            })
            ->once()
            ->andReturn($this->postMock);

        $this->postMetaRepositoryMock->shouldReceive('create')
            ->withArgs(function ($arg) use ($attachment) {
                return $arg instanceof PostMetaDto &&
                $arg->getPostId() == 1 &&
                $arg->getMetaKey() ==  $attachment->getMetaKey() &&
                $arg->getMetaValue() == $attachment->toJson();
            })
            ->once();

        // Act
        $this->attachmentService->create($attachment);

    }

    public function test_create_image(): void
    {
        // Arrange
        $this->createImagePost();
        $attachment = new Attachment(
            1,
            'uploads/test_image_upload.jpg',
            'https://127.0.0.1/uploads/test_image_upload.jpg',
            'img/jpg',
            8812709,
            new MediaDetail('test_image_upload.jpg')
        );

        $image = new Image(
            1,
            new Dimension(600, 400),
            new ImageDetail("test_image_upload.jpg", "Some image Alt text")
        );

        $this->postRepositoryMock->shouldReceive('create')
        ->withArgs(function ($arg) {
            return $arg instanceof PostDto &&
                $arg->getAuthor() == 1 &&
                $arg->getTitle() == 'test_image_upload.jpg' &&
                $arg->getType() == Attachment::META_KEY &&
                $arg->getStatus() == new PostStatus(1);
        })
        ->once()
        ->andReturn($this->postImageMock);

        $this->postMetaRepositoryMock->shouldReceive('createMany')
            ->withArgs(function (Collection $arg) use ($attachment, $image) {
                $expected = [
                    new PostMetaDto($this->postImageMock->getKey(), $attachment->getMetaKey(), $attachment->toJson()),
                    new PostMetaDto($this->postImageMock->getKey(), $image->getMetaKey(), $image->toJson())
                ];

                return $arg->toArray() == $expected;
            })
            ->once();


        // Act
        $this->attachmentService->createImage($attachment, $image);
    }

    public function test_update_attachment(): void
    {
        // Arrange
        $media = new MediaDetail("Media Name or Title", "Media Caption", "Media Description");
        $attachment = new Attachment(
            $this->postMock->id,
            'uploads/test.pdf',
            'https://127.0.0.1/uploads/test.pdf',
            'application/pdf',
            8812709,
            $media
        );

        $this->createAttachmentMeta($this->postMock->id, $attachment);
        $attachmentDto = new AttachmentUpdateDto($this->postMock->id, $media);

        $this->postMetaRepositoryMock->shouldReceive('getByColumns')
            ->with(['post_id' => $attachmentDto->getId(), 'meta_key' => Attachment::META_KEY])
            ->once()
            ->andReturn(collect([$this->attachmentMeta]));

        $this->postMetaRepositoryMock->shouldReceive('updateWithMeta')
            ->with($attachmentDto->getId(), $attachment->getMetaKey(), ['meta_value' => $attachment->getMetaValue()])
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->attachmentService->updateAttachment($attachmentDto);

        // Assert
        $this->assertTrue($result);
    }

    public function test_update_attachment_image(): void
    {
        // Arrange
        $mediaDetail = new MediaDetail("Media Name or Title", "Media Caption", "Media Description");
        $imageDetail = new ImageDetail("test_image_upload.jpg", "Some image Alt text");
        $dimension = new Dimension(600, 400);
        $attachment = new Attachment(
            $this->postMock->id,
            'uploads/test_image_upload.jpg',
            'https://127.0.0.1/uploads/test_image_upload.jpg',
            'image/jpeg',
            8812709,
            $mediaDetail
        );

        $image = new Image(
            $this->postMock->id,
            $dimension,
            $imageDetail
        );

        $this->createAttachmentMeta($this->postMock->id, $attachment);
        $this->createImageMeta($this->postMock->id, $image);

        $attachmentDto = new AttachmentUpdateDto($this->postMock->id, $mediaDetail, $imageDetail);

        $this->postMetaRepositoryMock->shouldReceive('getByColumns')
            ->with(['post_id' => $attachmentDto->getId(), 'meta_key' => Image::META_KEY])
            ->andReturn(collect([$this->imageMeta]));

        $this->postMetaRepositoryMock->shouldReceive('updateWithMeta')
            ->with($attachmentDto->getId(), $image->getMetaKey(), ['meta_value' => $image->getMetaValue()])
            ->andReturnTrue();

        $this->postMetaRepositoryMock->shouldReceive('getByColumns')
            ->with(['post_id' => $attachmentDto->getId(), 'meta_key' => Attachment::META_KEY])
            ->andReturn(collect([$this->attachmentMeta]));

        $this->postMetaRepositoryMock->shouldReceive('updateWithMeta')
            ->with($attachmentDto->getId(), $attachment->getMetaKey(), ['meta_value' => $attachment->getMetaValue()])
            ->andReturnTrue();

        // Act
        $result = $this->attachmentService->updateAttachment($attachmentDto);

        // Assert
        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up mocks after each test
        \Mockery::close();
    }
}
