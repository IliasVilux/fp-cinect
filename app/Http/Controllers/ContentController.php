<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContentController extends Controller
{
    public function show(int $id)
    {
        $content = Content::findOrFail($id);
        return Inertia::render('content/Detail', [
            'content' => $content,
        ]);
    }
}
