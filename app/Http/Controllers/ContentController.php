<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Content;
use App\Services\ContentService;
use App\Services\FavoriteListService;
use App\Services\RatingService;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function __construct(
        private RatingService $ratingService,
        private ReviewService $reviewService,
        private ContentService $contentService,
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
            'genreId' => 'nullable|integer',
            'orderBy' => 'nullable|string',
        ]);

        $genres = Genre::select(['name as label', 'id as value'])->get();

        return Inertia::render('content/Explore', [
            'genresItems' => $genres,
            'contents' => $this->contentService->getFiltered($validated),
            'filters' => $validated,
        ]);
    }

    /**
     * Show detail page for specific content.
     *
     * @param  FavoriteListService  $favoriteListService
     * @param  int  $id  The content ID
     * @return \Inertia\Response
     */
    public function show(FavoriteListService $favoriteListService, int $id)
    {
        $content = $this->contentService->getById($id);

        return Inertia::render('content/Detail', [
            'content' => $content,
            'relatedContents' => $this->contentService->getRelated($content),
            'favoriteLists' => $favoriteListService->getUserListsWithContent($content),
        ]);
    }

    /**
     * Store rating and review for a content.
     *
     * @param  Request  $request  The request containing the rating and review data
     * @param  Content  $content  The content
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeRatingAndReview(Request $request, Content $content)
    {
        $validated = $request->validate([ 'score' => 'integer|min:0|max:5', 'review' => 'nullable|string|max:1000', ]);

        $this->ratingService->upsert($content, $validated['score']);
        if ($validated['score'] > 0) {
            $this->reviewService->upsert($content, $validated['review']);
        }

        return back();
    }
}
