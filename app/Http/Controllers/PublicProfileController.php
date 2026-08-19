<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        // We will pass the user to a view (which Teammate A will build in Task 2.2)
        return view('profile.public', [
            'user' => $user
        ]);
    }
}