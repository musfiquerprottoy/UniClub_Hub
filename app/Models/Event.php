<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // Added the new columns to the fillable array so they can be saved!
    protected $fillable = [
        'club_id', 
        'title', 
        'description', 
        'event_date', 
        'location', 
        'status',
        'venue_id',   // <-- Added
        'start_time', // <-- Added
        'end_time'    // <-- Added
    ];

    // This tells Laravel that an Event belongs to a Club
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    // This tells Laravel that an Event belongs to a Venue
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}