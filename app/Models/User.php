<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
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

    /**
     * Check if the user is an Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is an Executive
     */
    public function isExecutive(): bool
    {
        return $this->role === 'executive';
    }

    /**
     * Check if the user is an Advisor
     */
    public function isAdvisor(): bool
    {
        return $this->role === 'advisor';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

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
}