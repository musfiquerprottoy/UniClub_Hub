<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('club_applications', function (Blueprint $table) {
            $table->id();
            // The Executive submitting the request
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            
            // The Advisor who needs to review it
            $table->foreignId('advisor_id')->constrained('users')->onDelete('cascade'); 
            
            // The Club being applied for
            $table->foreignId('club_id')->constrained('clubs')->onDelete('cascade');
            
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('remarks')->nullable(); // Optional: Advisor's feedback
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_applications');
    }
};