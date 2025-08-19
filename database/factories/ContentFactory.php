<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'type' => fake()->randomElement(['movie', 'series', 'anime']),
            'release_year' => fake()->optional()->year(),
            'duration' => fake()->optional()->numberBetween(1, 220),
            'genre_id' => Genre::inRandomOrder()->first()->id,
            'cover_image' => fake()->optional()->imageUrl(640, 480, 'movies', true),
        ];
    }
}
