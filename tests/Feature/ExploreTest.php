<?php

use App\Models\User;
use App\Models\Content;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->genre = Genre::factory()->create();
});

test('explore page loads with contents and genresItems', function () {
    $this->content = Content::factory()->create(['genre_id' => $this->genre->id]);
    $this->actingAs($this->user);

    $response = $this->get(route('contents.explore'));
    $response->assertStatus(status: 200)->assertInertia(fn (Assert $page) =>
        $page->component('content/Explore')
            ->has('contents.data', 1)
            ->has('genresItems', 1)
    );
});

test('explore page paginates correctly', function () {
    $this->actingAs($this->user);
    Content::factory()->count(30)->create();

    $response = $this->get(route('contents.explore'));
    $response->assertStatus(status: 200)->assertInertia(fn (Assert $page) =>
        $page->component('content/Explore')
            ->has('contents.data', 24)
            ->where('contents.total', 30)
    );
});

test('explore page filters by contentType', function () {
    $this->actingAs($this->user);
    Content::factory()->create(['genre_id' => $this->genre->id, 'type' => 'movie']);
    Content::factory()->create(['genre_id' => $this->genre->id, 'type' => 'series']);

    $response = $this->get(uri: route('contents.explore', ['contentType' => 'movie']));
    $response->assertStatus(200)->assertInertia(fn (Assert $page) =>
        $page->component('content/Explore')
            ->has('contents.data', 1)
            ->where('contents.data.0.type', 'movie')
    );
});

test('explore page filters by genre', function () {
    $this->actingAs($this->user);
    $genre2 = Genre::factory()->create();
    Content::factory()->create(['genre_id' => $this->genre->id, 'type' => 'movie']);
    Content::factory()->create(['genre_id' => $genre2->id, 'type' => 'series']);

    $response = $this->get(uri: route('contents.explore', ['genreId' => $genre2->id]));
    $response->assertStatus(200)->assertInertia(fn (Assert $page) =>
        $page->component('content/Explore')
            ->has('contents.data', 1)
            ->where('contents.data.0.genre_id', $genre2->id)
    );
});

test('explore page orders contents', function () {
    $this->actingAs($this->user);
    $first = Content::factory()->create(['title' => 'a', 'genre_id' => $this->genre->id]);
    $second = Content::factory()->create(['title' => 'z', 'genre_id' => $this->genre->id]);

    // Test ordering by name ascending
    $response = $this->get(uri: route('contents.explore', ['orderBy' => 'name_asc']));
    $response->assertStatus(200)->assertInertia(fn (Assert $page) =>
        $page->component('content/Explore')
            ->has('contents.data', 2)
            ->where('contents.data.0.id', $first->id)
            ->where('contents.data.1.id', $second->id)
    );

    // Test ordering by name descending
    $response = $this->get(uri: route('contents.explore', ['orderBy' => 'name_desc']));
    $response->assertStatus(200)->assertInertia(fn (Assert $page) =>
        $page->component('content/Explore')
            ->has('contents.data', 2)
            ->where('contents.data.0.id', $second->id)
            ->where('contents.data.1.id', $first->id)
    );
});

test('explore page searches content by title', function () {
    $this->actingAs($this->user);
    Content::factory()->create(['title' => 'title', 'genre_id' => $this->genre->id, 'type' => 'movie']);
    Content::factory()->create(['genre_id' => $this->genre->id, 'type' => 'series']);

    $response = $this->get(uri: route('contents.explore', ['searchContent' => 'tItLe']));
    $response->assertStatus(200)->assertInertia(fn (Assert $page) =>
        $page->component('content/Explore')
            ->has('contents.data', 1)
            ->where('contents.data.0.title', 'title')
    );
});
