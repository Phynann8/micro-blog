<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    //Show Tweets to feeds
    public function index()
    {

        // 1. Get the IDs of the users the currently logged-in user is following
        $followingIds = auth()->user()->following()->pluck('users.id');

        // 2. Add the logged-in user's OWN ID to the list so they see their own tweets
        $followingIds->push(auth()->id());

        // 3. Fetch tweets ONLY from those specific IDs, eager-load the user, and order by newest
        $tweets = Tweet::whereIn('user_id', $followingIds)
            ->with('user')
            ->latest()
            ->get();

        // 4. Pass the filtered data to the dashboard view
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
