<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    // 1. Inject Request $request here
    public function store(Request $request, User $user)
    {
        // 2. Grab the user directly from the Request object
        /** @var \App\Models\User $currentUser */
        $currentUser = $request->user();

        // A user cannot follow themselves
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // Check if the authenticated user is already following this user
        if ($currentUser->isFollowing($user)) {
            // Unfollow (detach)
            $currentUser->following()->detach($user->id);
        } else {
            // Follow (attach)
            $currentUser->following()->attach($user->id);
        }

        return back();
    }
}
