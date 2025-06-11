<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function show(int $id)
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
