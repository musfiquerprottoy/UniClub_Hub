<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Including both user_id and student_id to satisfy NOT NULL constraints.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'student_id',
        'club_id',
        'mobile_no',
        'department',
        'semester',
        'address',
        'status',
    ];

    /**
     * Get the user (student) that applied for membership.
     * * We map this to 'student_id' as the primary foreign key, 
     * though 'user_id' contains the same value in your schema.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the club the membership belongs to.
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Scope a query to only include pending requests.
     * Usage: ClubMember::pending()->get();
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include accepted members.
     * Usage: ClubMember::accepted()->get();
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Helper to check if the member is currently active/approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'accepted';
    }
}