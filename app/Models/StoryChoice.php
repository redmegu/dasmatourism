<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'choice_text',
        'next_chapter_id',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }

    public function chapter()
    {
        return $this->belongsTo(StoryChapter::class);
    }

    public function nextChapter()
    {
        return $this->belongsTo(StoryChapter::class, 'next_chapter_id');
    }
}
