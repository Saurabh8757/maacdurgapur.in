<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'title',
        'slug',
        'description',
        'tools_covered',
        'thumbnail_media_id',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'tools_covered' => 'array',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'thumbnail_media_id');
    }
}
