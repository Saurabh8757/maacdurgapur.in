<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementShowcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id',
        'company_id',
        'student_name',
        'company_name',
        'designation',
        'annual_package',
        'student_image_media_id',
        'company_logo_media_id',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function studentImage()
    {
        return $this->belongsTo(MediaAsset::class, 'student_image_media_id');
    }

    public function companyLogo()
    {
        return $this->belongsTo(MediaAsset::class, 'company_logo_media_id');
    }
}
