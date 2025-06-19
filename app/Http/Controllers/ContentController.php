<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Category;
use Illuminate\Http\Request;
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
            'filters' => $request->only(['orderBy', 'contentType', 'categoryId']),
        ]);
    }
    public function detail(int $id)
    {
        $content = Content::with(['category', 'seasons.episodes'])->findOrFail($id);
        $relatedContents = Content::where('category_id', $content->category_id)
            ->where('id', '!=', $content->id)
            ->where('type', $content->type)
            ->inRandomOrder()
            ->take(10)
            ->get();

        return Inertia::render('content/Detail', [
            'content' => $content,
            'relatedContents' => $relatedContents,
        ]);
    }
}
