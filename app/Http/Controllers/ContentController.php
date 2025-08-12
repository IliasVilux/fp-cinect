<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Content;
use App\Services\ContentService;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function __construct(
        private ContentService $contentService,
        private RatingService $ratingService,
    ) {}

    /**
     * Show explore page.
     *
     * @param  Request  $request  The request containing filters for content
     * @return \Inertia\Response
     */
    public function explore(Request $request)
    {
        $validated = $request->validate([
            'searchContent' => 'nullable|string|max:255',
            'contentType' => 'nullable|string',
            'categoryId' => 'nullable|integer',
            'orderBy' => 'nullable|string',
        ]);

        $categories = Category::select(['name as label', 'id as value'])->get();

        return Inertia::render('content/Explore', [
            'categoriesItems' => $categories,
            'contents' => $this->contentService->getFiltered($validated),
            'filters' => $validated,
        ]);
    }

    /**
     * Show detail page for specific content.
     *
     * @param  int  $id  The content ID
     * @return \Inertia\Response
     */
    public function detail(int $id)
    {
        $content = $this->contentService->getById($id);

        return Inertia::render('content/Detail', [
            'content' => $content,
            'relatedContents' => $this->contentService->getRelated($content),
        ]);
    }

    /**
     * Store or update rating and review for content.
     *
     * @param  Request  $request  The request containing rating and review data
     * @param  int  $id  The content ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReview(Request $request, int $id)
    {
        $validated = $request->validate([
            'rating' => 'nullable|integer|min:0|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $content = Content::findOrFail($id);

        $this->ratingService->upsertRatingAndReview($content, $validated);

        return back();
    }
}
