<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private ContentService $contentService,
    ) {}

    /**
     * Show the main dashboard page.
     *
     * @return Response
     */
    public function index()
    {
        return Inertia::render('dashboard/Dashboard', [
            'cardsbuttonContent' => $this->contentService->getRandomCards(),
            'recentContents' => $this->contentService->getLatest(),
            'series' => $this->contentService->getByType('series'),
            'movies' => $this->contentService->getByType('movie'),
            'animes' => $this->contentService->getByType('anime'),
            'topTen' => $this->contentService->getTopTen(),
        ]);
    }

    /**
     * Show dashboard page for a specific category.
     *
     * @param  string  $category  Content type/category ('series', 'movie', 'anime')
     * @return \Inertia\Response
     */
    public function indexCategory(string $category)
    {
        return Inertia::render('dashboard/DashboardCategory', [
            'featuredContent' => $this->contentService->getFeatured($category),
            'contentType' => $category,
            'trendingContents' => $this->contentService->getTrending($category),
            'contentsGroupedByCategory' => $this->contentService->getGroupedByCategory($category),
            'topTen' => $this->contentService->getTopTen($category),

        ]);
    }
}
