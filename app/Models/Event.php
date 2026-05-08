<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'club_id', 
        'created_by', // Required for Executive tracking
        'title', 
        'description', 
        'event_date', 
        'location', 
        'status',
        'venue_id',   
        'start_time', 
        'end_time',
        'image'       // Added so banners save correctly
    ];

    /**
     * Relationship: An Event belongs to a Club.
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Relationship: Track which User (Executive) created the proposal.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: An Event belongs to a Venue (if applicable).
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * Accessor: Get professional status badge details.
     * Note: We use 'pending_advisor' and 'pending_admin' to match our workflow.
     */
    public function getStatusDetailsAttribute(): array
    {
        return match($this->status) {
            'pending_advisor' => [
                'label' => 'Awaiting Advisor',
                'color' => 'bg-amber-100 text-amber-700 border-amber-200',
                'dot'   => '🟡'
            ],
            'pending_admin' => [
                'label' => 'Awaiting Admin',
                'color' => 'bg-blue-100 text-blue-700 border-blue-200',
                'dot'   => '🔵'
            ],
            'approved' => [
                'label' => 'Live / Confirmed',
                'color' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'dot'   => '🟢'
            ],
            'rejected' => [
                'label' => 'Rejected',
                'color' => 'bg-red-100 text-red-700 border-red-200',
                'dot'   => '🔴'
            ],
            default => [
                'label' => 'Draft',
                'color' => 'bg-gray-100 text-gray-700',
                'dot'   => '⚪'
            ],
        };
    }

    /**
     * Helper to check if event is approved and safe to show to students.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}