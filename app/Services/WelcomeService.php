<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Genre;
use Illuminate\Support\Collection;

class WelcomeService
{
    /**
     * Retrieve all genres with one recent content each.
     *
     * Used on the welcome page to display a carousel of all genres
     * (across all content types) with a background image from one
     * recent content item per genre.
     */
    public function getGenres(): Collection
    {
        $genres = Genre::with([
            'contents' => function ($query) {
                $query->whereNotNull('poster_image')->latest()->limit(1);
            },
        ])->get();

        return $genres->map(function (Genre $genre) {
            $genre->content = $genre->contents->first();
            unset($genre->contents);

            return $genre;
        });
    }

    /**
     * Retrieve one random content.
     */
    public function getRandomContent()
    {
        return Content::whereNotNull('backdrop_image')->inRandomOrder()->first();
    }
}
