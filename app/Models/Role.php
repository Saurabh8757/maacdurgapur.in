<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'parent_role_id',
        'code',
        'name',
        'description',
        'scope_type',
        'risk_level',
        'is_system',
        'is_assignable',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_assignable' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Role $role): void {
            if (empty($role->uuid)) {
                $role->uuid = (string) Str::uuid();
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Role::class, 'parent_role_id');
    }

    public function children()
    {
        return $this->hasMany(Role::class, 'parent_role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withPivot('granted_by', 'created_at');
    }

    public function permissionGrants()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function userAssignments()
    {
        return $this->hasMany(UserRole::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
            ->withPivot([
                'uuid',
                'brand_id',
                'scope_key',
                'status',
                'starts_at',
                'expires_at',
            ]);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeGlobal($query)
    {
        return $query->where('scope_type', 'global');
    }

    public function scopeBrand($query)
    {
        return $query->where('scope_type', 'brand');
    }
}
