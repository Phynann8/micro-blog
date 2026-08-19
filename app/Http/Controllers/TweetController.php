<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TweetController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming data (Must be text, max 280 chars)
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:280'],
        ]);

        // 2. Create the tweet attached to the currently logged-in user
        $request->user()->tweets()->create($validated);

        // 3. Redirect the user back to the dashboard after saving
        return back();
    }
}
