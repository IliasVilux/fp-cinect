<?php

namespace App\Http\Controllers;

use App\Services\ContentService;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(ContentService $service): Response
    {
        return Inertia::render('Welcome', [
            'categories' => $service->getCategories(),
            'trendingContents' => $service->getTrendingContents(),
        ]);
    }
}
