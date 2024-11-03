<?php

namespace Tests\Unit\Domains\Post\Service;

use App\Domains\Post\Contracts\PostMetaRepository;
use App\Domains\Post\Contracts\PostRepository;
use App\Domains\Post\Contracts\SlideRepository;
use App\Domains\Post\DTO\PostDto;
use App\Domains\Post\DTO\PostMetaDto;
use App\Domains\Post\DTO\SlideCreateDto;
use App\Domains\Post\Service\SlideService;
use App\Domains\Post\Slides\Slides;
use App\Domains\Post\Slides\SlidesMeta;
use App\Domains\Post\Slides\Template;
use App\Domains\Post\ValueObjects\PostStatus;
use App\Domains\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use Tests\TestCase;

class SlideServiceTest extends TestCase
{
    // Rollback database changes
    use DatabaseTransactions;

    private SlideService $slideService;
    /**
    * @var SlideRepository&MockInterface
    */
    private SlideRepository $slideRepositoryMock;
    /**
    * @var PostMetaRepository&MockInterface
    */
    private PostMetaRepository $postMetaRepositoryMock;
    /**
    * @var PostRepository&MockInterface
    */
    private PostRepository $postRepositoryMock;

    private User $userMock;
    private Slides $slidesMock;
    private SlidesMeta $slidesMetaMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->slideRepositoryMock = $this->mock(SlideRepository::class);
        $this->postMetaRepositoryMock = $this->mock(PostMetaRepository::class);
        $this->postRepositoryMock = $this->mock(PostRepository::class);
        $this->slideService = new SlideService(
            $this->postMetaRepositoryMock,
            $this->slideRepositoryMock,
            $this->postRepositoryMock
        );

        $this->createUser();
        $this->createSlide();
    }

    /**
    * Create a Mock User
    *
    * @return void
    */
    private function createUser(): void
    {
        $this->userMock = new User();
        $this->userMock->id = 1;
        $this->userMock->email = 'test@test.com';
        $this->userMock->name = 'testname';
        $this->userMock->display_name = 'Test Name';
        $this->userMock->password = 'password';
    }

    private function createSlide(): void
    {
        $this->slidesMock = new Slides();
        $this->slidesMock->id = 1;
        $this->slidesMock->content = 'test content';
        $this->slidesMock->title = 'test title';
        $this->slidesMock->excerpt = 'test excerpt';
        $this->slidesMock->status = new PostStatus(1);
        $this->slidesMock->slug = 'test-slug';
    }


    public function test_create(): void
    {
        // Arrange
        $slideDto = new SlideCreateDto(
            $this->userMock->id,
            'test content',
            'test title',
            'test excerpt',
            new PostStatus(1),
            'test-slug'
        );

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['title' => "test title%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['slug' => "test-slug%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        $this->slideRepositoryMock->shouldReceive('create')
            ->withArgs(function ($arg) {
                return $arg instanceof SlideCreateDto &&
                    $arg->getTitle() == 'test title (2)' &&
                    $arg->getAuthor() == $this->userMock->id &&
                    $arg->getContent() == 'test content' &&
                    $arg->getSlug() == 'test-slug-2';

            })
            ->once()
            ->andReturn($this->slidesMock);

        $template = new Template($slideDto->getPpt(), $slideDto->getGoogle(), $slideDto->getCanva());
        $slideMeta = new PostMetaDto($this->slidesMock->id, Template::META_KEY, $template->toJson());

        $this->postMetaRepositoryMock->shouldReceive('create')
            ->withArgs(function ($arg) use ($slideMeta) {
                return $arg instanceof PostMetaDto &&
                $arg == $slideMeta;
            })
            ->once();

        // Act
        $this->slideService->create($slideDto);

    }

    public function test_update(): void
    {
        // Arrange
        $postDto = new PostDto(
            $this->userMock->id,
            $this->slidesMock->content,
            $this->slidesMock->title,
            $this->slidesMock->excerpt,
            $this->slidesMock->status,
            $this->slidesMock->slug,
        );

        $template = new Template(
            'uploads/ppt.ptx',
            'https://docs.google.com/presentation/d/1rx8KFxkQ0zkFK0TTWLtigmHtfHhZ-2wNqldAKlD3gwQ/copy',
            'https://www.canva.com/design/DAGIA3IH2Ro/qZEXKF8hCtqQKEk_-8mDOw/view?utm_content=DAGIA3IH2Ro&utm_campaign=designshare&utm_medium=link&utm_source=publishsharelink&mode=preview',
        );

        $this->postRepositoryMock->shouldReceive('find')
            ->with($this->slidesMock->id)
            ->once()
            ->andReturn($this->slidesMock);

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['title' => "test title%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        $this->postRepositoryMock->shouldReceive('getByColumns')
            ->with(['slug' => "test-slug%"], 'like')
            ->once()
            ->andReturn(new Collection([$this->slidesMock]));

        $updateList = $postDto->toFilteredArray();
        $updateList['title'] = "test title (2)";
        $updateList['slug'] = "test-slug-2";
        $updateList['status'] = $postDto->getStatus()->getStatus();

        $this->postRepositoryMock->shouldReceive('update')
            ->with($this->slidesMock->getKey(), $updateList)
            ->once()
            ->andReturn(true);

        $this->postMetaRepositoryMock->shouldReceive('updateWithMeta')
            ->with($this->slidesMock->getKey(), $template->getMetaKey(), ['meta_value' => $template->getMetaValue()])
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->slideService->update($this->slidesMock->id, $postDto, $template);

        $this->assertTrue($result);

    }

    public function test_delete(): void
    {
        // Arrange
        $id = $this->slidesMock->id;

        $this->postRepositoryMock->shouldReceive('find')
            ->with($this->slidesMock->id)
            ->once()
            ->andReturn($this->slidesMock);

        $this->postMetaRepositoryMock->shouldReceive('updateWithMeta')
            ->with($this->slidesMock->getKey(), Template::META_KEY, ['is_deleted' => 1])
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->slideService->delete($id);

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
