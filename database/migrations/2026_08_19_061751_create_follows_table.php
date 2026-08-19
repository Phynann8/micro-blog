<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            
            // The ID of the person who clicked the "Follow" button
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            
            // The ID of the person they are following
            $table->foreignId('following_id')->constrained('users')->cascadeOnDelete();
            
            $table->timestamps();

            // Prevent duplicate follows (a user can't follow the same person twice)
            $table->unique(['follower_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
