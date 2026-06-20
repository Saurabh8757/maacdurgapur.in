<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsFeature extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'title',
        'slug',
        'description',
        'icon_media_id',
        'status',
        'sort_order'
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function icon(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'icon_media_id');
    }
}
