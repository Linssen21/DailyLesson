<?php

namespace Tests\Feature\Api;

use App\Domains\Post\Common\Attachment;
use App\Domains\Post\Common\MediaDetail;
use App\Domains\Post\Common\Post;
use App\Domains\Post\Common\PostMeta;
use App\Domains\Post\Slides\Template;
use App\Domains\Post\ValueObjects\PostStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Str;
use Tests\TestCase;
use Tests\Util;

class SlideTest extends TestCase
{
    // Rollback database changes
    use DatabaseTransactions;

    private Util $util;
    private string $token;
    private int $authorId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authorId = 99;
        $this->util = new Util($this);
        $this->util->createAdminUser("testadmin@test.com", "testpassadmin", $this->authorId);
        $this->token = $this->util->authAdminToken("testadmin@test.com", "testpassadmin");
    }

    /**
     * Create a slide record
     *
     * @param integer $commonId
     * @return int $id
     */
    private function createSlide(int $commonId): int
    {
        $title = "Test Slide title $commonId";
        $post = Post::create([
            'author_id' => $this->authorId,
            'title' => $title,
            'content' => "Test Slide Content",
            'excerpt' => fake()->text(100),
            'status' => new PostStatus(1),
            'type' => 'slide',
            'slug' => Str::slug($title),
            'like_count' => fake()->numberBetween(0, 1000),
            'published_at' => fake()->optional()->dateTimeBetween('-1 year', 'now')
        ]);

        $template = new Template(
            'uploads/ppt.ptx',
            'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy',
            'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview',
        );

        $post->post_meta()->create([
            'id' => $commonId,
            'meta_key' => $template->getMetaKey(),
            'meta_value' => $template->getMetaValue()
        ]);

        return $post->getKey();
    }

    /**
     * Create an attachment record
     *
     * @return void
     */
    private function createAttachment(): void
    {
        Post::factory()->create([
            'id' => 100,
            'author_id' => 99,
            'title' => "Test Attachment title 100",
        ]);

        $attachment = new Attachment(
            99,
            'uploads/test.pptx',
            'https://127.0.0.1/uploads/test.pptx',
            'file',
            8812709,
            new MediaDetail('test.pptx')
        );

        PostMeta::factory()->create([
            'id' => 100,
            'post_id' => 100,
            'meta_key' => $attachment->getMetaKey(),
            'meta_value' => $attachment->getMetaValue()
        ]);
    }



    public function test_create(): void
    {

        // Act
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")->postJson('/api/v2/admin/slide/create', [
            'title' => "Test Slide 99",
            'status' => 1,
            'ppt' => 'uploads/test.ptx',
            'google' => 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy',
            'canva' => 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview',
        ]);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS')
            ]);
    }

    public function test_get(): void
    {
        // Arrange
        $commonId = 11;
        $id = $this->createSlide($commonId);

        // Act
        $response = $this->get("/api/v2/slide/get?id=$id");

        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS'),
            ])
            ->assertJsonFragment([
                'title' => "Test Slide title $commonId",
            ]);
    }

    public function test_get_all(): void
    {
        // Arrange
        $this->createSlide(11);
        $this->createSlide(22);
        $this->createSlide(33);

        // Act
        $response = $this->get("/api/v2/slide/get");
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS'),
            ])
            ->assertJsonFragment([
                'title' => "Test Slide title 11",
                'title' => "Test Slide title 22",
                'title' => "Test Slide title 33",
            ]);

    }


    public function test_get_many(): void
    {
        // Arrange
        $this->createSlide(111);
        $this->createSlide(122);
        $this->createSlide(133);
        $this->createSlide(222);
        $this->createSlide(333);

        // Act
        $response = $this->get("/api/v2/slide/get?title=Test Slide title 1");
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS'),
            ])
            ->assertJsonFragment([
                'title' => "Test Slide title 111",
                'title' => "Test Slide title 122",
                'title' => "Test Slide title 133"
            ]);

    }


    public function test_get_with_selected_fields(): void
    {
        // Arrange
        $this->createSlide(111);
        $this->createSlide(122);
        $this->createSlide(133);
        $this->createSlide(222);
        $this->createSlide(333);

        // Act
        $response = $this->get("/api/v2/slide/get?title=Test Slide title 1&fields=id,title");
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS'),
            ])
            ->assertJsonFragment([
                'title' => "Test Slide title 111",
                'title' => "Test Slide title 122",
                'title' => "Test Slide title 133",
            ]);

    }

    public function test_get_per_page(): void
    {
        // Arrange
        $this->createSlide(111);
        $this->createSlide(122);
        $this->createSlide(133);
        $this->createSlide(222);
        $this->createSlide(333);

        // Act
        $response = $this->get("/api/v2/slide/get?per_page=10");
        $response->assertStatus(200)
            ->assertJson([
                'status' => config('constants.STATUS_SUCCESS'),
            ])
            ->assertJsonFragment([
                'title' => "Test Slide title 111",
                'title' => "Test Slide title 122",
                'title' => "Test Slide title 133",
                'title' => "Test Slide title 222",
                'title' => "Test Slide title 333",
            ]);

    }

    public function test_update(): void
    {
        // Arrange
        $id = $this->createSlide(111);

        // Act
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")->putJson("/api/v2/admin/slide/update/$id", [
            'title' => "Test Slide Update 99",
            'status' => 1,
            'ppt' => 'uploads/test_update.ptx',
            'google' => 'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy',
            'canva' => 'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview',
        ]);

        $response->assertStatus(200)->assertJson([
            'status' => config('constants.STATUS_SUCCESS'),
        ]);
    }

    public function test_update_without_slide_meta(): void
    {
        // Arrange
        $id = $this->createSlide(111);

        // Act
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")->putJson("/api/v2/admin/slide/update/$id", [
            'title' => "Test Slide Update 99",
            'status' => 1,
        ]);

        // Assert
        $response->assertStatus(200)->assertJson([
            'status' => config('constants.STATUS_SUCCESS'),
        ]);
    }

    public function test_delete(): void
    {
        // Arrange
        $id = $this->createSlide(111);

        // Act
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")->delete("/api/v2/admin/slide/delete/$id");

        // Assert
        $response->assertStatus(200)->assertJson([
            'status' => config('constants.STATUS_SUCCESS'),
        ]);
    }
}
