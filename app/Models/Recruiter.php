<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruiter extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'company_name',
        'company_logo_media_id',
        'company_website',
        'css_class',
        'custom_html',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function logo()
    {
        return $this->belongsTo(MediaAsset::class, 'company_logo_media_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    protected static function booted()
    {
        static::saved(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('homepage_recruiters');
        });

        static::deleted(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('homepage_recruiters');
        });
    }
}
