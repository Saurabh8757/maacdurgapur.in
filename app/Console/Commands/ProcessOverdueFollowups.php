<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LeadFollowup;
use App\Services\LeadFollowupService;

class ProcessOverdueFollowups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'followups:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process overdue followups and mark them as missed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(LeadFollowupService $followupService)
    {
        $this->info('Processing overdue followups...');

        $overdueFollowups = LeadFollowup::where('status', 'pending')
            ->where('followup_date', '<', date('Y-m-d'))
            ->get();

        $whatsappService = app(\App\Services\WhatsApp\WhatsappServiceInterface::class);

        $count = 0;
        foreach ($overdueFollowups as $followup) {
            $followupService->markMissed($followup);
            
            // If they opted for WhatsApp reminder, we might want to notify them or the admin that they missed it.
            // Let's send a specific template for overdue followup.
            if ($followup->send_whatsapp_reminder) {
                // To notify the admin/counsellor:
                // $whatsappService->sendMessage($counsellorPhone, "Followup missed...");
            }
            
            $count++;
        }

        $this->info("Successfully marked {$count} followups as missed.");

        return Command::SUCCESS;
    }
}
