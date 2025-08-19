<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Rating;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class RatingService
{
    /**
     * Store, update or delete a rating and review for the given content.
     *
     * If rating is 0, deletes existing rating and review.
     *
     * @param  Content  $content  The content to rate
     * @param  array{rating:int, review:string}  $data
     */
    public function upsert(Content $content, int $rating): void
    {
        $userId = Auth::id();

        if ($rating === 0) {
            Rating::where('user_id', $userId)
                ->where('content_id', $content->getKey())
                ->delete();

            Review::where('user_id', $userId)
                ->where('content_id', $content->getKey())
                ->delete();

            return;
        }

        Rating::updateOrCreate(
            ['user_id' => $userId, 'content_id' => $content->getKey()],
            ['rating' => $rating]
        );
    }
}
