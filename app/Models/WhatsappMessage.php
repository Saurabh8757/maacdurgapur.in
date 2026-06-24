<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'followup_id',
        'user_id',
        'provider',
        'direction',
        'phone',
        'message_type',
        'message',
        'provider_message_id',
        'status',
        'metadata',
        'retry_count',
        'last_error',
        'sent_at',
        'delivered_at',
        'read_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function followup()
    {
        return $this->belongsTo(LeadFollowup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
