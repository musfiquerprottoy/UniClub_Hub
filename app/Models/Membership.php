<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membership extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'club_id',
        'status',
    ];

    /**
     * Relationship: A membership belongs to a user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: A membership belongs to a club.
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}