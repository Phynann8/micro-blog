<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\TweetController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [TweetController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
// allows people to navigate to a user's profile using ID:
Route::get('/user/{user}', [PublicProfileController::class, 'show'])->name('profile.show');
Route::post('/tweets', [TweetController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('tweets.store');
