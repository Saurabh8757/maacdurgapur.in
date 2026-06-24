<?php
use App\Models\Lead;
use App\Services\LeadFollowupService;
use App\Models\User;

$lead = Lead::first();
$user = User::where('user_type', 'Admin')->first();

if (!$lead || !$user) {
    echo "No lead or user found.\n";
    exit;
}

$followupService = app(LeadFollowupService::class);

echo "Creating followup...\n";
$followup = $followupService->createFollowup($lead, date('Y-m-d', strtotime('+1 day')), '14:00', 'Test Followup Remarks', $user->id, $user->id);
echo "Followup created: " . $followup->id . "\n";

echo "Completing followup...\n";
$followupService->completeFollowup($followup, 'Talked to lead, all good', $user->id);
echo "Followup completed.\n";

echo "Lead activities:\n";
$activities = $lead->activities()->get();
foreach ($activities as $act) {
    echo "- " . $act->activity_type . ": " . $act->title . "\n";
}
