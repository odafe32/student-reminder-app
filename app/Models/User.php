<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'department',
        'level',
        'avatar',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Add tasks relationship
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Add notifications relationship
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    // Get unread notifications
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function getDashboardRoute(): string
    {
        return $this->isAdmin()
            ? route('admin.dashboard')
            : route('student.dashboard');
    }
}