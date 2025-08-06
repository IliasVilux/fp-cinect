<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ContentService
{
    public function getCategories(): Collection
    {
        $categories = Category::with([
            'contents' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->get();

        return $categories->map(function ($category) {
            $category->content = $category->contents->first();
            unset($category->contents);
            return $category;
        });
    }

    public function getTrendingContents(): Collection
    {
        $weekAgo = Carbon::now()->subWeek();

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
}
