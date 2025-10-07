<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'sms_notification',
        'in_app_notification',
        'attachment',
        'completed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'reminder_time' => 'datetime:H:i',
        'email_notification' => 'boolean',
        'sms_notification' => 'boolean',
        'in_app_notification' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
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
        return $this->status !== 'completed' && $this->due_date && $this->due_date->isPast();
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

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'completed')
                    ->where('due_date', '<', now());
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeDueTomorrow($query)
    {
        return $query->whereDate('due_date', today()->addDay());
    }

    // Utility Methods
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

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
    }

    public function markAsInProgress()
    {
        $this->update(['status' => 'in_progress']);
    }

    public function markAsPending()
    {
        $this->update(['status' => 'pending']);
    }
}