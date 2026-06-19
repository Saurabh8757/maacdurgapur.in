<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'role_id',
        'brand_id',
        'scope_key',
        'status',
        'starts_at',
        'expires_at',
        'assigned_by',
        'reason',
        'revoked_by',
        'revoked_at',
        'revocation_reason',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (UserRole $assignment): void {
            if (empty($assignment->uuid)) {
                $assignment->uuid = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function revoker()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    public function scopeActive($query)
    {
        return $query
            ->where('status', 'active')
            ->where(function ($activeQuery) {
                $activeQuery->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($activeQuery) {
                $activeQuery->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeCurrent($query)
    {
        return $query->active();
    }
}
