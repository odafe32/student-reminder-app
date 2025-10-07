<?php

namespace App\Console\Commands;

use App\Models\TaskNotification;
use App\Jobs\SendTaskNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessTaskNotifications extends Command
{
    protected $signature = 'tasks:process-notifications 
                           {--limit=50 : Maximum number of notifications to process}
                           {--dry-run : Show what would be processed without actually sending}';

    protected $description = 'Process pending task notifications that are due to be sent';

    public function handle()
    {
        $limit = $this->option('limit');
        $dryRun = $this->option('dry-run');

        $this->info("Processing task notifications (limit: {$limit}, dry-run: " . ($dryRun ? 'yes' : 'no') . ")");

        // Get pending notifications that are due
        $notifications = TaskNotification::with(['task', 'user'])
            ->pending()
            ->due()
            ->orderBy('scheduled_at')
            ->limit($limit)
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('No notifications to process.');
            return 0;
        }

        $this->info("Found {$notifications->count()} notifications to process:");

        $processed = 0;
        $failed = 0;

        foreach ($notifications as $notification) {
            $taskTitle = $notification->task->title ?? 'Unknown Task';
            $userEmail = $notification->user->email ?? 'Unknown User';
            
            $this->line("- {$notification->type} to {$userEmail} for '{$taskTitle}'");

            if (!$dryRun) {
                try {
                    // Dispatch the job
                    SendTaskNotification::dispatch($notification);
                    $processed++;
                    
                    $this->info("  ✓ Queued successfully");
                    
                } catch (\Exception $e) {
                    $failed++;
                    $this->error("  ✗ Failed: " . $e->getMessage());
                    
                    Log::error('Failed to queue notification', [
                        'notification_id' => $notification->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        if (!$dryRun) {
            $this->info("\nProcessing complete:");
            $this->info("- Queued: {$processed}");
            if ($failed > 0) {
                $this->error("- Failed: {$failed}");
            }
        } else {
            $this->info("\nDry run complete. No notifications were actually sent.");
        }

        return 0;
    }
}