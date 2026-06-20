<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsFaq extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cms_faqs';

    protected $fillable = [
        'cms_faq_category_id',
        'brand_id',
        'question',
        'answer',
        'status',
        'sort_order',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CmsFaqCategory::class, 'cms_faq_category_id');
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
