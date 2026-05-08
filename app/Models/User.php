<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helper Functions
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isExecutive(): bool { return $this->role === 'executive'; }
    public function isAdvisor(): bool { return $this->role === 'advisor'; }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * THIS WAS MISSING: The relationship causing your dashboard error.
     */
    public function memberships(): HasMany
    {
        // This tells Laravel that a User can have many records in the memberships table
        return $this->hasMany(Membership::class);
    }

    /**
     * The clubs this user has applied to manage.
     */
    public function clubApplications(): HasMany
    {
        return $this->hasMany(ClubApplication::class);
    }

    /**
     * The clubs this user actually manages (Approved).
     */
    public function managedClubs(): HasMany
    {
        return $this->hasMany(Club::class);
    }

    /**
     * Events created by the user.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }
}