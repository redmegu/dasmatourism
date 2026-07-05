<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;

class Business extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'business_category_id',
        'owner_id',
        'name',
        'slug',
        'description',
        'address',
        'contact_number',
        'email',
        'website',
        'google_maps_link',
        'services',
        'operating_hours',
        'logo',
        'business_permit',
        'permit_verified_at',  // MAKE SURE THIS IS HERE!
        'status',
        'is_verified',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'services' => 'array',
            'is_verified' => 'boolean',
            'views' => 'integer',
            'permit_verified_at' => 'datetime',  // MAKE SURE THIS IS HERE TOO!
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($business) {
            if (empty($business->slug)) {
                $business->slug = Str::slug($business->name);
            }
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function images()
    {
        return $this->hasMany(BusinessImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(BusinessImage::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function approvedReviews()
    {
        return $this->morphMany(Review::class, 'reviewable')->where('status', 'approved');
    }

    public function promotions()
    {
        return $this->morphMany(Promotion::class, 'promotable');
    }

    public function interactions()
    {
        return $this->morphMany(UserInteraction::class, 'interactable');
    }

    public function bookmarks()
    {
        return $this->morphMany(Bookmark::class, 'bookmarkable');
    }

    // Helper methods
    public function getAverageRating()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getTotalReviews()
    {
        return $this->approvedReviews()->count();
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function hasVerifiedPermit()
    {
        return !is_null($this->permit_verified_at);
    }
}
