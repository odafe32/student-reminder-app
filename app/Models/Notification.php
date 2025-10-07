<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'read_at',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Get the type badge class for the notification.
     */
    public function getTypeBadgeClass(): string
    {
        return match($this->type) {
            'success' => 'bg-success',
            'warning' => 'bg-warning text-dark',
            'error' => 'bg-danger',
            'info' => 'bg-info',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the icon for the notification type.
     */
    public function getTypeIcon(): string
    {
        return match($this->type) {
            'success' => 'mdi-check-circle',
            'warning' => 'mdi-alert-circle',
            'error' => 'mdi-close-circle',
            'info' => 'mdi-information',
            default => 'mdi-bell',
        };
    }

    /**
     * Get formatted created at date for display.
     */
    public function getFormattedCreatedAt(): string
    {
        if ($this->created_at->isToday()) {
            return 'Today ' . $this->created_at->format('H:i');
        } elseif ($this->created_at->isYesterday()) {
            return 'Yesterday ' . $this->created_at->format('H:i');
        } else {
            return $this->created_at->format('M j, H:i');
        }
    }
}
