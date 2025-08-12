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
            'categories' => $this->welcomeService->getCategories(),
            'trendingContents' => $this->contentService->getTrending(),
        ]);
    }
}
