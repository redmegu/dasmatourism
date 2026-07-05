<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Announcement extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'content',
        'image',
        'status',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
