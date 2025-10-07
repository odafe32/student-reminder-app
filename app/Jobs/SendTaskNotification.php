<?php

namespace App\Jobs;

use App\Models\TaskNotification;
use App\Mail\TaskReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTaskNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1 min, 5 min, 15 min

    public function __construct(
        public TaskNotification $notification
    ) {}

    public function handle(): void
    {
        try {
            switch ($this->notification->type) {
                case 'email':
                    $this->sendEmailNotification();
                    break;
                    
                case 'in_app':
                    $this->sendInAppNotification();
                    break;
            }

            $this->notification->markAsSent();
            
            Log::info('Task notification sent successfully', [
                'notification_id' => $this->notification->id,
                'type' => $this->notification->type,
                'user_id' => $this->notification->user_id,
                'task_id' => $this->notification->task_id,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Task notification failed', [
                'notification_id' => $this->notification->id,
                'type' => $this->notification->type,
                'user_id' => $this->notification->user_id,
                'task_id' => $this->notification->task_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->notification->markAsFailed($e->getMessage());
            throw $e; // Re-throw to trigger retry
        }
    }

    private function sendEmailNotification()
    {
        if (!$this->notification->user->email) {
            throw new \Exception('User has no email address');
        }

        Mail::to($this->notification->user->email)
            ->send(new TaskReminderMail($this->notification->task));

        Log::info('Email notification sent', [
            'to' => $this->notification->user->email,
            'task_title' => $this->notification->task->title
        ]);
    }

    private function sendInAppNotification()
    {
        // Create in-app notification record
        \App\Models\InAppNotification::create([
            'user_id' => $this->notification->user_id,
            'task_id' => $this->notification->task_id,
            'title' => 'Task Reminder',
            'message' => $this->notification->message,
            'type' => 'task_reminder',
            'is_read' => false,
        ]);

        Log::info('In-app notification created', [
            'user_id' => $this->notification->user_id,
            'task_title' => $this->notification->task->title
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->notification->markAsFailed($exception->getMessage());
        
        Log::error('Task notification job failed permanently', [
            'notification_id' => $this->notification->id,
            'type' => $this->notification->type,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);
    }
}