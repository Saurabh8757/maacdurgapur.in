<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadActivity;
use Illuminate\Support\Facades\Auth;

class LeadActivityService
{
    // Activity Type Constants
    public const TYPE_LEAD_CREATED = 'LEAD_CREATED';
    public const TYPE_STATUS_CHANGED = 'STATUS_CHANGED';
    public const TYPE_ASSIGNED = 'ASSIGNED';
    public const TYPE_NOTE_ADDED = 'NOTE_ADDED';
    public const TYPE_FOLLOWUP_CREATED = 'FOLLOWUP_CREATED';
    public const TYPE_FOLLOWUP_COMPLETED = 'FOLLOWUP_COMPLETED';
    public const TYPE_FOLLOWUP_MISSED = 'FOLLOWUP_MISSED';
    public const TYPE_FOLLOWUP_CANCELLED = 'FOLLOWUP_CANCELLED';
    public const TYPE_WHATSAPP_SENT = 'WHATSAPP_SENT';
    public const TYPE_WHATSAPP_DELIVERED = 'WHATSAPP_DELIVERED';
    public const TYPE_WHATSAPP_READ = 'WHATSAPP_READ';
    public const TYPE_WHATSAPP_FAILED = 'WHATSAPP_FAILED';
    public const TYPE_WHATSAPP_RECEIVED = 'WHATSAPP_RECEIVED';
    public const TYPE_EMAIL_SENT = 'EMAIL_SENT';
    public const TYPE_CALL_LOGGED = 'CALL_LOGGED';
    public const TYPE_CONVERTED = 'CONVERTED';
    public const TYPE_CLOSED = 'CLOSED';

    /**
     * Core method to log an activity for a lead.
     */
    public function logActivity(
        Lead $lead,
        string $activityType,
        string $title,
        ?string $description = null,
        array $metadata = [],
        ?int $userId = null,
        bool $isPinned = false
    ): LeadActivity {
        // Fallback to currently authenticated user if not provided
        $userId = $userId ?? Auth::id();

        return LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => $userId,
            'activity_type' => $activityType,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
            'is_pinned' => $isPinned,
        ]);
    }

    /**
     * Log when a lead is created.
     */
    public function logLeadCreated(Lead $lead): LeadActivity
    {
        return $this->logActivity(
            $lead,
            self::TYPE_LEAD_CREATED,
            'Lead Created',
            'Lead was captured/created.',
            ['source_page' => $lead->source_page]
        );
    }

    /**
     * Log when a lead's status is changed.
     */
    public function logStatusChange(Lead $lead, string $oldStatus, string $newStatus, ?int $userId = null): LeadActivity
    {
        return $this->logActivity(
            $lead,
            self::TYPE_STATUS_CHANGED,
            'Status Changed',
            "Status changed from {$oldStatus} to {$newStatus}.",
            [
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ],
            $userId
        );
    }

    /**
     * Log when a lead is assigned to a user.
     */
    public function logAssignment(Lead $lead, ?int $assignedToUserId, ?int $userId = null): LeadActivity
    {
        return $this->logActivity(
            $lead,
            self::TYPE_ASSIGNED,
            'Lead Assigned',
            "Lead was assigned to a new user.",
            [
                'assigned_to' => $assignedToUserId,
            ],
            $userId
        );
    }

    /**
     * Add a manual note to the lead's timeline.
     */
    public function addNote(Lead $lead, string $noteText, ?int $userId = null, bool $isPinned = false): LeadActivity
    {
        return $this->logActivity(
            $lead,
            self::TYPE_NOTE_ADDED,
            'Note Added',
            $noteText,
            [],
            $userId,
            $isPinned
        );
    }

    /**
     * Log conversion.
     */
    public function logConversion(Lead $lead, ?int $userId = null): LeadActivity
    {
        return $this->logActivity(
            $lead,
            self::TYPE_CONVERTED,
            'Lead Converted',
            'Lead successfully converted.',
            [],
            $userId
        );
    }

    /**
     * Log closure.
     */
    public function logClosure(Lead $lead, string $reason = '', ?int $userId = null): LeadActivity
    {
        return $this->logActivity(
            $lead,
            self::TYPE_CLOSED,
            'Lead Closed',
            "Lead closed. Reason: {$reason}",
            ['reason' => $reason],
            $userId
        );
    }

    // Future placeholders
    public function logWhatsapp(Lead $lead, string $messageId, string $status)
    {
        // To be implemented in WhatsApp Integration phase
    }

    public function logEmail(Lead $lead, string $emailId, string $status)
    {
        // To be implemented in Email Integration phase
    }

    public function logFollowup(Lead $lead, int $followupId, string $action)
    {
        // To be implemented in Followup System phase
    }
}
