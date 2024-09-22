<?php

namespace Tests\Feature\Api;

use App\Domains\Post\Common\Attachment;
use App\Domains\Post\Common\Image;
use App\Domains\Post\Common\ImageDetail;
use App\Domains\Post\Common\MediaDetail;
use App\Domains\Post\Common\Post;
use App\Feature\Upload\Dimension;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\Util;

class UploadTest extends TestCase
{
    // Rollback database changes
    use RefreshDatabase;

    private Util $util;
    private string $token;
    private int $authorId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authorId = 99;
        $this->util = new Util($this);
        $this->util->createAdminUser("testadmin@test.com", "testpassadmin", $this->authorId);
        $this->token = "Bearer " . $this->util->authAdminToken("testadmin@test.com", "testpassadmin");
    }

    private function createImageAttachment(int $commonId): int
    {
        $post = Post::factory()->create([
            'id' => $commonId,
            'author_id' => $this->authorId,
            'title' => "Test Attachment title {$commonId}",
            'slug' => "test-attachment-title-$commonId"
        ]);

        $attachment = new Attachment(
            $commonId,
            'uploads/test_image_upload.jpg',
            'https://127.0.0.1/uploads/test_image_upload.jpg',
            'img/jpg',
            8812709,
            new MediaDetail('test_image_upload.jpg')
        );

        $image = new Image(
            $commonId,
            new Dimension(600, 400),
            new ImageDetail("test_image_upload.jpg", "Some image Alt text")
        );

        $post->post_meta()->create([
            'id' => $commonId,
            'post_id' => $post->getKey(),
            'meta_key' => $image->getMetaKey(),
            'meta_value' => $image->getMetaValue()
        ]);

        $post->post_meta()->create([
            'id' => $commonId,
            'post_id' => $post->getKey(),
            'meta_key' => $attachment->getMetaKey(),
            'meta_value' => $attachment->getMetaValue()
        ]);

        return $post->getKey();
    }

    private function createPptAttachment(int $commonId): int
    {
        $post = Post::factory()->create([
            'id' => $commonId,
            'author_id' => $this->authorId,
            'title' => "Test Attachment title {$commonId}",
            'slug' => "test-attachment-title-$commonId"
        ]);

        $attachment = new Attachment(
            $this->authorId,
            'uploads/test.pptx',
            'https://127.0.0.1/uploads/test.pptx',
            'file',
            8812709,
            new MediaDetail('test.pptx')
        );

        $post->post_meta()->create([
            'post_id' => $post->getKey(),
            'meta_key' => $attachment->getMetaKey(),
            'meta_value' => $attachment->getMetaValue()
        ]);

        return $post->getKey();
    }

    public function test_upload_attachment(): void
    {
        // Arrange
        $this->createPptAttachment(101);
        $file = UploadedFile::fake()->create('test.pptx', 1025);

        // Act
        $response = $this->withHeader('Authorization', $this->token)->postJson('/api/v2/admin/upload/create', [
            'attachment' => $file
        ]);

        // Assert
        $response->assertStatus(200)
        ->assertJson([
            'status' => config('constants.STATUS_SUCCESS')
        ]);
    }

    public function test_upload_image(): void
    {
        // Arrange
        $this->createImageAttachment(112);
        $file = UploadedFile::fake()->image('test.jpg')->size(120);

        // Act
        $response = $this->withHeader('Authorization', $this->token)->postJson('/api/v2/admin/upload/create', [
            'attachment' => $file
        ]);

        // Assert
        $response->assertStatus(200)
        ->assertJson([
            'status' => config('constants.STATUS_SUCCESS')
        ]);
    }

    public function test_update_ppt(): void
    {
        // Arrange
        $id = $this->createPptAttachment(113);

        // Act
        $response = $this->withHeader('Authorization', $this->token)->putJson("/api/v2/admin/upload/update/$id", [
            'name' => "Test name",
            'caption' => "Test name",
            'description' => "Test description"
        ]);

        // Assert
        $response->assertStatus(200)->assertJson([
            'status' => config('constants.STATUS_SUCCESS'),
        ]);
    }

    public function test_update_image(): void
    {
        // Arrange
        $id = $this->createImageAttachment(114);

        // Act
        $response = $this->withHeader('Authorization', $this->token)->putJson("/api/v2/admin/upload/update/$id", [
            'name' => "Test name",
            'caption' => "Test name",
            'description' => "Test description",
            'title' => "Test title",
            'altText' => "Test alt text",
        ]);

        // Assert
        $response->assertStatus(200)->assertJson([
            'status' => config('constants.STATUS_SUCCESS'),
        ]);
    }

    public function test_delete_ppt(): void
    {
        // Arrange
        $id = $this->createPptAttachment(115);

        // Act
        $response = $this->withHeader('Authorization', $this->token)->delete("/api/v2/admin/upload/delete/$id");

        // Assert
        $response->assertStatus(200)->assertJson([
            'status' => config('constants.STATUS_SUCCESS'),
        ]);
    }

    public function test_delete_image(): void
    {
        // Arrange
        $id = $this->createImageAttachment(116);

        // Act
        $response = $this->withHeader('Authorization', $this->token)->delete("/api/v2/admin/upload/delete/$id");

        // Assert
        $response->assertStatus(200)->assertJson([
            'status' => config('constants.STATUS_SUCCESS')
        ]);
    }

    public function test_get_ppt(): void
    {
        // Arrange
        $commonId = 117;
        $id = $this->createPptAttachment($commonId);

        // Act
        $response = $this->get("/api/v2/upload/get?id=$id");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS')
            ])
            ->assertJsonFragment([
                'title' => "Test Attachment title {$commonId}"
            ]);

    }

    public function test_get_all_ppt(): void
    {
        // Arrange
        $this->createPptAttachment(111);
        $this->createPptAttachment(222);
        $this->createPptAttachment(333);

        // Act
        $response = $this->get("/api/v2/upload/get");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS')
            ])
            ->assertJsonFragment([
                'title' => "Test Attachment title 111",
                'title' => "Test Attachment title 222",
                'title' => "Test Attachment title 333",
            ]);
    }

    public function test_get_many_ppt(): void
    {
        // Arrange
        $this->createPptAttachment(111);
        $this->createPptAttachment(122);
        $this->createPptAttachment(133);
        $this->createPptAttachment(222);
        $this->createPptAttachment(333);

        // Act
        $response = $this->get("/api/v2/upload/get?title=Test Attachment title 1");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS')
            ])
            ->assertJsonFragment([
                'title' => "Test Attachment title 111",
                'title' => "Test Attachment title 122",
                'title' => "Test Attachment title 133",
            ]);
    }

    public function test_get_image(): void
    {
        // Arrange
        $commonId = 117;
        $id = $this->createImageAttachment($commonId);

        // Act
        $response = $this->get("/api/v2/upload/get?id=$id");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS')
            ])
            ->assertJsonFragment([
                'title' => "Test Attachment title {$commonId}"
            ]);

    }

    public function test_get_all_image(): void
    {
        // Arrange
        $this->createImageAttachment(111);
        $this->createImageAttachment(222);
        $this->createImageAttachment(333);

        // Act
        $response = $this->get("/api/v2/upload/get");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS')
            ])
            ->assertJsonFragment([
                'title' => "Test Attachment title 111",
                'title' => "Test Attachment title 222",
                'title' => "Test Attachment title 333",
            ]);
    }

    public function test_get_many_image(): void
    {
        // Arrange
        $this->createImageAttachment(111);
        $this->createImageAttachment(122);
        $this->createImageAttachment(133);
        $this->createImageAttachment(222);
        $this->createImageAttachment(333);

        // Act
        $response = $this->get("/api/v2/upload/get?title=Test Attachment title 1");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS')
            ])
            ->assertJsonFragment([
                'title' => "Test Attachment title 111",
                'title' => "Test Attachment title 122",
                'title' => "Test Attachment title 133",
            ]);
    }

}
