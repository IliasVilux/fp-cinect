<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function explore()
    {
        $contents = Content::paginate(2);

        return Inertia::render('content/Explore', [
            'contents' => $contents,
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
