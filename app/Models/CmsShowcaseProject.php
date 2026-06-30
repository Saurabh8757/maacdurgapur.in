<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsShowcaseProject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'cms_showcase_category_id',
        'title',
        'slug',
        'student_name',
        'short_description',
        'thumbnail_media_id',
        'thumbnail_media_id_2',
        'thumbnail_media_id_3',
        'thumbnail_media_id_4',
        'thumbnail_media_id_5',
        'software_icon_media_id',
        'video_url',
        'status',
        'sort_order'
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CmsShowcaseCategory::class, 'cms_showcase_category_id');
    }

    public function thumbnail(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'thumbnail_media_id');
    }

    public function thumbnail2(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'thumbnail_media_id_2');
    }

    public function thumbnail3(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'thumbnail_media_id_3');
    }

    public function thumbnail4(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'thumbnail_media_id_4');
    }

    public function thumbnail5(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'thumbnail_media_id_5');
    }

    public function softwareIcon(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'software_icon_media_id');
    }
}
