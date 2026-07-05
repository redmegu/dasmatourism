<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
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

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
