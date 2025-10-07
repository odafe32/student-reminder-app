<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Process task notifications every 5 minutes
        $schedule->command('tasks:process-notifications --limit=100')
                 ->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Clean up old notifications (older than 30 days)
        $schedule->command('model:prune', ['--model' => 'App\\Models\\TaskNotification'])
                 ->daily()
                 ->at('02:00');

        // Clean up old in-app notifications (older than 90 days)
        $schedule->command('model:prune', ['--model' => 'App\\Models\\InAppNotification'])
                 ->daily()
                 ->at('02:30');

        // Update overdue tasks status
        $schedule->call(function () {
            \App\Models\Task::where('status', '!=', 'completed')
                ->where('status', '!=', 'cancelled')
                ->where('due_date', '<', now())
                ->update(['status' => 'overdue']);
        })->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}