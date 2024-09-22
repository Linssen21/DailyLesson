<?php

namespace Database\Factories;

use App\Domains\Post\Common\Post;
use App\Domains\Post\ValueObjects\PostStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->title();
        return [
            'author_id' => fake()->numberBetween(1, 50),
            'title' => $title,
            'content' => fake()->paragraphs(3, true),
            'excerpt' => fake()->text(100),
            'status' => new PostStatus(1),
            'type' => fake()->randomElement(['attachment', 'slide']),
            'slug' => Str::slug($title),
            'like_count' => fake()->numberBetween(0, 1000),
            'published_at' => fake()->optional()->dateTimeBetween('-1 year', 'now')
        ];
    }
}
