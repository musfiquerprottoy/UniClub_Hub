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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // This links the event to a specific club
            $table->foreignId('club_id')->constrained()->onDelete('cascade'); 
            
            $table->string('title');
            $table->text('description');
            $table->date('event_date');
            $table->string('location');
            
            // Every proposal starts as 'pending' until an Admin/Advisor approves it
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
