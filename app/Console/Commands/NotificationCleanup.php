<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotificationCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete notifications older than 180 days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $service = new \App\Services\NotificationService();
        $deleted = $service->deleteOldNotifications();
        $this->info("Successfully deleted {$deleted} old notifications.");
        return 0;
    }
}
