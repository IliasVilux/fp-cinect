<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $recentContents = Content::orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        $serie = Content::where('type', 'series')->inRandomOrder()->first();
        $movie = Content::where('type', 'movie')->inRandomOrder()->first();
        $anime = Content::where('type', 'anime')->inRandomOrder()->first();

        $series = Content::where('type', 'series')->get();
        $movies = Content::where('type', 'movie')->get();
        $animes = Content::where('type', 'anime')->get();

        return Inertia::render('dashboard/Dashboard', [
            'cardsbuttonContent' => [$serie, $movie, $anime],
            'recentContents' => $recentContents,
            'series' => $series,
            'movies' => $movies,
            'animes' => $animes,
        ]);
    }

    public function indexCategory(string $category)
    {
        // Randomly select a hero content from the specified category
        $featuredContent = Content::where('type', $category)->with('category')->inRandomOrder()->first();

        $weekAgo = Carbon::now()->subWeek();
        // Get trending category content based on reviews, ratings, and favorite lists in the last week
        $trendingContents = Content::where('type', $category)->withCount([
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
        })->take(10);

        // Group contents by category
        $contentsGroupedByCategory = Content::where('type', $category)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(function ($contents) {
                return [
                    'category' => $contents->first()->category,
                    'contents' => $contents->values(),
                ];
            })
            ->values();

        return Inertia::render('dashboard/DashboardCategory', [
            'featuredContent' => $featuredContent,
            'contentType' => $category,
            'trendingContents' => $trendingContents,
            'contentsGroupedByCategory' => $contentsGroupedByCategory
        ]);
    }
}
