<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(ContentService $service): Response
    {
        return Inertia::render('dashboard/Dashboard', [
            'cardsbuttonContent' => $service->getRandomCards(),
            'recentContents' => $service->getLatest(),
            'series' => $service->getByType('series'),
            'movies' => $service->getByType('movie'),
            'animes' => $service->getByType('anime'),
            'topTen' => $service->getTopTen(),
        ]);
    }

    public function indexCategory(ContentService $service, string $category)
    {
        return Inertia::render('dashboard/DashboardCategory', [
            'featuredContent' => $service->getFeatured($category),
            'contentType' => $category,
            'trendingContents' => $service->getTrending($category),
            'contentsGroupedByCategory' => $service->getGroupedByCategory($category),
            'topTen' => $service->getTopTen($category),

        ]);
    }
}
