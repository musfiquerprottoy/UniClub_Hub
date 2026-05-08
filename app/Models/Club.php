<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Club extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'creation_date',
        'category',
        'logo',
        'user_id'
    ];

    /**
     * The Executive who manages this club.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Applications submitted for this club (Executive management apps).
     */
    public function applications(): HasMany
    {
        return $this->hasMany(ClubApplication::class);
    }

    /**
     * Events hosted by this club.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * All membership records (Students applying or joined).
     */
    public function members(): HasMany
    {
        return $this->hasMany(ClubMember::class);
    }

    /**
     * Helper relationship to get only accepted members.
     * Useful for eager loading: $club->load('acceptedMembers')
     */
    public function acceptedMembers(): HasMany
    {
        return $this->hasMany(ClubMember::class)->where('status', 'accepted');
    }

    /**
     * Dynamic attribute to get the count of accepted members.
     * Logic: Starts at 0, increases by 1 for each 'accepted' record, 
     * decreases if a record is deleted.
     * * Accessible in Blade as: {{ $club->accepted_members_count }}
     */
    public function getAcceptedMembersCountAttribute(): int
    {
        return $this->members()->where('status', 'accepted')->count();
    }

    /**
     * Get logo URL or fallback placeholder.
     * Accessible in Blade as: {{ $club->logo_url }}
     */
    public function getLogoUrlAttribute(): string
    {
        if (
            $this->logo &&
            Storage::disk('public')->exists($this->logo)
        ) {
            return asset('storage/' . $this->logo);
        }

        // Professional placeholder using the club's name
        return 'https://ui-avatars.com/api/?name=' .
            urlencode($this->name) .
            '&background=312E81&color=FFFFFF&size=256';
    }
}