<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_group_id',
        'key',
        'name',
        'description',
        'data_type',
        'input_type',
        'default_value',
        'validation_rules',
        'options',
        'is_required',
        'is_translatable',
        'is_brand_overridable',
        'is_sensitive',
        'is_public',
        'requires_publish',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'default_value' => 'array',
        'validation_rules' => 'array',
        'options' => 'array',
        'is_required' => 'boolean',
        'is_translatable' => 'boolean',
        'is_brand_overridable' => 'boolean',
        'is_sensitive' => 'boolean',
        'is_public' => 'boolean',
        'requires_publish' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(SettingGroup::class, 'setting_group_id');
    }

    public function values()
    {
        return $this->hasMany(SettingValue::class);
    }

    public function activeValues()
    {
        return $this->hasMany(SettingValue::class)
            ->whereIn('status', ['draft', 'published']);
    }

    public function auditLogs()
    {
        return $this->hasMany(
            SettingAuditLog::class,
            'setting_definition_id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeInGroup($query, string $groupCode)
    {
        return $query->whereHas('group', function ($groupQuery) use ($groupCode) {
            $groupQuery->where('code', $groupCode);
        });
    }

    public function scopeOfType($query, string $dataType)
    {
        return $query->where('data_type', $dataType);
    }
}
