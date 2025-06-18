<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function explore(Request $request)
    {
        $query = Content::query();

        if ($request->filled('contentType')) {
            $query->where('type', $request->input('contentType'));
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

        return Inertia::render('content/Explore', [
            'contents' => $contents,
            'filters' => $request->only(['orderBy', 'contentType']),
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
