<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MediaVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'media_asset_id',
        'name',
        'storage_disk',
        'storage_key',
        'mime_type',
        'extension',
        'size_bytes',
        'width',
        'height',
        'duration_ms',
        'checksum_sha256',
        'processing_parameters',
        'status',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration_ms' => 'integer',
        'processing_parameters' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (MediaVariant $variant): void {
            if (empty($variant->uuid)) {
                $variant->uuid = (string) Str::uuid();
            }
        });
    }

    public function asset()
    {
        return $this->belongsTo(MediaAsset::class, 'media_asset_id');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }
}
