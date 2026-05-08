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
     * Ensure 'advisor_id' is here so the Controller can save it!
     */
    protected $fillable = [
        'user_id',    // The ID of the Executive applicant
        'advisor_id', // The ID of the specific Advisor selected
        'club_id',    // The ID of the Club being requested
        'status',     // 'pending', 'approved', or 'rejected'
        'remarks',    // Optional field for feedback/rejection reasons
    ];

    /**
     * Relationship: The Executive who submitted the application.
     */
    public function executive(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: The Advisor assigned to review this application.
     * This is the link that makes the request show up on the Advisor's dashboard.
     */
    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    /**
     * Relationship: The Club being applied for.
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    /**
     * Helper: Clean boolean check for pending status.
     * Usage in Blade: @if($application->isPending())
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}