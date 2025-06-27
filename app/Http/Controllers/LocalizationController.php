<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return redirect()->back();
    }
}
