<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalizationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|in:en,es',
        ]);

        session(['locale' => $validated['locale']]);

        if (Auth::check()) {
            Auth::user()->update(['locale' => $validated['locale']]);
        }

        return redirect()->back();
    }
}
