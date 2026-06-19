<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandDomain extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'hostname',
        'scheme',
        'is_primary',
        'is_preview',
        'redirect_to_primary',
        'status',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_preview' => 'boolean',
        'redirect_to_primary' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
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
