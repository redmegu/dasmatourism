<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'chapter_number',
        'content',
        'background_image',
        'visual_images',
        'character_models',
        'attraction_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'chapter_number' => 'integer',
            'is_active' => 'boolean',
            'visual_images' => 'array',
            'character_models' => 'array',
        ];
    }

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }

    public function choices()
    {
        return $this->hasMany(StoryChoice::class, 'chapter_id')->orderBy('order');
    }
}
