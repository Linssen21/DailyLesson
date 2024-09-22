<?php

namespace Database\Factories;

use App\Domains\Post\Common\PostMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class PostMetaFactory extends Factory
{
    protected $model = PostMeta::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'meta_key' => fake()->word(),
            'meta_value' => json_encode([
                'key1' => fake()->word(),
                'key2' => fake()->numberBetween(1, 100),
                'key3' => fake()->boolean()
            ]),
            'is_deleted' => 0
        ];
    }
}
