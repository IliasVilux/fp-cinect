<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\FavoriteList;
use App\Services\FavoriteListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FavoriteListController extends Controller
{
    public function __construct(
        private FavoriteListService $favoriteListService,
    ) {}

    /**
     * Show the user favorite lists page.
     *
     * @return Response
     */
    public function show()
    {
        return Inertia::render('favorite-lists/myLists', [
            'lists' => Auth::user()->favoriteLists()->with(['contents'])->get(),
        ]);
    }

    /**
     * Store a new favorite list.
     *
     * @param  Request  $request  The request containing the favorite list data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:0|max:30',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $this->favoriteListService->create($validated);

        return back();
    }

    /**
     * Update an existing favorite list.
     *
     * @param  Request  $request  The request containing the updated favorite list data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, FavoriteList $list)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:0|max:30',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
        ]);

        $this->favoriteListService->update($list, $validated);

        return back();
    }

    /**
     * Delete a favorite list.
     *
     * @param  FavoriteList  $favoriteList  The favorite list to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(FavoriteList $list)
    {
        $this->favoriteListService->delete($list);

        return back();
    }

    /**
     * Toggle content in a favorite list.
     *
     * @param  FavoriteList  $list  The favorite list to toggle content in
     * @param  Content  $content  The content to toggle
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleContent(FavoriteList $list, Content $content)
    {
        $this->favoriteListService->toggleContent($list, $content);

        return back();
    }
}
