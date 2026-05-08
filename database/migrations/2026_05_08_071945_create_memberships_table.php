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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            // Links the membership to a user
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Links the membership to a club
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            
            // Stores the membership status
            $table->string('status')->default('pending'); // e.g., 'pending', 'accepted'
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};