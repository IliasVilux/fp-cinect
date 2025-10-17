<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\FavoriteList;
use App\Models\Rating;
use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('migrate:fresh');

        $this->call(ContentSeeder::class);

        User::factory(10)->create()->each(function ($user) {
            dump('Creando usuario: '.$user->name);

            FavoriteList::factory(rand(1, 4))->create(['user_id' => $user->id])->each(function ($list) use ($user) {
                dump('  - Creando lista: '.$list->name);

                $contents = Content::inRandomOrder()->take(rand(3, 15))->get();
                $list->contents()->attach($contents->pluck('id'));

                dump('    - Agregando '.count($contents).' contenidos a la lista');

                $contents->each(function ($content) use ($user) {
                    if (fake()->boolean(40)) {
                        Rating::firstOrCreate(
                            [
                                'user_id' => $user->id,
                                'content_id' => $content->id,
                            ],
                            [
                                'score' => rand(1, 5),
                            ]
                        );
                        dump('      - Rating agregado a: '.$content->title);

                        if (fake()->boolean(45)) {
                            Review::firstOrCreate(
                                [
                                    'user_id' => $user->id,
                                    'content_id' => $content->id,
                                ],
                                [
                                    'review_text' => fake()->paragraph(),
                                ]
                            );

                            dump('      - Review agregado a: '.$content->title);
                        }
                    }
                });
            });
        });
    }
}
