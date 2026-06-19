<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsPublicationItem extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'settings_publication_id',
        'setting_value_id',
        'setting_value_version_id',
        'previous_version_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function publication()
    {
        return $this->belongsTo(SettingsPublication::class, 'settings_publication_id');
    }

    public function settingValue()
    {
        return $this->belongsTo(SettingValue::class);
    }

    public function version()
    {
        return $this->belongsTo(SettingValueVersion::class, 'setting_value_version_id');
    }

    public function previousVersion()
    {
        return $this->belongsTo(SettingValueVersion::class, 'previous_version_id');
    }
}
