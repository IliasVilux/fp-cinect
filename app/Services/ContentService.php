<?php

namespace App\Services;

use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ContentService
{
    /**
     * Retrieve the top 5 trending contents.
     *
     * Trending score is calculated based on recent reviews, ratings, and favorites
     * from the last 7 days, sorted by weighted importance.
     *
     * @param string|null $type Optional content type (e.g., 'movie', 'series', 'anime')
     * @return \Illuminate\Support\Collection
     */
    public function getTrending(?string $type = null): Collection
    {
        $weekAgo = Carbon::now()->subWeek();
        $query = Content::query();

        if ($type) {
            $query->where('type', $type);
        }

        return Content::withCount([
            'reviews' => fn($q) => $q->where('created_at', '>=', $weekAgo),
            'ratings' => fn($q) => $q->where('created_at', '>=', $weekAgo),
            'favoriteLists' => fn($q) => $q->where('favorite_list_content.created_at', '>=', $weekAgo),
        ])
        ->get()
        ->sortByDesc(fn($content) =>
            ($content->reviews_count * 3) +
            ($content->ratings_count * 2) +
            ($content->favorite_lists_count * 1)
        )
        ->take(5)
        ->values();
    }

    /**
     * Retrieve the top 10 highest-rated contents.
     *
     * @param string|null $type Optional content type to filter results
     * @return \Illuminate\Support\Collection
     */
    public function getTopTen(?string $type = null): Collection
    {
        $query = Content::query();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->withAvg('ratings', 'rating')
            ->orderBy('ratings_avg_rating', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Retrieve the 10 most recently added contents.
     *
     * @param string|null $type Optional content type to filter results
     * @return \Illuminate\Support\Collection
     */
    public function getLatest(?string $type = null): Collection
    {
        $query = Content::query();

        if ($type) {
            $query->where('type', $type);
        }

        return $query->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Retrieve all contents of a specific type.
     *
     * @param string $type The content type ('movie', 'series', 'anime')
     * @return \Illuminate\Support\Collection
     */
    public function getByType(string $type): Collection
    {
        return Content::where('type', $type)->get();
    }

    /**
     * Retrieve all contents of a specific type, grouped by category.
     *
     * Each group contains the category and its corresponding contents.
     *
     * @param string $type The content type ('movie', 'series', 'anime')
     * @return \Illuminate\Support\Collection
     */
    public function getGroupedByCategory(string $type): Collection
    {
        return Content::where('type', $type)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(fn($contents) => [
                'category' => $contents->first()->category,
                'contents' => $contents->values(),
            ])
            ->values();
    }

    /**
     * Retrieve one random content of a specific type.
     *
     * Used as a featured item in category dashboard hero.
     *
     * @param string $type The content type ('movie', 'series', 'anime')
     * @return \App\Models\Content|null
     */
    public function getFeatured(string $type): ?Content
    {
        return Content::where('type', $type)->with('category')->inRandomOrder()->first();
    }

    /**
     * Retrieve one random content from each content type.
     *
     * @return array
     */
    public function getRandomCards(): array
    {
        return [
            Content::where('type', 'series')->inRandomOrder()->first(),
            Content::where('type', 'movie')->inRandomOrder()->first(),
            Content::where('type', 'anime')->inRandomOrder()->first(),
        ];
    }
}
