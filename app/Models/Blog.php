<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_media_id',
        'author',
        'reading_time',
        'tags',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_title',
        'og_description',
        'is_featured',
        'featured_order',
        'status',
        'published_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function featuredImage()
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_media_id');
    }
}
