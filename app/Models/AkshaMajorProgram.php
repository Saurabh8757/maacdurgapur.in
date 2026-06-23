<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkshaMajorProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'title',
        'slug',
        'short_description',
        'outcome',
        'featured_image_media_id',
        'skills',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'skills' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function featuredImage()
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_media_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
