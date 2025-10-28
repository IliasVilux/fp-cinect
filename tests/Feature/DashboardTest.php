<?php

use App\Models\Content;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

dataset('protected_routes', [
    '/dashboard',
    '/dashboard/movie',
    '/dashboard/series',
    '/dashboard/anime',
]);
dataset('dashboard_types', [
    'movie',
    'series',
    'anime',
]);

beforeEach(function () {
    app()->setLocale('en');
    $this->user = User::factory()->create();
    $this->genre = Genre::factory()->create();
    $this->content1 = Content::factory()->create(['genre_id' => $this->genre->id, 'type' => 'movie']);
    $this->content2 = Content::factory()->create(['genre_id' => $this->genre->id, 'type' => 'series']);
    $this->content3 = Content::factory()->create(['genre_id' => $this->genre->id, 'type' => 'anime']);
});

test('guests are redirected to the login page', function ($route) {
    $response = $this->get($route);
    $response->assertRedirect('/login');
})->with('protected_routes');

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($this->user);

    $response = $this->get('/dashboard');
    $response->assertInertia(fn (Assert $page) => $page->component('dashboard/Dashboard')
        ->has('cardsbuttonContent', 3)
        ->has('recentContents', 3)
        ->has('topTen', 3)
        ->has('movies', 1)
        ->has('series', 1)
        ->has('animes', 1)
    );
});

test('authenticated users can visit the dashboard type pages', function ($type) {
    $this->actingAs($this->user);

    $response = $this->get("/dashboard/{$type}");
    $response->assertInertia(function (Assert $page) use ($type) {
        $page->component('dashboard/DashboardType')
            ->where('contentType', $type)
            ->where('featuredContent', fn ($content) => $content !== null)
            ->has('trendingContents', 1)
            ->has('topTen', 1)
            ->has('contentsGroupedByGenre', 1);
    });
})->with('dashboard_types');
