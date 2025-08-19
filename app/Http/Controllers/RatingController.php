<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Services\RatingService;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function __construct(
        private RatingService $ratingService,
    ) {}

    /**
     * Upsert a rating for a content.
     *
     * @param  Request  $request  The request containing the rating data
     * @param  Content  $content  The content
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upsert(Request $request, Content $content)
    {
        $validated = $request->validate([
            'rating' => 'nullable|integer|min:0|max:5',
        ]);

        $this->ratingService->upsert($content, $validated['rating']);

        return back();
    }
}
