<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'code',
        'name',
        'legal_name',
        'slug',
        'default_locale',
        'timezone',
        'status',
        'is_primary',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Brand $brand): void {
            if (empty($brand->uuid)) {
                $brand->uuid = (string) Str::uuid();
            }
        });
    }

    public function domains()
    {
        return $this->hasMany(BrandDomain::class);
    }

    public function activeDomains()
    {
        return $this->hasMany(BrandDomain::class)
            ->where('status', 'active');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'brand_user')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function memberships()
    {
        return $this->hasMany(BrandMembership::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function settingValues()
    {
        return $this->hasMany(SettingValue::class);
    }

    public function settingsPublications()
    {
        return $this->hasMany(SettingsPublication::class);
    }

    public function mediaFolders()
    {
        return $this->hasMany(MediaFolder::class);
    }

    public function mediaAssets()
    {
        return $this->hasMany(MediaAsset::class);
    }

    public function mediaUsages()
    {
        return $this->hasMany(MediaUsage::class);
    }

    public function userRoleAssignments()
    {
        return $this->hasMany(UserRole::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
