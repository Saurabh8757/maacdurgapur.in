<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsFaqCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cms_faq_categories';

    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'status',
        'sort_order',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(CmsFaq::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeForBrand(Builder $query, ?int $brandId): Builder
    {
        if ($brandId) {
            return $query->where('brand_id', $brandId);
        }
        return $query;
    }
}
