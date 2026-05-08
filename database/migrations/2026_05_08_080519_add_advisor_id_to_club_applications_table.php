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
        Schema::table('club_applications', function (Blueprint $table) {
            // Check if column doesn't exist before adding to prevent errors
            if (!Schema::hasColumn('club_applications', 'advisor_id')) {
                $table->foreignId('advisor_id')
                      ->nullable()
                      ->after('user_id') // Keeps the table organized
                      ->constrained('users')
                      ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('club_applications', function (Blueprint $table) {
            // We use an array for dropForeign to drop by column name
            $table->dropForeign(['advisor_id']);
            $table->dropColumn('advisor_id');
        });
    }
};