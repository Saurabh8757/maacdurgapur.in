<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a new notification record in the database.
     * All properties are passed in via an array.
     */
    public function create(array $data)
    {
        try {
            $data['created_by'] = Auth::id() ?? null;
            return Notification::create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send a notification to a specific user.
     */
    public function sendToUser($userId, $title, $message, $type = 'info', $entityType = null, $entityId = null, $url = null, $icon = null, $color = null)
    {
        return $this->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action_url' => $url,
            'icon' => $icon,
            'color' => $color,
            'brand_id' => null,
        ]);
    }

    /**
     * Send a notification to all users belonging to a specific brand.
     * Super admins should also see this (handled at query level).
     */
    public function sendToBrand($brandId, $title, $message, $type = 'info', $entityType = null, $entityId = null, $url = null, $icon = null, $color = null)
    {
        return $this->create([
            'brand_id' => $brandId,
            'user_id' => null, // Generic brand notification
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action_url' => $url,
            'icon' => $icon,
            'color' => $color,
        ]);
    }

    /**
     * Send a notification to all users matching a specific role type.
     */
    public function sendToRole($role, $title, $message, $type = 'info', $entityType = null, $entityId = null, $url = null, $icon = null, $color = null)
    {
        $users = User::where('user_type', $role)->get();
        foreach ($users as $user) {
            $this->sendToUser($user->id, $title, $message, $type, $entityType, $entityId, $url, $icon, $color);
        }
    }

    /**
     * Mark a specific notification as read.
     */
    public function markRead($notificationId, $userId)
    {
        $notification = Notification::find($notificationId);
        
        // Ensure user has access to mark this read
        if ($notification && ($notification->user_id == $userId || $notification->brand_id == Auth::user()->brand_id || Auth::user()->user_type == 'Admin')) {
            $notification->is_read = true;
            $notification->save();
            return true;
        }
        return false;
    }

    /**
     * Mark all notifications as read for a specific user/context.
     */
    public function markAllRead($user)
    {
        if ($user->user_type == 'Admin') {
            // Super admin marks all as read
            Notification::where('is_read', false)->update(['is_read' => true]);
        } elseif ($user->brand_id) {
            // Brand admin marks brand notifications and their own as read
            Notification::where('is_read', false)
                ->where(function ($query) use ($user) {
                    $query->where('brand_id', $user->brand_id)
                          ->orWhere('user_id', $user->id);
                })->update(['is_read' => true]);
        } else {
            // Normal admin/user marks their own as read
            Notification::where('is_read', false)
                ->where('user_id', $user->id)
                ->update(['is_read' => true]);
        }
    }

    /**
     * Get the count of unread notifications for a user context.
     */
    public function getUnreadCount($user)
    {
        if ($user->user_type == 'Admin') {
            return Notification::where('is_read', false)->count();
        }

        return Notification::where('is_read', false)
            ->where(function ($query) use ($user) {
                if ($user->brand_id) {
                    $query->where('brand_id', $user->brand_id);
                }
                $query->orWhere('user_id', $user->id);
            })->count();
    }

    /**
     * Get the latest notifications for the dropdown.
     */
    public function getLatest($user, $limit = 5)
    {
        $query = Notification::query();

        if ($user->user_type != 'Admin') {
            $query->where(function ($q) use ($user) {
                if ($user->brand_id) {
                    $q->where('brand_id', $user->brand_id);
                }
                $q->orWhere('user_id', $user->id);
            });
        }

        return $query->with(['brand', 'createdByUser'])->orderBy('created_at', 'desc')->take($limit)->get();
    }

    /**
     * Delete notifications older than 180 days.
     * To be used by the scheduled cleanup task.
     */
    public function deleteOldNotifications()
    {
        $date = now()->subDays(180);
        return Notification::where('created_at', '<', $date)->delete();
    }

    /**
     * FUTURE READY STUB
     * Queue a WhatsApp notification for future CRM integration.
     */
    public function queueWhatsappNotification($phoneNumber, $templateId, $data)
    {
        // To be implemented in later phases
        Log::info('WhatsApp Notification queued (stub): ' . $phoneNumber);
    }

    /**
     * FUTURE READY STUB
     * Queue an Email notification for future CRM integration.
     */
    public function queueEmailNotification($email, $templateId, $data)
    {
        // To be implemented in later phases
        Log::info('Email Notification queued (stub): ' . $email);
    }
}
