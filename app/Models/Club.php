<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'description',
    'creation_date',
    'category',
    'logo',
    'user_id'
])]
class Club extends Model
{
    /**
     * The Executive who manages this club
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Applications submitted for this club
     */
    public function applications(): HasMany
    {
        return $this->hasMany(ClubApplication::class);
    }

    /**
     * Events hosted by this club
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get logo URL or fallback placeholder
     */
    public function getLogoUrlAttribute(): string
    {
        if (
            $this->logo &&
            Storage::disk('public')->exists($this->logo)
        ) {
            return asset('storage/' . $this->logo);
        }

        return 'https://ui-avatars.com/api/?name=' .
            urlencode($this->name) .
            '&background=312E81&color=FFFFFF&size=256';
    }
}