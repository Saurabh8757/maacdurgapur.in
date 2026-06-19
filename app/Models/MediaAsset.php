<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MediaAsset extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'lineage_uuid',
        'version_number',
        'previous_version_id',
        'is_current',
        'brand_id',
        'media_folder_id',
        'uploaded_by',
        'storage_disk',
        'storage_key',
        'original_filename',
        'display_name',
        'extension',
        'mime_type',
        'media_type',
        'visibility',
        'security_classification',
        'status',
        'size_bytes',
        'checksum_sha256',
        'width',
        'height',
        'duration_ms',
        'page_count',
        'alt_text',
        'caption',
        'credit',
        'copyright',
        'focal_x',
        'focal_y',
        'metadata',
        'processing_error',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'version_number' => 'integer',
        'is_current' => 'boolean',
        'size_bytes' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration_ms' => 'integer',
        'page_count' => 'integer',
        'focal_x' => 'decimal:5',
        'focal_y' => 'decimal:5',
        'metadata' => 'array',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (MediaAsset $asset): void {
            if (empty($asset->uuid)) {
                $asset->uuid = (string) Str::uuid();
            }

            if (empty($asset->lineage_uuid)) {
                $asset->lineage_uuid = (string) Str::uuid();
            }
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function folder()
    {
        return $this->belongsTo(MediaFolder::class, 'media_folder_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function previousVersion()
    {
        return $this->belongsTo(MediaAsset::class, 'previous_version_id');
    }

    public function nextVersions()
    {
        return $this->hasMany(MediaAsset::class, 'previous_version_id');
    }

    public function versions()
    {
        return $this->hasMany(MediaAsset::class, 'lineage_uuid', 'lineage_uuid')
            ->orderBy('version_number');
    }

    public function variants()
    {
        return $this->hasMany(MediaVariant::class);
    }

    public function usages()
    {
        return $this->hasMany(MediaUsage::class);
    }

    public function scopeImages($query)
    {
        return $query->where('media_type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('media_type', 'video');
    }

    public function scopeDocuments($query)
    {
        return $query->where('media_type', 'document');
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopePrivate($query)
    {
        return $query->where('visibility', 'private');
    }
}
