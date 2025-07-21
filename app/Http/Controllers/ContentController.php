<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Category;
use App\Models\Rating;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function explore(Request $request)
    {
        $validated = $request->validate([
            'searchContent' => 'nullable|string|max:255',
        ]);

        $query = Content::query();

        if ($request->filled('contentType')) {
            $query->where('type', $request->input('contentType'));
        }

        if ($request->filled('categoryId')) {
            $query->where('category_id', $request->input('categoryId'));
        }

        if ($request->filled('searchContent')) {
            $query->where('title', 'like', '%' . $request->input('searchContent') . '%');
        }

        switch ($request->input('orderBy')) {
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'recent':
                $query->orderBy('created_at', 'desc');
                break;
            case 'top_rated':
                $query->orderBy('rating', 'desc'); // ! TODO: when ratings are implemented
                break;
            case 'low_rated':
                $query->orderBy('rating', 'asc'); // ! TODO: when ratings are implemented
                break;
            default:
                $query->latest();
        }

        $contents = $query->paginate(24);
        $categories = Category::select(['name as label', 'id as value'])->get();

        return Inertia::render('content/Explore', [
            'categoriesItems' => $categories,
            'contents' => $contents,
            'filters' => $request->only(['orderBy', 'contentType', 'categoryId', 'searchContent']),
        ]);
    }
    public function detail(int $id)
    {
        $content = Content::with(['category', 'seasons.episodes', 'userRating', 'userReview'])->withAvg('ratings', 'rating')->findOrFail($id);

        $relatedContents = Content::where('category_id', $content->category_id)
            ->where('id', '!=', $content->getKey())
            ->where('type', $content->type)
            ->inRandomOrder()
            ->take(10)
            ->get();

        return Inertia::render('content/Detail', [
            'content' => $content,
            'relatedContents' => $relatedContents,
        ]);
    }

    public function storeReview(Request $request, int $id)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $content = Content::findOrFail($id);
        $userId = Auth::id();

        Rating::updateOrCreate(
            ['user_id' => $userId, 'content_id' => $content->getKey()],
            ['rating' => $validated['rating']]
        );

        if (!empty($validated['review'])) {
            Review::updateOrCreate(
                ['user_id' => $userId, 'content_id' => $content->getKey()],
                ['review_text' => $validated['review']]
            );
        } else {
            Review::where('user_id', $userId)->where('content_id', $content->getKey())->delete();
        }

        return back();
    }
}
