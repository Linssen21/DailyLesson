<?php

namespace Tests\Unit\Domains\Post\Service;

use App\Common\Column;
use App\Common\QueryParams;
use App\Domains\Post\Common\Post;
use App\Domains\Post\Contracts\PostMetaRepository;
use App\Domains\Post\Contracts\PostRepository;
use App\Domains\Post\DTO\PostDto;
use App\Domains\Post\Service\PostService;
use App\Domains\Post\Slides\Slides;
use App\Domains\Post\Slides\Template;
use App\Domains\Post\ValueObjects\PostStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use ReflectionClass;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    // Rollback database changes
    use DatabaseTransactions;

    private PostService $postService;
    private Slides $slidesMock;

    /**
    * @var PostRepository&MockInterface
    */
    private PostRepository $postRepositoryMock;
    /**
    * @var PostMetaRepository&MockInterface
    */
    private PostMetaRepository $postMetaRepositoryMock;


    public function setUp(): void
    {
        parent::setUp();
        $this->postRepositoryMock = $this->mock(PostRepository::class);
        $this->postMetaRepositoryMock = $this->mock(PostMetaRepository::class);
        $this->postService = new PostService($this->postRepositoryMock, $this->postMetaRepositoryMock);
        $this->createSlide();
    }

    private function createSlide(): void
    {
        $this->slidesMock = new Slides();
        $this->slidesMock->id = 1;
        $this->slidesMock->content = 'test content';
        $this->slidesMock->title = 'Test Title';
        $this->slidesMock->excerpt = 'test excerpt';
        $this->slidesMock->status = new PostStatus(1);
        $this->slidesMock->slug = 'test-slug';
    }

    public function test_increment_title(): void
    {
        // Arrange
        $title = "Test Title";
        $expectedTitle = "Test Title (2)";

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['title' => "Test Title%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        // Act
        $reflection = new ReflectionClass($this->postService);
        $method = $reflection->getMethod('incrementTitle');
        $method->setAccessible(true);
        $result = $method->invoke($this->postService, $title);

        // Assert
        $this->assertEquals($expectedTitle, $result);
    }

    public function test_increment_slug(): void
    {
        // Arrange
        $slug = "Test Slug";
        $expectedSlug = "test-slug-2";

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['slug' => "test-slug%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        // Act
        $reflection = new ReflectionClass($this->postService);
        $method = $reflection->getMethod('incrementSlug');
        $method->setAccessible(true);
        $result = $method->invoke($this->postService, $slug);

        // Assert
        $this->assertEquals($expectedSlug, $result);
    }

    public function test_get_with_pagination(): void
    {
        // Arrange
        $params = new QueryParams(
            columns: collect([new Column('id', '=', 1)]),
            fields: ['id, title'],
            page: 1,
        );

        $postOne = new Post();
        $postOne->id = 1;
        $postOne->title = 'Post test 1';

        $this->postRepositoryMock->shouldReceive('getWithPagination')
            ->with($params)
            ->once()
            ->andReturn(new LengthAwarePaginator([$postOne], 1, 10));

        // Act
        $post = $this->postService->getWithPagination($params);

        // Assert
        $this->assertEquals(1, $post->total());
        $this->assertEquals(1, $post->lastPage());
        $this->assertIsArray($post->toArray());
    }

    public function test_get(): void
    {
        // Arrange
        $params = new QueryParams(
            columns: collect([new Column('id', '=', 1)]),
            fields: ['id, title']
        );

        $postOne = new Post();
        $postOne->id = 1;
        $postOne->title = 'Post test 1';

        $this->postRepositoryMock->shouldReceive('getAllByColumn')
            ->with($params)
            ->once()
            ->andReturn(new Collection([$postOne]));

        // Act
        $result = $this->postService->get($params);

        // Assert
        $this->assertEquals(1, $result->count());
        $this->assertIsArray($result->toArray());
    }

    public function test_get_not_found(): void
    {
        // Arrange
        $params = new QueryParams(
            columns: collect([new Column('id', '=', 2), new Column('title', 'LIKE', '%Post test 2%')]),
            fields: ['id, title']
        );

        $this->postRepositoryMock->shouldReceive('getAllByColumn')
            ->with($params)
            ->once()
            ->andReturn(new Collection());

        // Act
        $result = $this->postService->get($params);

        // Assert
        $this->assertEquals(0, $result->count());
    }


    public function test_soft_delete_with_meta(): void
    {
        // Arrange
        $this->postRepositoryMock->shouldReceive('find')
            ->with($this->slidesMock->getKey())
            ->once()
            ->andReturn($this->slidesMock);

        $this->postMetaRepositoryMock->shouldReceive('updateWithMeta')
            ->with($this->slidesMock->getKey(), Template::META_KEY, ['is_deleted' => 1])
            ->once();

        // Act
        $result = $this->postService->softDelete($this->slidesMock->getKey(), Template::META_KEY);

        // Assert
        $this->assertTrue($result);
    }

    public function test_soft_delete(): void
    {
        // Arrange
        $this->postRepositoryMock->shouldReceive('find')
        ->with($this->slidesMock->getKey())
        ->once()
        ->andReturn($this->slidesMock);

        $this->postMetaRepositoryMock->shouldReceive('update')
            ->with($this->slidesMock->getKey(), ['is_deleted' => 1])
            ->once();

        // Act
        $result = $this->postService->softDelete($this->slidesMock->getKey());

        // Assert
        $this->assertTrue($result);
    }

    public function test_update(): void
    {
        // Arrange
        $postDto = new PostDto(
            1,
            $this->slidesMock->content,
            $this->slidesMock->title,
            $this->slidesMock->excerpt,
            $this->slidesMock->status,
            $this->slidesMock->slug
        );

        $this->postRepositoryMock->shouldReceive('find')
            ->with($this->slidesMock->getKey())
            ->once()
            ->andReturn($this->slidesMock);

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['title' => "Test Title%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['slug' => "test-slug%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        $updateList = $postDto->toFilteredArray();
        $updateList['title'] = 'Test Title (2)';
        $updateList['slug'] = 'test-slug-2';
        $updateList['status'] = $postDto->getStatus()->getStatus();

        $this->postRepositoryMock->shouldReceive('update')
            ->with($this->slidesMock->getKey(), $updateList)
            ->once()
            ->andReturn(true);

        // Act
        $reflection = new ReflectionClass($this->postService);
        $method = $reflection->getMethod('updatePost');
        $method->setAccessible(true);
        $method->invoke($this->postService, $this->slidesMock->getKey(), $postDto);
    }

    public function test_create(): void
    {
        // Arrange
        $postDto = new PostDto(
            1,
            $this->slidesMock->content,
            $this->slidesMock->title,
            $this->slidesMock->excerpt,
            $this->slidesMock->status,
            $this->slidesMock->slug
        );

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['title' => "Test Title%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['slug' => "test-slug%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        $this->postRepositoryMock->shouldReceive('create')
            ->withArgs(function ($arg) {
                return $arg instanceof PostDto &&
                    $arg->getTitle() == 'Test Title (2)' &&
                    $arg->getAuthor() == 1 &&
                    $arg->getContent() == 'test content' &&
                    $arg->getSlug() == 'test-slug-2';

            })
            ->once()
            ->andReturn($this->slidesMock);

        // Act
        $reflection = new ReflectionClass($this->postService);
        $method = $reflection->getMethod('createPost');
        $method->setAccessible(true);
        $result = $method->invoke($this->postService, $postDto);

        // Assert
        $this->assertEquals($this->slidesMock, $result);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up mocks after each test
        \Mockery::close();
    }
}
