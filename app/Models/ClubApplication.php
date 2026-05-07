<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubApplication extends Model
{
    protected $fillable = [
        'user_id',
        'club_id',
        'status',
    ];

    /**
     * Executive user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Requested club
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}