<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_asset_id',
        'brand_id',
        'usable_type',
        'usable_id',
        'collection',
        'role',
        'locale',
        'sort_order',
        'context',
        'created_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'context' => 'array',
    ];

    public function asset()
    {
        return $this->belongsTo(MediaAsset::class, 'media_asset_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function usable()
    {
        return $this->morphTo();
    }
}
