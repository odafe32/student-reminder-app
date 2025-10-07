<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Create welcome notification for new users
            NotificationService::info(
                $user,
                'Welcome to Student Reminder System!',
                'Your account has been successfully created. You can now start managing your tasks and setting reminders.'
            );

            // Create task creation notification example
            if ($user->tasks()->count() > 0) {
                $recentTask = $user->tasks()->latest()->first();
                NotificationService::success(
                    $user,
                    'Task Created Successfully',
                    "Your task '{$recentTask->title}' has been created and saved."
                );
            }

            // Create reminder notification example if user has tasks with reminders
            $taskWithReminder = $user->tasks()->whereNotNull('reminder_time')->first();
            if ($taskWithReminder) {
                NotificationService::warning(
                    $user,
                    'Reminder Set',
                    "You have set a reminder for '{$taskWithReminder->title}' at {$taskWithReminder->reminder_time->format('H:i')}."
                );
            }
        }
    }
}
