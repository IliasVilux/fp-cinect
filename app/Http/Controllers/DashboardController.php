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
    public function show()
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
     * Show dashboard page for a specific type.
     *
     * @param  string  $type  Content type/Type ('series', 'movie', 'anime')
     * @return \Inertia\Response
     */
    public function showType(string $type)
    {
        $map = [
            'movies' => 'movie',
            'series' => 'series',
            'animes' => 'anime',
        ];
        $dbType = $map[$type] ?? abort(404);

        return Inertia::render('dashboard/DashboardType', [
            'featuredContent' => $this->contentService->getFeatured($dbType),
            'contentType' => $type,
            'trendingContents' => $this->contentService->getTrending($dbType),
            'contentsGroupedByGenre' => $this->contentService->getGroupedByGenre($dbType),
            'topTen' => $this->contentService->getTopTen($dbType),
        ]);
    }
}
