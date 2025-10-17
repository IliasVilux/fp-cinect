<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService,
    ) {}

    /**
     * Upsert a review for a content.
     *
     * @param  Request  $request  The request containing the review data
     * @param  Content  $content  The content
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upsert(Request $request, Content $content)
    {
        $validated = $request->validate([
            'review' => 'nullable|string|max:1000',
        ]);

        $this->reviewService->upsert($content, $validated['review']);

        return back();
    }

    /**
     * Delete the given review.
     *
     * @param  Review  $review  The review to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Review $review)
    {
        $this->reviewService->destroy($review, Auth::id());

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
