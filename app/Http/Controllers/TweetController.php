<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    //Show Tweets to feeds
    public function index()
    {
        // Fetch tweets, eager-load the user, and order newest first
        $tweets = Tweet::with('user')->latest()->get();

        // Pass the data to the dashboard view
        return view('dashboard', [
            'tweets' => $tweets
        ]);
    }
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
