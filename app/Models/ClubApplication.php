<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClubApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',    // The Executive applying
        'advisor_id', // The Advisor reviewing
        'club_id',    // The Club in question
        'status',     // pending, approved, rejected
        'remarks',    // Added this in case you want to store rejection reasons
    ];

    /**
     * The Executive who submitted the application.
     * Maps to the 'user_id' foreign key.
     */
    public function executive(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The Advisor assigned to review this application.
     * Maps to the 'advisor_id' foreign key.
     */
    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    /**
     * The Club being applied for.
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    /**
     * Helper to check if the application is still pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}