<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user.
     */
    public static function create(User $user, string $title, string $message, string $type = 'info', array $data = null): Notification
    {
        return $user->notifications()->create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);
    }

    /**
     * Create a success notification.
     */
    public static function success(User $user, string $title, string $message, array $data = null): Notification
    {
        return self::create($user, $title, $message, 'success', $data);
    }

    /**
     * Create a warning notification.
     */
    public static function warning(User $user, string $title, string $message, array $data = null): Notification
    {
        return self::create($user, $title, $message, 'warning', $data);
    }

    /**
     * Create an error notification.
     */
    public static function error(User $user, string $title, string $message, array $data = null): Notification
    {
        return self::create($user, $title, $message, 'error', $data);
    }

    /**
     * Create an info notification.
     */
    public static function info(User $user, string $title, string $message, array $data = null): Notification
    {
        return self::create($user, $title, $message, 'info', $data);
    }
}
