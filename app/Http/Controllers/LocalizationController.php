<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocalizationController extends Controller
{
    /**
     * Handle the localization update request.
     *
     * Validates the 'locale' input, updates session and user preference (if logged in),
     * then redirects back to the previous page.
     *
     *
     * @return \Illuminate\Http\RedirectResponse
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
