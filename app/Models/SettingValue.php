<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_definition_id',
        'brand_id',
        'scope_key',
        'locale',
        'value',
        'status',
        'published_at',
        'published_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'value' => 'array',
        'published_at' => 'datetime',
    ];

    public function definition()
    {
        return $this->belongsTo(SettingDefinition::class, 'setting_definition_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function versions()
    {
        return $this->hasMany(SettingValueVersion::class)
            ->orderBy('version_number');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function publicationItems()
    {
        return $this->hasMany(SettingsPublicationItem::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(SettingAuditLog::class);
    }
}
