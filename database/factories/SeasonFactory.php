<?php

namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Season>
 */
class SeasonFactory extends Factory
{
    use HasFactory;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->optional()->sentence(2),
            'season_number' => fake()->numberBetween(1, 10),
            'content_id' => Content::factory(),
        ];
    }
}
