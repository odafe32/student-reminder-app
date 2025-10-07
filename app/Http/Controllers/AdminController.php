<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function showDashboard()
    {
        $viewData = [
           'meta_title'=> 'Admin Dashboard | Student Reminder System',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
        ];

        return view('admin.dashboard', $viewData);
    }

    public function showProfile()
    {
        $viewData = [
           'meta_title'=> 'Admin Profile | Student Reminder System',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
        ];

        return view('admin.profile', $viewData);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->department = $request->department;
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'role' => 'required|in:admin,student',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'role' => $request->role,
            'status' => $request->status,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        return back()->with('success', 'User created successfully!');
    }

    public function showUsers()
    {
        $users = User::all();

        $viewData = [
           'meta_title'=> 'Manage Users | Admin Panel',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
           'users' => $users,
        ];

        return view('admin.users', $viewData);
    }

    public function showUser(User $user)
    {
        $viewData = [
           'meta_title'=> 'User Details | Admin Panel',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
           'user' => $user,
        ];

        return view('admin.user-details', $viewData);
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,student',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->department = $request->department;
        $user->level = $request->level;
        $user->status = $request->status;
        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'User updated successfully!');
    }

    public function deleteUser(User $user)
    {
        // Prevent deleting the current admin
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Delete user's avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function showTasks()
    {
        $tasks = Task::with('user')->get();

        $viewData = [
           'meta_title'=> 'View Tasks & Reminders | Admin Panel',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
           'tasks' => $tasks,
        ];

        return view('admin.tasks', $viewData);
    }

    public function showTask(Task $task)
    {
        $viewData = [
           'meta_title'=> 'Task Details | Admin Panel',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
           'task' => $task,
        ];

        return view('admin.task-details', $viewData);
    }

    public function deleteTask(Task $task)
    {
        // Delete task attachment if exists
        if ($task->attachment) {
            Storage::disk('public')->delete($task->attachment);
        }

        $task->delete();

        return back()->with('success', 'Task deleted successfully!');
    }

    public function showNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(20);

        $viewData = [
            'meta_title' => 'Notifications | Admin Panel',
            'meta_desc' => 'Student Reminder System',
            'meta_image' => url('logo.png'),
            'notifications' => $notifications,
            'unreadCount' => $user->unreadNotifications()->count(),
            'filters' => request()->only(['type', 'status']),
        ];

        return view('admin.notifications', $viewData);
    }

    public function markNotificationAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'All notifications marked as read!');
    }
}