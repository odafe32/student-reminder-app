<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TestTaskReminder extends Command
{
    protected $signature = 'tasks:test-reminder 
                           {--user-email= : Email address to test with}
                           {--create-test-task : Create a test task with immediate reminder}
                           {--send-now : Send reminder immediately}';

    protected $description = 'Test task reminder system (email notifications)';

    public function handle()
    {
        $userEmail = $this->option('user-email');
        $createTestTask = $this->option('create-test-task');
        $sendNow = $this->option('send-now');

        $this->info("🧪 Testing Task Reminder System");

        // Find or create test user
        if ($userEmail) {
            $user = User::where('email', $userEmail)->first();
            if (!$user) {
                $this->error("❌ User with email {$userEmail} not found.");
                return 1;
            }
        } else {
            $user = User::where('role', 'student')->first();
            if (!$user) {
                $this->error("❌ No student users found. Please create a student user first.");
                return 1;
            }
        }

        $this->info("👤 Using user: {$user->name} ({$user->email})");

        if ($createTestTask) {
            $this->createTestTask($user);
        }

        if ($sendNow) {
            $this->sendTestReminders($user);
        }

        // Show pending notifications
        $this->showPendingNotifications($user);

        return 0;
    }

    private function createTestTask($user)
    {
        $this->info("\n📝 Creating test task...");

        // Create a task due in 1 minute with email notification
        $task = Task::create([
            'user_id' => $user->id,
            'title' => 'Test Email Reminder - ' . now()->format('H:i:s'),
            'description' => 'This is a test task to verify email notifications are working.',
            'category' => 'personal',
            'due_date' => now()->addMinute(), // Due in 1 minute
            'reminder_time' => now()->addMinute()->format('H:i'), // Remind in 1 minute
            'priority' => 'high',
            'status' => 'pending',
            'email_notification' => true,
            'sms_notification' => false, // Disable SMS for now
            'in_app_notification' => true,
        ]);

        $this->info("✅ Test task created:");
        $this->info("   ID: {$task->id}");
        $this->info("   Title: {$task->title}");
        $this->info("   Due: {$task->due_date->format('Y-m-d H:i:s')}");
        $this->info("   Reminder: {$task->reminder_time}");
        $this->info("   Email notification: " . ($task->email_notification ? 'Yes' : 'No'));

        // The task model should automatically schedule notifications
        $this->info("   Notifications scheduled automatically via model.");
    }

    private function sendTestReminders($user)
    {
        $this->info("\n📤 Processing pending notifications for user...");

        // Get pending notifications for this user
        $notifications = \App\Models\TaskNotification::with(['task'])
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('type', 'email')
            ->get();

        if ($notifications->isEmpty()) {
            $this->warn("   No pending email notifications found for this user.");
            $this->info("   Try creating a test task first: --create-test-task");
            return;
        }

        foreach ($notifications as $notification) {
            $this->info("   Processing notification ID: {$notification->id}");
            $this->info("   Task: {$notification->task->title}");
            $this->info("   Scheduled for: {$notification->scheduled_at}");

            try {
                // Dispatch the job immediately
                \App\Jobs\SendTaskNotification::dispatch($notification);
                $this->info("   ✅ Email job dispatched successfully");
            } catch (\Exception $e) {
                $this->error("   ❌ Failed to dispatch: " . $e->getMessage());
            }
        }
    }

    private function showPendingNotifications($user)
    {
        $this->info("\n📋 Pending notifications for {$user->name}:");

        $notifications = \App\Models\TaskNotification::with(['task'])
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->orderBy('scheduled_at')
            ->get();

        if ($notifications->isEmpty()) {
            $this->info("   No pending notifications.");
            return;
        }

        foreach ($notifications as $notification) {
            $status = $notification->scheduled_at <= now() ? '🔴 DUE' : '🟡 SCHEDULED';
            $this->info("   {$status} [{$notification->type}] {$notification->task->title}");
            $this->info("      Scheduled: {$notification->scheduled_at->format('Y-m-d H:i:s')}");
            $this->info("      Status: {$notification->status}");
        }
    }
}