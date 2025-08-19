<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    /**
     * Store or update a review for the given content.
     *
     * If review is empty, deletes existing review.
     *
     * @param  Content  $content  The content to review
     * @param  string  $review  The review text
     */
    public function upsert(Content $content, ?string $review)
    {
        $userId = Auth::id();

        if (!empty($review)) {
            Review::updateOrCreate(
                ['user_id' => $userId, 'content_id' => $content->getKey()],
                ['review_text' => $review]
            );
        } else {
            Review::where('user_id', $userId)
                ->where('content_id', $content->getKey())
                ->delete();
        }

        return;
    }


    /**
     * Delete a review.
     *
     * @param  Review  $review  The review to delete
     * @param  int  $userId  The ID of the user attempting to delete the review
     */
    public function destroy(Review $review, int $userId): bool
    {
        return $review->delete();
    }
}
