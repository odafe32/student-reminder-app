<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskReminderMail;
use App\Models\Task;
use App\Models\User;

class TestEmail extends Command
{
    protected $signature = 'mail:test 
                           {email : Email address to send test to}
                           {--task-id= : Specific task ID to use for test}';

    protected $description = 'Test email configuration by sending a test task reminder';

    public function handle()
    {
        $email = $this->argument('email');
        $taskId = $this->option('task-id');

        $this->info("📧 Testing email configuration...");
        $this->info("📬 To: {$email}");

        try {
            // Get or create a test task
            if ($taskId) {
                $task = Task::find($taskId);
                if (!$task) {
                    $this->error("❌ Task with ID {$taskId} not found.");
                    return 1;
                }
            } else {
                // Create a temporary test task
                $user = User::where('role', 'student')->first();
                if (!$user) {
                    $this->error("❌ No student users found.");
                    return 1;
                }

                $task = new Task([
                    'user_id' => $user->id,
                    'title' => 'Test Email Reminder',
                    'description' => 'This is a test email to verify the email system is working correctly.',
                    'category' => 'personal',
                    'due_date' => now()->addDay(),
                    'reminder_time' => '09:00',
                    'priority' => 'medium',
                    'status' => 'pending',
                ]);
                $task->user = $user; // Set the relationship manually
            }

            $this->info("📝 Using task: {$task->title}");
            $this->info("👤 For user: {$task->user->name}");

            // Send the email
            Mail::to($email)->send(new TaskReminderMail($task));

            $this->info("✅ Test email sent successfully!");
            $this->info("📬 Check your inbox at: {$email}");
            
            // Check mail configuration
            $this->info("\n📊 Mail Configuration:");
            $this->info("   Driver: " . config('mail.default'));
            $this->info("   Host: " . config('mail.mailers.smtp.host'));
            $this->info("   Port: " . config('mail.mailers.smtp.port'));
            $this->info("   From: " . config('mail.from.address'));

        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            
            $this->info("\n🔧 Troubleshooting:");
            $this->info("1. Check your .env mail configuration");
            $this->info("2. Verify MAIL_* settings are correct");
            $this->info("3. Check storage/logs/laravel.log for details");
            
            return 1;
        }

        return 0;
    }
}