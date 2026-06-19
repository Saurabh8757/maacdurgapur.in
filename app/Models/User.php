<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_user')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function brandMemberships()
    {
        return $this->hasMany(BrandMembership::class);
    }

    public function defaultBrand()
    {
        return $this->belongsToMany(Brand::class, 'brand_user')
            ->wherePivot('is_default', true)
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function createdBrands()
    {
        return $this->hasMany(Brand::class, 'created_by');
    }

    public function updatedBrands()
    {
        return $this->hasMany(Brand::class, 'updated_by');
    }

    public function createdSettingValues()
    {
        return $this->hasMany(SettingValue::class, 'created_by');
    }

    public function updatedSettingValues()
    {
        return $this->hasMany(SettingValue::class, 'updated_by');
    }

    public function publishedSettingValues()
    {
        return $this->hasMany(SettingValue::class, 'published_by');
    }

    public function createdSettingValueVersions()
    {
        return $this->hasMany(SettingValueVersion::class, 'created_by');
    }

    public function settingsPublications()
    {
        return $this->hasMany(SettingsPublication::class, 'published_by');
    }

    public function settingsRollbacks()
    {
        return $this->hasMany(SettingsPublication::class, 'rolled_back_by');
    }

    public function settingAuditLogs()
    {
        return $this->hasMany(SettingAuditLog::class);
    }

    public function uploadedMediaAssets()
    {
        return $this->hasMany(MediaAsset::class, 'uploaded_by');
    }

    public function createdMediaFolders()
    {
        return $this->hasMany(MediaFolder::class, 'created_by');
    }

    public function createdMediaUsages()
    {
        return $this->hasMany(MediaUsage::class, 'created_by');
    }

    public function roleAssignments()
    {
        return $this->hasMany(UserRole::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot([
                'uuid',
                'brand_id',
                'scope_key',
                'status',
                'starts_at',
                'expires_at',
            ]);
    }

    public function globalRoleAssignments()
    {
        return $this->hasMany(UserRole::class)
            ->where('scope_key', 'global');
    }

    public function brandRoleAssignments()
    {
        return $this->hasMany(UserRole::class)
            ->whereNotNull('brand_id');
    }

    public function assignedRoleGrants()
    {
        return $this->hasMany(UserRole::class, 'assigned_by');
    }

    public function revokedRoleGrants()
    {
        return $this->hasMany(UserRole::class, 'revoked_by');
    }
}
