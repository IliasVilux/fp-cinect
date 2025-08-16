<?php

namespace App\Http\Controllers;

use App\Models\FavoriteList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class FavoriteListController extends Controller
{
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
        return back();
    }

    /**
     * Update an existing favorite list.
     *
     * @param  Request  $request  The request containing the updated favorite list data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        return back();
    }

    /**
     * Delete a favorite list.
     *
     * @param  FavoriteList  $favoriteList  The favorite list to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(FavoriteList $favoriteList)
    {
        return back();
    }
}
