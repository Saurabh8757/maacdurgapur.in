<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'assigned_user_id',
        'followup_date',
        'followup_time',
        'remarks',
        'status',
        'created_by',
        'completed_at',
        'send_whatsapp_reminder',
    ];

    protected $casts = [
        'followup_date' => 'date',
        'completed_at' => 'datetime',
        'send_whatsapp_reminder' => 'boolean',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function whatsappMessages()
    {
        return $this->hasMany(WhatsappMessage::class, 'followup_id');
    }
}
