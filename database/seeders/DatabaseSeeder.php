<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Content;
use App\Models\Episode;
use App\Models\FavoriteList;
use App\Models\Platform;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Season;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(10)->create();
        $platforms = Platform::factory(5)->create();

        $movies = Content::factory(8)->create(['type' => 'movie']);
        $series = Content::factory(8)->create(['type' => 'series']);
        $animes = Content::factory(8)->create(['type' => 'anime']);

        $allContents = $movies->concat($series)->concat($animes);

        $series->concat($animes)->each(function ($content) {
            $seasons = Season::factory(rand(1, 5))->create(['content_id' => $content->id]);

            $seasons->each(function ($season) {
                Episode::factory(rand(1, 10))->create(['season_id' => $season->id]);
            });
        });

        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $lists = FavoriteList::factory(rand(1, 3))->create(['user_id' => $user->id]);

            foreach ($lists as $list) {
                $randomContents = $allContents->random(rand(3, 6));
                $list->contents()->attach($randomContents->pluck('id'));
            }

            foreach ($allContents as $content) {
                $randomPlatforms = $platforms->random(rand(2, 5));
                $content->platforms()->syncWithoutDetaching($randomPlatforms->pluck('id'));
            }

            $ratedContents = $allContents->random(rand(5, 15));
            foreach ($ratedContents as $content) {
                Rating::factory()->create([
                    'user_id' => $user->id,
                    'content_id' => $content->id,
                ]);

                if (rand(0, 1)) {
                    Review::factory()->create([
                        'user_id' => $user->id,
                        'content_id' => $content->id,
                    ]);
                }
            }
        }
    }
}
