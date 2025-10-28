<?php

use App\Models\User;
use App\Models\Content;
use App\Models\FavoriteList;
use App\Models\Genre;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('user can create a list', function () {
    $this->actingAs($this->user);
    $this->post(route('favoriteLists.store'), [
        'name' => 'name',
        'description' => 'description',
        'is_public' => true,
    ])->assertRedirect();

    assertDatabaseHas('favorite_lists', [
        'name' => 'name',
        'description' => 'description',
        'is_public' => true,
        'user_id' => $this->user->id,
    ]);
    $response = $this->get(route('favoriteLists.index'));
    $response->assertStatus(200)->assertInertia(fn (Assert $page) =>
        $page->component('favorite-lists/myLists')
            ->has('lists', 1)
            ->where('lists.0.name', 'name')
    );
});

test('user can add content to the list', function () {
    $genre = Genre::factory()->create();
    $content = Content::factory()->create(['genre_id' => $genre->id]);
    $list = FavoriteList::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user);
    $this->post(route('favoriteLists.toggleContent', [
        'list' => $list->id,
        'content' => $content->id,
    ]))->assertRedirect();

    assertDatabaseHas('content_favorite_list', [
        'favorite_list_id' => $list->id,
        'content_id' => $content->id,
    ]);
    $response = $this->get(route('contents.show', $content->id));
    $response->assertStatus(status: 200)->assertInertia(fn (Assert $page) =>
        $page->component('content/Detail')
            ->has('favoriteLists', 1)
            ->where('favoriteLists.0.user_id', $this->user->id)
    );
    $response = $this->get(route('favoriteLists.index'));
    $response->assertStatus(status: 200)->assertInertia(fn (Assert $page) =>
        $page->component('favorite-lists/myLists')
            ->has('lists', 1)
            ->where('lists.0.contents.0.id', $content->id)
    );
});

test('user can remove content to the list', function () {
    $genre = Genre::factory()->create();
    $content = Content::factory()->create(['genre_id' => $genre->id]);
    $list = FavoriteList::factory()->create(['user_id' => $this->user->id]);
    $list->contents()->attach($content->id);

    $this->actingAs($this->user);
    $this->post(route('favoriteLists.toggleContent', [
        'list' => $list->id,
        'content' => $content->id,
    ]))->assertRedirect();

    assertDatabaseMissing('content_favorite_list', [
        'favorite_list_id' => $list->id,
        'content_id' => $content->id,
    ]);
});

test('user can update a list', function () {
    $list = FavoriteList::factory()->create(['user_id' => $this->user->id, 'is_public' => true]);

    $this->actingAs($this->user);
    $this->put(route('favoriteLists.update', $list->id), [
        'name' => 'Updated Name',
        'description' => 'Updated Description',
        'is_public' => false,
    ]);
    assertDatabaseHas('favorite_lists', [
        'name' => 'Updated Name',
        'description' => 'Updated Description',
        'is_public' => false,
        'user_id' => $this->user->id,
    ]);
});
