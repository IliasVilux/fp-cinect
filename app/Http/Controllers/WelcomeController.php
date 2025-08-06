<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use App\Services\WelcomeService;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(WelcomeService $welcomeService, ContentService $contentService): Response
    {
        return Inertia::render('Welcome', [
            'categories' => $welcomeService->getCategories(),
            'trendingContents' => $contentService->getTrending(),
        ]);
    }
}
