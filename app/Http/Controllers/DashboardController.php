<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $serie = Content::where('type', 'series')->inRandomOrder()->first();
        $movie = Content::where('type', 'movie')->inRandomOrder()->first();
        $anime = Content::where('type', 'anime')->inRandomOrder()->first();

        $series = Content::where('type', 'series')->get();
        $movies = Content::where('type', 'movie')->get();
        $animes = Content::where('type', 'anime')->get();

        return Inertia::render('Dashboard', [
            'cardsbuttonContent' => [$serie, $movie, $anime],
            'series' => $series,
            'movies' => $movies,
            'animes' => $animes,
        ]);
    }
}
