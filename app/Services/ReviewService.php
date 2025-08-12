<?php

namespace App\Services;

use App\Models\Review;

class ReviewService
{
    /**
     * Delete a review.
     *
     * @param  Review  $review  The review to delete
     * @param  int  $userId  The ID of the user attempting to delete the review
     */
    public function delete(Review $review, int $userId): bool
    {
        return $review->delete();
    }
}
