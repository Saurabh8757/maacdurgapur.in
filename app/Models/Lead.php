<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'name', 'phone', 'email', 'course_name', 'location', 
        'message', 'source_page', 'source_url', 'status', 'assigned_to', 'custom_data',
        'legacy_source', 'legacy_source_id'
    ];

    protected $casts = [
        'custom_data' => 'array',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class)->orderBy('created_at', 'desc');
    }

    public function followups()
    {
        return $this->hasMany(LeadFollowup::class)->orderBy('followup_date', 'asc')->orderBy('followup_time', 'asc');
    }

    public function whatsappMessages()
    {
        return $this->hasMany(WhatsappMessage::class);
    }
}
