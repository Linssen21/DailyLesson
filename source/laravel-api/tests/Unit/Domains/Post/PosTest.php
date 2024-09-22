<?php

declare(strict_types=1);

namespace Tests\Unit\Domains\Post;

use App\Domains\Post\Common\Post;
use App\Domains\Post\ValueObjects\PostStatus;
use Tests\TestCase;

class PosTest extends TestCase
{
    /**
    * Create a mock post object, add reference with Post Class and Mock Object class
    *
    * @var Post&MockObject
    */
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post = $this->getMockBuilder(Post::class)
            ->onlyMethods(['save'])
            ->getMock();
        $this->post->exists = true;
    }

    public function test_excerpt(): void
    {
        // Arrange
        $content = 'This is a sample content for testing the content field';
        $this->post->content = $content;

        // Act
        $this->post->excerpt = '';

        // Assert
        $this->assertEquals($content, $this->post->excerpt);

    }

    public function test_excerpt_ellipsis(): void
    {
        // Arrange
        $content = 'This is a sample content for testing the content field';
        $this->post->content = $content;
        $this->post->setExcerptLength(40);
        $expected_excerpt = 'This is a sample content for testing the...';

        // Act
        $this->post->excerpt = '';

        // Assert
        $this->assertEquals($expected_excerpt, $this->post->excerpt);

    }

    public function test_excerpt_with_content_and_set_excerpt(): void
    {
        // Arrange
        $content = 'This is a sample content for testing the content field';
        $this->post->content = $content;
        $expected_excerpt = 'This is an excerpt test';

        // Act
        $this->post->excerpt = $expected_excerpt;

        // Assert
        $this->assertEquals($expected_excerpt, $this->post->excerpt);

    }

    public function test_slug_based_on_title(): void
    {
        // Arrange
        $title = 'This is a sample title';
        $this->post->title = $title;
        $expected_slug = 'this-is-a-sample-title';

        // Act
        $this->post->slug = '';

        // Assert
        $this->assertEquals($expected_slug, $this->post->slug);

    }

    public function test_slug_based_on_set_slug(): void
    {
        // Arrange
        $title = 'This is a sample title';
        $this->post->title = $title;
        $expected_slug = 'this-is-a-sample-slug';

        // Act
        $this->post->slug = 'this-is-a-sample-slug';

        // Assert
        $this->assertEquals($expected_slug, $this->post->slug);

    }

    public function test_increment_like_count(): void
    {
        // Arrange
        $this->post->like_count = 5;

        // Act
        $this->post->incrementLikeCount(5);

        // Assert
        $this->assertEquals(10, $this->post->like_count);
    }


    public function test_decrement_like_count(): void
    {
        // Arrange
        $this->post->like_count = 5;

        // Act
        $this->post->decrementLikeCount(3);

        // Assert
        $this->assertEquals(2, $this->post->like_count);
    }


    public function test_soft_delete(): void
    {
        // Arrange
        $this->post->softDelete();

        // Act
        $result = $this->post->isDeleted();

        // Assert
        $this->assertTrue($result);
        $this->assertEquals($this->post->status, new PostStatus(3));
    }
}
