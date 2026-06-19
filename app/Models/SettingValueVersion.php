<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingValueVersion extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'setting_value_id',
        'version_number',
        'value',
        'status',
        'change_summary',
        'created_by',
    ];

    protected $casts = [
        'value' => 'array',
        'version_number' => 'integer',
        'created_at' => 'datetime',
    ];

    public function settingValue()
    {
        return $this->belongsTo(SettingValue::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function publicationItems()
    {
        return $this->hasMany(SettingsPublicationItem::class, 'setting_value_version_id');
    }

    public function previousForPublicationItems()
    {
        return $this->hasMany(SettingsPublicationItem::class, 'previous_version_id');
    }
}
