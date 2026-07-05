<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttractionImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'attraction_id',
        'image_path',
        'caption',
        'is_primary',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }
}
