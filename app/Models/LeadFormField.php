<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'field_name', 'label', 'type', 'options', 
        'placeholder', 'is_required', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
