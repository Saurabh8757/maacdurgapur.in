<?php

namespace App\Services\WhatsApp;

use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\WhatsappMessage;
use App\Models\WhatsappSetting;
use App\Models\WhatsappTemplate;
use App\Services\LeadActivityService;
use Illuminate\Support\Facades\Log;

class WhatsappService implements WhatsappServiceInterface
{
    protected $provider;
    protected $apiKey;
    protected $apiSecret;
    protected $phoneId;

    public function __construct()
    {
        // Load configurations dynamically from settings.
        $this->provider = WhatsappSetting::get('whatsapp_provider', 'stub');
        $this->apiKey = WhatsappSetting::get('whatsapp_api_key');
        $this->apiSecret = WhatsappSetting::get('whatsapp_api_secret');
        $this->phoneId = WhatsappSetting::get('whatsapp_phone_id');
    }

    public function sendMessage($phone, $message, $metadata = []): ?WhatsappMessage
    {
        $whatsappMessage = WhatsappMessage::create([
            'lead_id' => $metadata['lead_id'] ?? null,
            'followup_id' => $metadata['followup_id'] ?? null,
            'user_id' => auth()->id(),
            'provider' => $this->provider,
            'direction' => 'outbound',
            'phone' => $phone,
            'message_type' => 'text',
            'message' => $message,
            'status' => 'queued',
            'metadata' => $metadata,
        ]);

        return $this->dispatchToProvider($whatsappMessage);
    }

    public function sendTemplate($phone, $templateType, $metadata = []): ?WhatsappMessage
    {
        $template = WhatsappTemplate::where('template_type', $templateType)->where('is_active', true)->first();

        if (!$template) {
            Log::warning("WhatsApp template {$templateType} not found or inactive.");
            return null;
        }

        // Extremely simple parsing: replace {{name}} with metadata value
        $content = $template->content;
        foreach ($metadata as $key => $value) {
            if (is_scalar($value)) {
                $content = str_replace('{{' . $key . '}}', $value, $content);
            }
        }

        $whatsappMessage = WhatsappMessage::create([
            'lead_id' => $metadata['lead_id'] ?? null,
            'followup_id' => $metadata['followup_id'] ?? null,
            'user_id' => auth()->id(),
            'provider' => $this->provider,
            'direction' => 'outbound',
            'phone' => $phone,
            'message_type' => 'template',
            'message' => $content,
            'status' => 'queued',
            'metadata' => $metadata,
        ]);

        return $this->dispatchToProvider($whatsappMessage);
    }

    public function sendLeadAcknowledgement(Lead $lead): ?WhatsappMessage
    {
        if (!$lead->phone) return null;

        $metadata = [
            'lead_id' => $lead->id,
            'name' => $lead->name,
            'phone' => $lead->phone,
        ];

        return $this->sendTemplate($lead->phone, 'Lead Acknowledgement', $metadata);
    }

    public function sendFollowupReminder(LeadFollowup $followup): ?WhatsappMessage
    {
        $lead = $followup->lead;
        if (!$lead || !$lead->phone) return null;

        $metadata = [
            'lead_id' => $lead->id,
            'followup_id' => $followup->id,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'date' => $followup->followup_date->format('Y-m-d'),
            'time' => $followup->followup_time,
        ];

        return $this->sendTemplate($lead->phone, 'Followup Reminder', $metadata);
    }

    protected function dispatchToProvider(WhatsappMessage $whatsappMessage): WhatsappMessage
    {
        try {
            // Here, we would match $this->provider (twilio, interakt, meta)
            // and actually make the cURL/Guzzle request.
            // For now, we simulate success for provider independence.
            
            // Simulate Provider Logic:
            if ($this->provider === 'stub') {
                $providerMessageId = 'sim_' . uniqid();
                $status = 'sent';
                $error = null;
            } else {
                // Placeholder for real HTTP call
                $providerMessageId = 'req_' . uniqid();
                $status = 'sent';
                $error = null;
            }

            $whatsappMessage->update([
                'provider_message_id' => $providerMessageId,
                'status' => $status,
                'sent_at' => now(),
            ]);

            $this->logActivity($whatsappMessage, 'WHATSAPP_SENT');

        } catch (\Exception $e) {
            $whatsappMessage->increment('retry_count');
            
            if ($whatsappMessage->retry_count >= 3) {
                $whatsappMessage->update([
                    'status' => 'failed',
                    'last_error' => $e->getMessage(),
                ]);
                $this->logActivity($whatsappMessage, 'WHATSAPP_FAILED');
            }
        }

        return $whatsappMessage;
    }

    public function updateStatus($providerMessageId, $status, $error = null)
    {
        $message = WhatsappMessage::where('provider_message_id', $providerMessageId)->first();
        if (!$message) return;

        $updateData = ['status' => $status];
        
        if ($status === 'delivered') {
            $updateData['delivered_at'] = now();
            $activityType = 'WHATSAPP_DELIVERED';
        } elseif ($status === 'read') {
            $updateData['read_at'] = now();
            $activityType = 'WHATSAPP_READ';
        } elseif ($status === 'failed') {
            $updateData['last_error'] = $error;
            $activityType = 'WHATSAPP_FAILED';
        } else {
            return;
        }

        $message->update($updateData);
        $this->logActivity($message, $activityType);
    }

    public function logIncomingMessage($phone, $message, $providerMessageId, $metadata = [])
    {
        // Try to associate with an existing lead by phone
        $lead = Lead::where('phone', $phone)->first();

        $whatsappMessage = WhatsappMessage::create([
            'lead_id' => $lead ? $lead->id : null,
            'provider' => $this->provider,
            'direction' => 'inbound',
            'phone' => $phone,
            'message_type' => 'text',
            'message' => $message,
            'provider_message_id' => $providerMessageId,
            'status' => 'delivered',
            'metadata' => $metadata,
            'delivered_at' => now(),
        ]);

        if ($lead) {
            $this->logActivity($whatsappMessage, 'WHATSAPP_RECEIVED');
        }

        return $whatsappMessage;
    }

    protected function logActivity(WhatsappMessage $message, $activityType)
    {
        if (!$message->lead_id) return;

        $activityService = app(LeadActivityService::class);
        $activityService->logActivity(
            $message->lead_id,
            $activityType,
            "WhatsApp {$message->status}",
            $message->message,
            $message->user_id,
            [
                'whatsapp_message_id' => $message->id,
                'provider_message_id' => $message->provider_message_id,
                'status' => $message->status,
                'phone' => $message->phone,
            ]
        );
    }
}
