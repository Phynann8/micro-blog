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
        Schema::create('tweets', function (Blueprint $table) {
            $table->id();
            
            // 1. Foreign Key: Links this tweet to the user who created it
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // 2. The Tweet Body: A string with a strict 280-character maximum
            $table->string('body', 280);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweets');
    }
};
