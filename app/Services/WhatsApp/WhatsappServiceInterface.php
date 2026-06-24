<?php

namespace App\Services\WhatsApp;

interface WhatsappServiceInterface
{
    /**
     * Send a raw text message.
     */
    public function sendMessage($phone, $message, $metadata = []): ?\App\Models\WhatsappMessage;

    /**
     * Send a pre-configured template message.
     */
    public function sendTemplate($phone, $templateType, $metadata = []): ?\App\Models\WhatsappMessage;

    /**
     * Send an acknowledgement message when a lead is created.
     */
    public function sendLeadAcknowledgement(\App\Models\Lead $lead): ?\App\Models\WhatsappMessage;

    /**
     * Send a reminder when a followup is scheduled or due.
     */
    public function sendFollowupReminder(\App\Models\LeadFollowup $followup): ?\App\Models\WhatsappMessage;

    /**
     * Process an incoming webhook to update message status.
     */
    public function updateStatus($providerMessageId, $status, $error = null);

    /**
     * Log an incoming user message.
     */
    public function logIncomingMessage($phone, $message, $providerMessageId, $metadata = []);
}
