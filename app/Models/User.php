<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function business()
    {
        return $this->hasOne(Business::class, 'owner_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ADD THIS RELATIONSHIP ↓↓↓
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }
    // ↑↑↑ END OF NEW RELATIONSHIP

    public function storyProgress()
    {
        return $this->hasOne(UserStoryProgress::class);
    }

    public function preferences()
    {
        return $this->hasOne(UserPreference::class);
    }

    public function interactions()
    {
        return $this->hasMany(UserInteraction::class);
    }

    public function landmarkSuggestions()
    {
        return $this->hasMany(LandmarkSuggestion::class);
    }

    // Role checks
    public function isAdministrator()
    {
        return $this->role === 'administrator';
    }

    public function isBusinessOwner()
    {
        return $this->role === 'business_owner';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
