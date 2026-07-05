<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttractionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'attraction_id',
        'day_of_week',
        'opening_time',
        'closing_time',
        'is_closed',
    ];

    protected function casts(): array
    {
        return [
            'opening_time' => 'datetime:H:i',
            'closing_time' => 'datetime:H:i',
            'is_closed' => 'boolean',
        ];
    }

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }
}
