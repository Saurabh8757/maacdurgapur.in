<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SettingsPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'brand_id',
        'scope_key',
        'locale',
        'status',
        'change_summary',
        'published_by',
        'published_at',
        'rolled_back_by',
        'rolled_back_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'rolled_back_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (SettingsPublication $publication): void {
            if (empty($publication->uuid)) {
                $publication->uuid = (string) Str::uuid();
            }
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function rollbackActor()
    {
        return $this->belongsTo(User::class, 'rolled_back_by');
    }

    public function items()
    {
        return $this->hasMany(SettingsPublicationItem::class);
    }
}
