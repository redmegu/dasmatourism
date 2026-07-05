<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapMarker extends Model
{
    use HasFactory;

    protected $fillable = [
        'attraction_id',
        'latitude',
        'longitude',
        'marker_icon',
        'marker_color',
        'zoom_level',
        'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'zoom_level' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }
}
