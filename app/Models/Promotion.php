<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Promotion extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'promotable_type',
        'promotable_id',
        'title',
        'description',
        'image',
        'start_date',
        'end_date',
        'is_active',
        'views',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
            'views' => 'integer',
        ];
    }

    public function promotable()
    {
        return $this->morphTo();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    /**
     * Check if promotion is currently active
     */
    public function isActive()
    {
        return $this->is_active
            && $this->start_date <= now()
            && $this->end_date >= now();
    }

    /**
     * Check if promotion has expired
     */
    public function isExpired()
    {
        return $this->end_date < now();
    }

    /**
     * Check if promotion is upcoming
     */
    public function isUpcoming()
    {
        return $this->start_date > now();
    }
}
