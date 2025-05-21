<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    public function index(): Response
    {
        $categories = Category::with(['contents' => function ($query) {
            $query->latest()->limit(1);
        }])->get();

        $categories = $categories->map(function ($category) {
            $category->content = $category->contents->first();
            unset($category->contents);
            return $category;
        });

        return Inertia::render('Welcome', [
            'categories'=> $categories
        ]);
    }
}
