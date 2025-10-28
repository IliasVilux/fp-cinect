<?php

use App\Models\Content;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->genre = Genre::factory()->create();
    $this->content = Content::factory()->create(['genre_id' => $this->genre->id, 'backdrop_image' => 'https://example.com/backdrop.jpg']);
});

test('landing page loads with featured, genres and trending contents', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(200)->assertInertia(fn (Assert $page) => $page->component('Welcome')
        ->where('randomContent', fn ($content) => $content !== null)
        ->has('genres', 1)
        ->has('trendingContents', 1)
    );
});

test('guest sees login button in header', function () {
    $response = $this->get(route('home'));
    $response->assertStatus(200)->assertInertia(fn (Assert $page) => $page->component('Welcome')->has('auth')->where('auth.user', null)
    );
});

test('authenticated user sees dashboard button in header', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));
    $response->assertInertia(fn (Assert $page) => $page->component('Welcome')->has('auth.user', fn (Assert $userProp) => $userProp->where('name', $user->name)->where('email', $user->email)->etc()
    )
    );
});
