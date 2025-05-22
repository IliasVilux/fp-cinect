<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(): Response
    {
        $categories = Category::with([
            'contents' => function ($query) {
                $query->latest()->limit(1);
            }
        ])->get();

        $categories = $categories->map(function ($category) {
            $category->content = $category->contents->first();
            unset($category->contents);
            return $category;
        });

        $weekAgo = Carbon::now()->subWeek();
        $trendingContents = Content::withCount([
            'reviews' => function ($query) use ($weekAgo) {
                $query->where('created_at', '>=', $weekAgo);
            },
            'ratings' => function ($query) use ($weekAgo) {
                $query->where('created_at', '>=', $weekAgo);
            },
            'favoriteLists' => function ($query) use ($weekAgo) {
                $query->where('favorite_list_content.created_at', '>=', $weekAgo);
            },
        ])->get()->sortByDesc(function ($content) {
            return
                ($content->reviews_count * 3) +
                ($content->ratings_count * 2) +
                ($content->favorite_lists_count * 1);
        })->take(5);

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'trendingContents' => $trendingContents,
        ]);
    }
}
