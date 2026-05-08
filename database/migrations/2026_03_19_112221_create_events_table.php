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
            // Links to the club that owns the event
            $table->foreignId('club_id')->constrained()->onDelete('cascade'); 
            
            $table->string('title');
            $table->text('description');
            $table->date('event_date');
            $table->string('location');
            
            /**
             * Workflow Statuses:
             * pending_advisor: Executive created it. Waiting for Advisor.
             * pending_admin:   Advisor approved it. Waiting for Admin.
             * approved:        Admin approved it. Now visible to students.
             * rejected:        Denied by either Advisor or Admin.
             */
            $table->enum('status', [
                'pending_advisor', 
                'pending_admin', 
                'approved', 
                'rejected'
            ])->default('pending_advisor');

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