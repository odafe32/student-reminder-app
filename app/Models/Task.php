<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'start_date',
        'due_date',
        'reminder_time',
        'repeat_frequency',
        'priority',
        'status',
        'email_notification',
        'in_app_notification',
        'attachment',
        'completed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'reminder_time' => 'datetime:H:i',
        'email_notification' => 'boolean',
        'in_app_notification' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifications()
    {
        return $this->hasMany(TaskNotification::class);
    }

    // Query Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                     ->whereDate('due_date', '<', today());
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeDueTomorrow($query)
    {
        return $query->whereDate('due_date', today()->addDay());
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    // Status Helper Methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isOverdue()
    {
        return $this->status !== 'completed' && $this->due_date && $this->due_date->lt(today());
    }

    public function isDueToday()
    {
        return $this->due_date && $this->due_date->isToday();
    }

    public function isDueTomorrow()
    {
        return $this->due_date && $this->due_date->isTomorrow();
    }

    // Priority Helper Methods
    public function isHighPriority()
    {
        return $this->priority === 'high';
    }

    public function isMediumPriority()
    {
        return $this->priority === 'medium';
    }

    public function isLowPriority()
    {
        return $this->priority === 'low';
    }

    // Category Helper Methods
    public function isAssignment()
    {
        return $this->category === 'assignment';
    }

    public function isExam()
    {
        return $this->category === 'exam';
    }

    // UI Helper Methods
    public function getPriorityBadgeClass()
    {
        return match($this->priority) {
            'high' => 'bg-danger',
            'medium' => 'bg-warning',
            'low' => 'bg-success',
            default => 'bg-secondary'
        };
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'completed' => 'bg-success',
            'in_progress' => 'bg-info',
            'pending' => 'bg-warning',
            'overdue' => 'bg-danger',
            'cancelled' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    public function getCategoryIcon()
    {
        return match($this->category) {
            'assignment' => 'mdi-file-document',
            'exam' => 'mdi-school',
            'meeting' => 'mdi-account-group',
            'personal' => 'mdi-account',
            'event' => 'mdi-calendar-star',
            'others' => 'mdi-dots-horizontal',
            default => 'mdi-circle'
        };
    }

    // Notification Helper Methods
    public function scheduleNotifications()
    {
        Log::info('Scheduling notifications for task', [
            'task_id' => $this->id,
            'title' => $this->title,
            'due_date' => $this->due_date?->toDateTimeString(),
            'status' => $this->status
        ]);

        // Clear existing pending notifications
        $deletedCount = $this->notifications()->where('status', 'pending')->delete();
        if ($deletedCount > 0) {
            Log::info('Cleared existing pending notifications', [
                'task_id' => $this->id,
                'deleted_count' => $deletedCount
            ]);
        }

        // Don't schedule notifications for completed tasks or tasks without due dates
        if (!$this->due_date || $this->status === 'completed') {
            Log::info('Skipping notification scheduling', [
                'task_id' => $this->id,
                'reason' => !$this->due_date ? 'no_due_date' : 'task_completed'
            ]);
            return;
        }

        $reminderDateTime = $this->getReminderDateTime();
        
        if (!$reminderDateTime) {
            Log::warning('Could not determine reminder datetime', [
                'task_id' => $this->id,
                'due_date' => $this->due_date?->toDateTimeString(),
                'reminder_time' => $this->reminder_time
            ]);
            return;
        }

        // If reminder time is in the past, schedule for immediate delivery
        if ($reminderDateTime->isPast()) {
            Log::info('Reminder time is in the past, scheduling for immediate delivery', [
                'task_id' => $this->id,
                'reminder_datetime' => $reminderDateTime->toDateTimeString(),
                'now' => now()->toDateTimeString()
            ]);
            $reminderDateTime = now()->addMinutes(1); // Schedule for 1 minute from now
        }

        $scheduledCount = 0;

        // Schedule email notification
        if ($this->email_notification && $this->user && $this->user->email) {
            $notification = $this->notifications()->create([
                'user_id' => $this->user_id,
                'type' => 'email',
                'status' => 'pending',
                'scheduled_at' => $reminderDateTime,
                'message' => $this->generateNotificationMessage('email'),
            ]);

            Log::info('Email notification scheduled', [
                'task_id' => $this->id,
                'notification_id' => $notification->id,
                'scheduled_at' => $reminderDateTime->toDateTimeString(),
                'user_email' => $this->user->email
            ]);
            $scheduledCount++;
        } elseif ($this->email_notification) {
            Log::warning('Email notification requested but user has no email', [
                'task_id' => $this->id,
                'user_id' => $this->user_id
            ]);
        }

        // Schedule in-app notification
        if ($this->in_app_notification) {
            $notification = $this->notifications()->create([
                'user_id' => $this->user_id,
                'type' => 'in_app',
                'status' => 'pending',
                'scheduled_at' => $reminderDateTime,
                'message' => $this->generateNotificationMessage('in_app'),
            ]);

            Log::info('In-app notification scheduled', [
                'task_id' => $this->id,
                'notification_id' => $notification->id,
                'scheduled_at' => $reminderDateTime->toDateTimeString()
            ]);
            $scheduledCount++;
        }

        Log::info('Notification scheduling completed', [
            'task_id' => $this->id,
            'scheduled_count' => $scheduledCount,
            'reminder_datetime' => $reminderDateTime->toDateTimeString()
        ]);
    }

    public function getReminderDateTime()
    {
        if (!$this->due_date) {
            return null;
        }

        // Use reminder_time if set, otherwise default to 9:00 AM
        $reminderTime = $this->reminder_time ? 
            Carbon::parse($this->reminder_time)->format('H:i') : '09:00';

        return $this->due_date->copy()->setTimeFromTimeString($reminderTime);
    }

    public function generateNotificationMessage($type)
    {
        $dueDate = $this->due_date->format('M j, Y');
        $dueTime = $this->reminder_time ? 
            $this->reminder_time->format('g:i A') : '';

        switch ($type) {
            case 'email':
                return "Reminder: '{$this->title}' is due on {$dueDate}" . 
                       ($dueTime ? " at {$dueTime}" : '') . 
                       ". Priority: " . ucfirst($this->priority) . 
                       ". Category: " . ucfirst($this->category);

            case 'in_app':
                return "Don't forget: '{$this->title}' is due on {$dueDate}" . 
                       ($dueTime ? " at {$dueTime}" : '') .
                       " (Priority: " . ucfirst($this->priority) . ")";

            default:
                return "Task reminder: {$this->title}";
        }
    }

    // Status Management Methods
    public function markAsCompleted()
    {
        Log::info('Marking task as completed', [
            'task_id' => $this->id,
            'title' => $this->title
        ]);

        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Cancel pending notifications
        $cancelledCount = $this->notifications()
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        if ($cancelledCount > 0) {
            Log::info('Cancelled pending notifications for completed task', [
                'task_id' => $this->id,
                'cancelled_count' => $cancelledCount
            ]);
        }
    }

    public function markAsInProgress()
    {
        $this->update(['status' => 'in_progress']);
    }

    public function markAsPending()
    {
        $this->update(['status' => 'pending']);
    }

    public function markAsCancelled()
    {
        Log::info('Marking task as cancelled', [
            'task_id' => $this->id,
            'title' => $this->title
        ]);

        $this->update(['status' => 'cancelled']);

        // Cancel pending notifications
        $cancelledCount = $this->notifications()
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        if ($cancelledCount > 0) {
            Log::info('Cancelled pending notifications for cancelled task', [
                'task_id' => $this->id,
                'cancelled_count' => $cancelledCount
            ]);
        }
    }

    // Override save method to auto-schedule notifications
    public function save(array $options = [])
    {
        $wasRecentlyCreated = !$this->exists;
        $notificationFieldsChanged = false;

        // Check if notification-related fields changed
        if (!$wasRecentlyCreated) {
            $notificationFields = [
                'due_date', 
                'reminder_time', 
                'email_notification', 
                'in_app_notification',
                'status'
            ];
            
            $notificationFieldsChanged = $this->isDirty($notificationFields);
        }

        $result = parent::save($options);
        
        // Schedule notifications after saving if this is a new task or notification fields changed
        if ($wasRecentlyCreated || $notificationFieldsChanged) {
            Log::info('Task saved, scheduling notifications', [
                'task_id' => $this->id,
                'was_recently_created' => $wasRecentlyCreated,
                'notification_fields_changed' => $notificationFieldsChanged,
                'changed_fields' => $notificationFieldsChanged ? $this->getChanges() : []
            ]);

            try {
                $this->scheduleNotifications();
            } catch (\Exception $e) {
                Log::error('Failed to schedule notifications after task save', [
                    'task_id' => $this->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return $result;
    }

    // Utility method to check if task needs attention
    public function needsAttention()
    {
        return $this->isOverdue() || 
               ($this->isDueToday() && !$this->isCompleted()) ||
               ($this->isHighPriority() && !$this->isCompleted());
    }

    // Get formatted due date for display
    public function getFormattedDueDate()
    {
        if (!$this->due_date) {
            return 'No due date';
        }

        $date = $this->due_date;
        
        if ($date->isToday()) {
            return 'Today' . ($this->reminder_time ? ' at ' . $this->reminder_time->format('g:i A') : '');
        } elseif ($date->isTomorrow()) {
            return 'Tomorrow' . ($this->reminder_time ? ' at ' . $this->reminder_time->format('g:i A') : '');
        } elseif ($date->isYesterday()) {
            return 'Yesterday' . ($this->reminder_time ? ' at ' . $this->reminder_time->format('g:i A') : '');
        } else {
            return $date->format('M j, Y') . ($this->reminder_time ? ' at ' . $this->reminder_time->format('g:i A') : '');
        }
    }

    // Get time until due
    public function getTimeUntilDue()
    {
        if (!$this->due_date) {
            return null;
        }

        return $this->due_date->diffForHumans();
    }
}