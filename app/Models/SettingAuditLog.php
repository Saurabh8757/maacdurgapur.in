<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LogicException;

class SettingAuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    public const UPDATED_AT = null;

    protected $fillable = [
        'uuid',
        'user_id',
        'brand_id',
        'brand_uuid',
        'setting_definition_id',
        'setting_value_id',
        'settings_publication_id',
        'scope_key',
        'locale',
        'event',
        'before_value',
        'after_value',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'before_value' => 'array',
        'after_value' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (SettingAuditLog $auditLog): void {
            if (empty($auditLog->uuid)) {
                $auditLog->uuid = (string) Str::uuid();
            }
        });

        static::updating(function (): void {
            throw new LogicException('Setting audit logs are append-only.');
        });

        static::deleting(function (): void {
            throw new LogicException('Setting audit logs are append-only.');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function definition()
    {
        return $this->belongsTo(
            SettingDefinition::class,
            'setting_definition_id'
        );
    }

    public function settingValue()
    {
        return $this->belongsTo(SettingValue::class);
    }

    public function publication()
    {
        return $this->belongsTo(
            SettingsPublication::class,
            'settings_publication_id'
        );
    }
}
