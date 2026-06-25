<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WhatsappSetting;
use App\Models\WhatsappWebhookLog;
use App\Services\WhatsApp\WhatsappServiceInterface;
use Illuminate\Http\Request;

class WhatsappWebhookController extends Controller
{
    public function verify(Request $request)
    {
        $expectedToken = WhatsappSetting::get('whatsapp_webhook_token');
        
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode && $token) {
            if ($mode === 'subscribe' && $token === $expectedToken) {
                return response($challenge, 200);
            }
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(['error' => 'Bad Request'], 400);
    }

    public function handle(Request $request, WhatsappServiceInterface $whatsappService)
    {
        // Token Validation
        $expectedToken = WhatsappSetting::get('whatsapp_webhook_token');
        $token = $request->query('token') ?? $request->header('X-Hub-Signature'); // Example validation logic
        
        if (
            !is_string($expectedToken)
            || $expectedToken === ''
            || !is_string($token)
            || !hash_equals($expectedToken, $token)
        ) {
            WhatsappWebhookLog::create([
                'provider' => 'unknown',
                'payload' => [],
                'status' => 'rejected',
                'error' => 'Invalid webhook token',
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        WhatsappWebhookLog::create([
            'provider' => 'stub', // Replace with dynamic if needed
            'payload' => $request->all(),
            'status' => 'processed',
        ]);

        // Process Payload (This is a simplified example payload parsing)
        $payload = $request->all();
        
        if (isset($payload['statuses'])) {
            foreach ($payload['statuses'] as $statusUpdate) {
                $whatsappService->updateStatus(
                    $statusUpdate['id'], 
                    $statusUpdate['status'],
                    $statusUpdate['errors'][0]['title'] ?? null
                );
            }
        }

        if (isset($payload['messages'])) {
            foreach ($payload['messages'] as $incomingMessage) {
                $whatsappService->logIncomingMessage(
                    $incomingMessage['from'],
                    $incomingMessage['text']['body'] ?? '',
                    $incomingMessage['id'],
                    $incomingMessage
                );
            }
        }

        return response()->json(['status' => 'success']);
    }
}
