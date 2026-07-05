<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStoryProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_chapter_id',
        'visited_chapters',
        'choices_made',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'visited_chapters' => 'array',
            'choices_made' => 'array',
            'is_completed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentChapter()
    {
        return $this->belongsTo(StoryChapter::class, 'current_chapter_id');
    }
}
