<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FavoriteListController extends Controller
{
    /**
     * Show the user favorite lists page.
     *
     * @return Response
     */
    public function myLists()
    {
        return Inertia::render('favoriteLists/myLists', [
            'lists' => Auth::user()->favoriteLists()->get(),
        ]);
    }
}
