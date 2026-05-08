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
        Schema::create('club_members', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The Account
            $table->foreignId('club_id')->constrained()->onDelete('cascade'); // The Club
            
            // Form Data (Provided by student during join request)
            $table->string('full_name'); 
            $table->string('student_id');
            $table->string('mobile_no');
            $table->string('department');
            $table->string('semester');
            $table->text('address')->nullable();
            
            // Status Management
            $table->string('status')->default('pending'); // pending, active, rejected
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_members');
    }
};