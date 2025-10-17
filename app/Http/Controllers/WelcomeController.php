<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use App\Services\WelcomeService;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function __construct(
        private WelcomeService $welcomeService,
        private ContentService $contentService,
    ) {}

    /**
     * Show welcome page.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Welcome', [
            'randomContent' => $this->welcomeService->getRandomContent(),
            'genres' => $this->welcomeService->getGenres(),
            'trendingContents' => $this->contentService->getTrending(),
        ]);
    }
}
