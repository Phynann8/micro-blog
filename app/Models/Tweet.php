<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tweet extends Model
{
    use HasFactory;

    // Allow the body and user_id to be saved to the database
    protected $fillable = [
        'body',
        'user_id'
    ];

    // A Tweet belongs to a specific User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
