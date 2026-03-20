<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['club_id', 'title', 'description', 'event_date', 'location', 'status'];

    // This tells Laravel that an Event belongs to a Club
    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}