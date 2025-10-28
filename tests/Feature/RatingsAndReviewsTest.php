<?php

use App\Models\Content;
use App\Models\Genre;
use App\Models\Rating;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->genre = Genre::factory()->create();
    $this->content = Content::factory()->create(['genre_id' => $this->genre->id]);
});

test('user can create a rating', function () {
    $this->actingAs($this->user);
    $this->post(route('contents.storeRatingAndReview', $this->content), ['score' => 4, 'review' => ''])->assertRedirect();

    assertDatabaseHas('ratings', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'score' => 4,
    ]);
    assertDatabaseMissing('reviews', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
    ]);
    $this->get(route('contents.show', $this->content))->assertInertia(fn (Assert $page) => $page->component('content/Detail')->has('content', fn (Assert $prop) => $prop->where('user_rating.score', 4)
        ->where('ratings_avg_score', 4)
        ->where('user_review', null)
        ->etc()
    )
    );
});

test('user can create a rating and review', function () {
    $this->actingAs($this->user)
        ->post(route('contents.storeRatingAndReview', $this->content), ['score' => 4, 'review' => 'text'])->assertRedirect();

    assertDatabaseHas('ratings', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'score' => 4,
    ]);
    assertDatabaseHas('reviews', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'review_text' => 'text',
    ]);
    $this->get(route('contents.show', $this->content))->assertInertia(fn (Assert $page) => $page->component('content/Detail')->has('content', fn (Assert $prop) => $prop->where('user_rating.score', 4)
        ->where('ratings_avg_score', 4)
        ->where('user_review.review_text', 'text')
        ->etc()
    )
    );
});

test('user can\'t create a review without rating', function () {
    $this->actingAs($this->user)
        ->post(route('contents.storeRatingAndReview', $this->content), ['score' => 0, 'review' => 'text'])->assertRedirect();

    assertDatabaseMissing('ratings', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
    ]);
    assertDatabaseMissing('reviews', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
    ]);
    $this->get(route('contents.show', $this->content))->assertInertia(fn (Assert $page) => $page->component('content/Detail')->has('content', fn (Assert $prop) => $prop->where('user_rating', null)
        ->where('ratings_avg_score', null)
        ->where('user_review', null)
        ->etc()
    )
    );
});

test('review is deleted when user delete rating', function () {
    $this->actingAs($this->user)
        ->post(route('contents.storeRatingAndReview', $this->content), ['score' => 4, 'review' => 'text'])->assertRedirect();

    assertDatabaseHas('ratings', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'score' => 4,
    ]);
    assertDatabaseHas('reviews', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'review_text' => 'text',
    ]);

    $this->post(route('contents.storeRatingAndReview', $this->content), ['score' => 0, 'review' => 'text'])->assertRedirect();

    assertDatabaseMissing('ratings', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
    ]);
    assertDatabaseMissing('reviews', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
    ]);
    $this->get(route('contents.show', $this->content))->assertInertia(fn (Assert $page) => $page->component('content/Detail')->has('content', fn (Assert $prop) => $prop->where('user_rating', null)
        ->where('ratings_avg_score', null)
        ->where('user_review', null)
        ->etc()
    )
    );
});

test('rating stays when review is deleted', function () {
    $this->actingAs($this->user)
        ->post(route('contents.storeRatingAndReview', $this->content), ['score' => 4, 'review' => 'text'])->assertRedirect();

    assertDatabaseHas('ratings', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'score' => 4,
    ]);
    assertDatabaseHas('reviews', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'review_text' => 'text',
    ]);

    $this->post(route('contents.storeRatingAndReview', $this->content), ['score' => 4, 'review' => ''])->assertRedirect();

    assertDatabaseHas('ratings', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'score' => 4,
    ]);
    assertDatabaseMissing('reviews', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
    ]);
    $this->get(route('contents.show', $this->content))->assertInertia(fn (Assert $page) => $page->component('content/Detail')->has('content', fn (Assert $prop) => $prop->where('user_rating.score', 4)
        ->where('ratings_avg_score', 4)
        ->where('user_review', null)
        ->etc()
    )
    );
});

test('user can\'t create more than one review or rating for the same content', function () {
    $this->actingAs($this->user)
        ->post(route('contents.storeRatingAndReview', $this->content), ['score' => 4, 'review' => 'text'])->assertRedirect();

    $this->post(route('contents.storeRatingAndReview', $this->content), ['score' => 5, 'review' => 'text 2'])->assertRedirect();

    expect(Rating::count())->toBe(1);
    expect(Review::count())->toBe(1);
    assertDatabaseHas('ratings', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'score' => 5,
    ]);
    assertDatabaseHas('reviews', [
        'user_id' => $this->user->id,
        'content_id' => $this->content->id,
        'review_text' => 'text 2',
    ]);
    $this->get(route('contents.show', $this->content))->assertInertia(fn (Assert $page) => $page->component('content/Detail')->has('content', fn (Assert $prop) => $prop->where('user_rating.score', 5)
        ->where('ratings_avg_score', 5)
        ->where('user_review.review_text', 'text 2')
        ->etc()
    )
    );
});

test('score must be between 0 and 5', function () {
    $this->actingAs($this->user)
        ->post(route('contents.storeRatingAndReview', $this->content), ['score' => 6, 'review' => ''])
        ->assertSessionHasErrors('score');

    $this->post(route('contents.storeRatingAndReview', $this->content), ['score' => -1, 'review' => ''])
        ->assertSessionHasErrors('score');
    $this->get(route('contents.show', $this->content))->assertInertia(fn (Assert $page) => $page->component('content/Detail')->has('content', fn (Assert $prop) => $prop->where('user_rating', null)
        ->where('ratings_avg_score', null)
        ->where('user_review', null)
        ->etc()
    )
    );
});

test('review cannot exceed 1000 characters', function () {
    $longReview = str_repeat('a', 1001);

    $this->actingAs($this->user)
        ->post(route('contents.storeRatingAndReview', $this->content), ['score' => 5, 'review' => $longReview])
        ->assertSessionHasErrors('review');
    $this->get(route('contents.show', $this->content))->assertInertia(fn (Assert $page) => $page->component('content/Detail')->has('content', fn (Assert $prop) => $prop->where('user_rating', null)
        ->where('ratings_avg_score', null)
        ->where('user_review', null)
        ->etc()
    )
    );
});

test('guest cannot create rating or review', function () {
    $this->post(route('contents.storeRatingAndReview', $this->content), ['score' => 5, 'review' => 'text'])
        ->assertRedirect('/login');

    assertDatabaseMissing('ratings', ['content_id' => $this->content->id]);
    assertDatabaseMissing('reviews', ['content_id' => $this->content->id]);
    $this->get(route('login'))->assertInertia(fn (Assert $page) => $page->component('auth/Login')
    );
});
