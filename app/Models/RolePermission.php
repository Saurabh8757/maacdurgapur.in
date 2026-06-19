<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'role_id',
        'permission_id',
        'granted_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function grantingUser()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }
}
