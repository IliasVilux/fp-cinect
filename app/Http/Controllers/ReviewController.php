<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService,
    ) {}

    /**
     * Delete the given review.
     *
     * @param  Review  $review  The review to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Review $review)
    {
        $this->reviewService->delete($review, Auth::id());

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
