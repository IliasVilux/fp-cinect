<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class WelcomeService
{
    /**
     * Retrieve all categories with one recent content each.
     *
     * Used on the welcome page to display a carousel of all categories
     * (across all content types) with a background image from one
     * recent content item per category.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCategories(): Collection
    {
        $categories = Category::with([
            'contents' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->get();

        return $categories->map(function (Category $category) {
            $category->content = $category->contents->first();
            unset($category->contents);
            return $category;
        });
    }
}
