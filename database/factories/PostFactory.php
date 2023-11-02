<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'excerpt' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'cover' => $this->faker->imageUrl(),
            'published_at' => $this->faker->boolean() ? $this->faker->dateTimeBetween('-15 days', '+1 months') : null,
        ];
    }
}
