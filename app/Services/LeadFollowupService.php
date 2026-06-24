<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadFollowup;
use Illuminate\Support\Facades\Auth;

class LeadFollowupService
{
    protected $activityService;
    protected $notificationService;

    public function __construct(LeadActivityService $activityService, NotificationService $notificationService)
    {
        $this->activityService = $activityService;
        $this->notificationService = $notificationService;
    }

    public function createFollowup(Lead $lead, $followupDate, $followupTime, $remarks, $assignedUserId = null, $createdBy = null, $isWhatsAppReminder = false)
    {
        $createdBy = $createdBy ?? Auth::id();

        $followup = LeadFollowup::create([
            'lead_id' => $lead->id,
            'assigned_user_id' => $assignedUserId,
            'followup_date' => $followupDate,
            'followup_time' => $followupTime,
            'remarks' => $remarks,
            'status' => 'pending',
            'created_by' => $createdBy,
            'send_whatsapp_reminder' => $isWhatsAppReminder,
        ]);

        $this->activityService->logActivity(
            $lead,
            LeadActivityService::TYPE_FOLLOWUP_CREATED,
            'Followup Created',
            "Scheduled for {$followupDate}" . ($followupTime ? " at {$followupTime}" : "") . " - {$remarks}",
            ['followup_id' => $followup->id, 'assigned_to' => $assignedUserId],
            $createdBy
        );

        if ($assignedUserId && $assignedUserId != $createdBy) {
            $this->notificationService->sendToUser(
                $assignedUserId,
                'Followup Assigned',
                "You have been assigned a followup for lead: {$lead->name}",
                'warning',
                'Followup',
                $followup->id,
                route('admin::leads.show', $lead->id),
                'fas fa-calendar-plus',
                'warning'
            );
        }

        if ($isWhatsAppReminder) {
            $whatsappService = app(\App\Services\WhatsApp\WhatsappServiceInterface::class);
            $whatsappService->sendFollowupReminder($followup);
        }

        return $followup;
    }

    public function completeFollowup(LeadFollowup $followup, $remarks = null, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        $followup->status = 'completed';
        $followup->completed_at = now();
        if ($remarks) {
            $followup->remarks = $followup->remarks . "\n[Completed: {$remarks}]";
        }
        $followup->save();

        $this->activityService->logActivity(
            $followup->lead,
            LeadActivityService::TYPE_FOLLOWUP_COMPLETED,
            'Followup Completed',
            $remarks ?? 'Followup marked as completed.',
            ['followup_id' => $followup->id],
            $userId
        );

        return $followup;
    }

    public function cancelFollowup(LeadFollowup $followup, $reason = null, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        $followup->status = 'cancelled';
        if ($reason) {
            $followup->remarks = $followup->remarks . "\n[Cancelled: {$reason}]";
        }
        $followup->save();

        $this->activityService->logActivity(
            $followup->lead,
            LeadActivityService::TYPE_FOLLOWUP_CANCELLED ?? 'FOLLOWUP_CANCELLED',
            'Followup Cancelled',
            $reason ?? 'Followup cancelled.',
            ['followup_id' => $followup->id],
            $userId
        );

        return $followup;
    }

    public function markMissed(LeadFollowup $followup)
    {
        $followup->status = 'missed';
        $followup->save();

        $this->activityService->logActivity(
            $followup->lead,
            LeadActivityService::TYPE_FOLLOWUP_MISSED ?? 'FOLLOWUP_MISSED',
            'Followup Missed',
            'Followup was not completed on time.',
            ['followup_id' => $followup->id],
            null // System action
        );

        if ($followup->assigned_user_id) {
            $this->notificationService->sendToUser(
                $followup->assigned_user_id,
                'Followup Overdue',
                "You have a missed followup for lead: {$followup->lead->name}",
                'danger',
                'Followup',
                $followup->id,
                route('admin::leads.show', $followup->lead->id),
                'fas fa-calendar-times',
                'danger'
            );
        }

        return $followup;
    }

    public function getTodaysFollowups($userId = null)
    {
        $query = LeadFollowup::with('lead')
            ->where('followup_date', date('Y-m-d'))
            ->where('status', 'pending');
            
        if ($userId) {
            $query->where('assigned_user_id', $userId);
        }
        
        return $query->orderBy('followup_time', 'asc')->get();
    }

    public function getPendingFollowups($userId = null)
    {
        $query = LeadFollowup::with('lead')
            ->where('status', 'pending');
            
        if ($userId) {
            $query->where('assigned_user_id', $userId);
        }
        
        return $query->orderBy('followup_date', 'asc')->orderBy('followup_time', 'asc')->get();
    }
}
