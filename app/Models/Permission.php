<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'domain',
        'resource',
        'action',
        'name',
        'description',
        'scope_type',
        'risk_level',
        'requires_mfa',
        'requires_reauthentication',
        'is_delegable',
        'status',
    ];

    protected $casts = [
        'requires_mfa' => 'boolean',
        'requires_reauthentication' => 'boolean',
        'is_delegable' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withPivot('granted_by', 'created_at');
    }

    public function roleGrants()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInDomain($query, string $domain)
    {
        return $query->where('domain', $domain);
    }

    public function scopeAtRiskLevel($query, string $riskLevel)
    {
        return $query->where('risk_level', $riskLevel);
    }
}
