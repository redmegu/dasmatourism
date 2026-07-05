<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;

class Attraction extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'address',
        'latitude',
        'longitude',
        'contact_number',
        'email',
        'website',
        'google_maps_link',
        'youtube_video_url',      // ADD THIS
        'uploaded_video_path',    // ADD THIS
        'video_thumbnail',        // ADD THIS
        'entrance_fee',
        'facilities',
        'commute_guide',
        'is_historical_site',
        'status',
        'is_featured',
        'views',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'entrance_fee' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_historical_site' => 'boolean',
            'is_featured' => 'boolean',
            'views' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attraction) {
            if (empty($attraction->slug)) {
                $attraction->slug = Str::slug($attraction->name);
            }
        });
    }

    // ADD THIS NEW METHOD
    public function getYoutubeEmbedUrl()
    {
        if (!$this->youtube_video_url) {
            return null;
        }

        // Extract YouTube video ID
        preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->youtube_video_url, $matches);

        return isset($matches[1]) ? 'https://www.youtube.com/embed/' . $matches[1] : null;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(AttractionImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(AttractionImage::class)->where('is_primary', true);
    }

    public function schedules()
    {
        return $this->hasMany(AttractionSchedule::class);
    }

    public function marker()
    {
        return $this->hasOne(MapMarker::class);
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeHistoricalSites($query)
    {
        return $query->where('is_historical_site', true);
    }
}
