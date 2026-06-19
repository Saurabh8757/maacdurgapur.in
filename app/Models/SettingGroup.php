<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'description',
        'icon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(SettingGroup::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SettingGroup::class, 'parent_id')
            ->orderBy('sort_order');
    }

    public function definitions()
    {
        return $this->hasMany(SettingDefinition::class)
            ->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
