<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Notification;
use App\Mail\TaskCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function showDashboard()
    {
        $user = Auth::user();
        
        // Get task statistics
        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->completed()->count();
        $pendingTasks = $user->tasks()->pending()->count();
        $overdueTasks = $user->tasks()->overdue()->count();
        $highPriorityTasks = $user->tasks()->highPriority()->where('status', '!=', 'completed')->count();
        
        // Get recent tasks
        $recentTasks = $user->tasks()
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();
        
        $viewData = [
            'meta_title' => 'Student Dashboard | Student Reminder System',
            'meta_desc' => 'Student Reminder System',
            'meta_image' => url('logo.png'),
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'overdueTasks' => $overdueTasks,
            'highPriorityTasks' => $highPriorityTasks,
            'recentTasks' => $recentTasks,
        ];

        return view('student.dashboard', $viewData);
    }

    public function showTasks(Request $request)
    {
        $user = Auth::user();
        
        // Build query with filters
        $query = $user->tasks();
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Filter by priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        
        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $tasks = $query->orderBy('due_date', 'asc')->paginate(15);
        
        $viewData = [
            'meta_title' => 'My Tasks | Student Reminder System',
            'meta_desc' => 'Student Reminder System',
            'meta_image' => url('logo.png'),
            'tasks' => $tasks,
            'filters' => $request->only(['status', 'category', 'priority', 'search']),
        ];

        return view('student.tasks', $viewData);
    }

    public function showCalendar()
    {
        $user = Auth::user();
        $tasks = $user->tasks()->orderBy('due_date', 'asc')->get();
        
        // Get task statistics
        $pendingTasks = $user->tasks()->pending()->count();
        $completedTasks = $user->tasks()->completed()->count();
        $overdueTasks = $user->tasks()->overdue()->count();
        
        $viewData = [
            'meta_title' => 'Calendar | Student Reminder System',
            'meta_desc' => 'Student Reminder System',
            'meta_image' => url('logo.png'),
            'tasks' => $tasks,
            'pendingTasks' => $pendingTasks,
            'completedTasks' => $completedTasks,
            'overdueTasks' => $overdueTasks,
        ];

        return view('student.calendar', $viewData);
    }

    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:assignment,exam,meeting,personal,event,others',
            'start_date' => 'nullable|date|after_or_equal:today',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'reminder_time' => 'nullable|date_format:H:i',
            'repeat_frequency' => 'required|in:none,daily,weekly,monthly,yearly',
            'priority' => 'required|in:low,medium,high',
            'email_notification' => 'boolean',
            'in_app_notification' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:5120', // 5MB max
        ]);

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('task-attachments', 'public');
            $validated['attachment'] = $attachmentPath;
        }

        $task = Auth::user()->tasks()->create($validated);

        // Send email notification if enabled
        if ($task->email_notification && Auth::user()->email) {
            try {
                Mail::to(Auth::user()->email)->send(new TaskCreatedMail($task));
            } catch (\Exception $e) {
                \Log::error('Failed to send task creation email', [
                    'task_id' => $task->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return back()->with('success', 'Task created successfully!');
    }

    public function updateTask(Request $request, Task $task)
    {
        // Ensure user owns the task
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:assignment,exam,meeting,personal,event,others',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'reminder_time' => 'nullable|date_format:H:i',
            'repeat_frequency' => 'required|in:none,daily,weekly,monthly,yearly',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,overdue,cancelled',
            'email_notification' => 'boolean',
            'in_app_notification' => 'boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif|max:5120',
            'remove_attachment' => 'nullable|boolean',
        ]);

        // Handle attachment removal
        if ($request->boolean('remove_attachment') && $task->attachment) {
            if (Storage::disk('public')->exists($task->attachment)) {
                Storage::disk('public')->delete($task->attachment);
            }
            $validated['attachment'] = null;
        }

        // Handle new attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($task->attachment && Storage::disk('public')->exists($task->attachment)) {
                Storage::disk('public')->delete($task->attachment);
            }
            
            $attachmentPath = $request->file('attachment')->store('task-attachments', 'public');
            $validated['attachment'] = $attachmentPath;
        }

        // Set completed_at timestamp if status is completed
        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        unset($validated['remove_attachment']);
        $task->update($validated);

        return back()->with('success', 'Task updated successfully!');
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        // Set completed_at timestamp if marking as completed
        if ($validated['status'] === 'completed' && $task->status !== 'completed') {
            $validated['completed_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $validated['completed_at'] = null;
        }

        $task->update($validated);

        return back()->with('success', 'Task status updated successfully!');
    }

    public function deleteTask(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete attachment if exists
        if ($task->attachment && Storage::disk('public')->exists($task->attachment)) {
            Storage::disk('public')->delete($task->attachment);
        }

        $task->delete();

        return back()->with('success', 'Task deleted successfully!');
    }

    // ... existing profile methods remain the same ...
    
    public function showProfile()
    {
        $user = Auth::user();
        
        $viewData = [
           'meta_title'=> 'My Profile | Student Reminder System',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
           'user' => $user,
        ];

        return view('student.profile', $viewData);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['nullable', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:50'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        // Handle avatar removal
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = null;
        }

        // Handle password change
        if ($request->filled('password')) {
            if (!$request->filled('current_password')) {
                return back()->withErrors(['current_password' => 'Current password is required to set a new password.']);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        unset($validated['current_password'], $validated['remove_avatar']);
        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function showNotifications(Request $request)
    {
        $user = Auth::user();

        // Get notifications with optional filters
        $query = $user->notifications();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        $notifications = $query->paginate(15);

        $viewData = [
            'meta_title' => 'Notifications | Student Reminder System',
            'meta_desc' => 'Student Reminder System',
            'meta_image' => url('logo.png'),
            'notifications' => $notifications,
            'unreadCount' => $user->unreadNotifications()->count(),
            'filters' => $request->only(['type', 'status']),
        ];

        return view('student.notifications', $viewData);
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