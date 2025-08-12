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
     * @return Collection
     */
    public function getTrending(?string $type = null)
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
        ->sortByDesc(fn(Content $content) =>
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
     * @return Collection
     */
    public function getTopTen(?string $type = null)
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
     * @return Collection
     */
    public function getLatest(?string $type = null)
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
     * @return Collection
     */
    public function getByType(string $type)
    {
        return Content::where('type', $type)->get();
    }

    /**
     * Retrieve all contents of a specific type, grouped by category.
     *
     * Each group contains the category and its corresponding contents.
     *
     * @param string $type The content type ('movie', 'series', 'anime')
     * @return Collection
     */
    public function getGroupedByCategory(string $type)
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
     * @return Content|null
     */
    public function getFeatured(string $type)
    {
        return Content::where('type', $type)->with('category')->inRandomOrder()->first();
    }

    /**
     * Retrieve one random content from each content type.
     *
     * @return Content[] Array of 3 contents types ('series', 'movie', 'anime')
     */
    public function getRandomCards()
    {
        return [
            Content::where('type', 'series')->inRandomOrder()->first(),
            Content::where('type', 'movie')->inRandomOrder()->first(),
            Content::where('type', 'anime')->inRandomOrder()->first(),
        ];
    }

    /**
     * Retrieve a specific content with all related fields (category, seasons, episodes, ratings, ) .
     *
     * @param string $id The content ID
     * @return Content|null
     */
    public function getById(string $id)
    {
        return Content::with(
            ['category',
            'seasons.episodes',
            'userRating',
            'userReview',
            'reviews.user'
            ])
            ->withAvg('ratings', 'rating')
            ->findOrFail($id);
    }

    /**
     * Retrieve 10 random contents related to specific content.
     *
     * @param Content $content
     * @return Collection
     */

    public function getRelated(Content $content)
    {
        return Content::where('category_id', $content->category_id)
            ->where('id', '!=', $content->getKey())
            ->where('type', $content->type)
            ->inRandomOrder()
            ->take(10)
            ->get();
    }

    /**
     * Retrieve filtered contents with pagination.
     *
     * @param array $filters <text>
     * @param int $perPage <text>
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getFiltered(array $filters, int $perPage = 24)
    {
        $query = Content::query();

        $query->when(
            !empty($filters['contentType']),
            fn($q) =>
            $q->where('type', $filters['contentType'])
        );

        $query->when(
            !empty($filters['categoryId']),
            fn($q) =>
            $q->where('category_id', $filters['categoryId'])
        );

        $query->when(
            !empty($filters['searchContent']),
            fn($q) =>
            $q->where('title', 'like', '%' . $filters['searchContent'] . '%')
        );

        $orders = [
            'name_asc' => ['title', 'asc'],
            'name_desc' => ['title', 'desc'],
            'recent' => ['created_at', 'desc'],
            'top_rated' => ['rating', 'desc'],  // TODO
            'low_rated' => ['rating', 'asc'],   // TODO
        ];

        $orderBy = $filters['orderBy'] ?? null;

        if ($orderBy && isset($orders[$orderBy])) {
            $query->orderBy(...$orders[$orderBy]);
        } else {
            $query->latest();
        }

        return $query->paginate($perPage);
    }
}
