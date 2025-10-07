@extends('layouts.student')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Calendar</h4>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="mdi mdi-plus me-1"></i> Add Task
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

                <div class="row">
                    <!-- Task Statistics -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded">
                                                <i class="mdi mdi-clock-outline fs-24"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Pending</p>
                                        <h4 class="mb-0">{{ $tasks->where('completed', false)->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-success-subtle text-success rounded">
                                                <i class="mdi mdi-check-circle-outline fs-24"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Completed</p>
                                        <h4 class="mb-0">{{ $tasks->where('completed', true)->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-danger-subtle text-danger rounded">
                                                <i class="mdi mdi-alert-circle-outline fs-24"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Overdue</p>
                                        <h4 class="mb-0">{{ $tasks->filter(function($task) { return $task->isOverdue(); })->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-info-subtle text-info rounded">
                                                <i class="mdi mdi-calendar-today fs-24"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Due Today</p>
                                        <h4 class="mb-0">{{ $tasks->where('completed', false)->filter(function($task) { return $task->due_date && $task->due_date->isToday(); })->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white"><i class="mdi mdi-plus-circle me-2"></i>Add New Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('student.tasks.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Enter task title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <option value="assignment">Assignment</option>
                                    <option value="exam">Exam</option>
                                    <option value="meeting">Meeting</option>
                                    <option value="personal">Personal</option>
                                    <option value="event">Event</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter task description"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="reminder_time" class="form-label">Reminder Time</label>
                                <input type="time" name="reminder_time" id="reminder_time" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                <select name="priority" id="priority" class="form-control" required>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="repeat_frequency" class="form-label">Repeat</label>
                                <select name="repeat_frequency" id="repeat_frequency" class="form-control">
                                    <option value="none">No Repeat</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Attachment</label>
                                <input type="file" name="attachment" id="attachment" class="form-control" 
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                                <div class="form-text">Max 5MB. PDF, DOC, DOCX, JPG, PNG, GIF</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notifications</label>
                        <div class="form-check">
                            <input type="checkbox" name="email_notification" id="email_notification" class="form-check-input" value="1" checked>
                            <label for="email_notification" class="form-check-label">Email Notification</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="in_app_notification" id="in_app_notification" class="form-check-input" value="1" checked>
                            <label for="in_app_notification" class="form-check-label">In-App Notification</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-check me-1"></i>Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white"><i class="mdi mdi-pencil me-2"></i>Edit Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editTaskForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Task Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="edit_title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_category" class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category" id="edit_category" class="form-control" required>
                                    <option value="assignment">Assignment</option>
                                    <option value="exam">Exam</option>
                                    <option value="meeting">Meeting</option>
                                    <option value="personal">Personal</option>
                                    <option value="event">Event</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="edit_start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="edit_due_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_reminder_time" class="form-label">Reminder Time</label>
                                <input type="time" name="reminder_time" id="edit_reminder_time" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select name="status" id="edit_status" class="form-control">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_priority" class="form-label">Priority</label>
                                <select name="priority" id="edit_priority" class="form-control">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_repeat_frequency" class="form-label">Repeat</label>
                                <select name="repeat_frequency" id="edit_repeat_frequency" class="form-control">
                                    <option value="none">No Repeat</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_attachment" class="form-label">Attachment</label>
                                <input type="file" name="attachment" id="edit_attachment" class="form-control">
                                <div id="current-attachment" class="form-text"></div>
                                <div class="form-check mt-2" id="remove-attachment-check" style="display: none;">
                                    <input type="checkbox" name="remove_attachment" id="remove_attachment" class="form-check-input" value="1">
                                    <label for="remove_attachment" class="form-check-label text-danger">Remove current attachment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notifications</label>
                        <div class="form-check">
                            <input type="checkbox" name="email_notification" id="edit_email_notification" class="form-check-input" value="1">
                            <label for="edit_email_notification" class="form-check-label">Email Notification</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="in_app_notification" id="edit_in_app_notification" class="form-check-input" value="1">
                            <label for="edit_in_app_notification" class="form-check-label">In-App Notification</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="mdi mdi-check me-1"></i>Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Task Modal -->
<div class="modal fade" id="viewTaskModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-eye me-2"></i>Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="task-details">
                    <h4 id="view_title" class="mb-3"></h4>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-2"><strong><i class="mdi mdi-calendar me-2"></i>Due Date:</strong></p>
                            <p id="view_due_date" class="text-muted"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="mb-2"><strong><i class="mdi mdi-text me-2"></i>Description:</strong></p>
                        <p id="view_description" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <p class="mb-2"><strong><i class="mdi mdi-check-circle me-2"></i>Status:</strong></p>
                        <span id="view_status"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" id="editFromViewBtn">
                    <i class="mdi mdi-pencil me-1"></i>Edit Task
                </button>
                <form method="POST" id="deleteFromViewForm" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                        <i class="mdi mdi-delete me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>

<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
    }

    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 2px 4px;
    }

    .fc-event-completed {
        opacity: 0.6;
        text-decoration: line-through;
    }

    .fc-daygrid-event {
        white-space: normal !important;
        align-items: normal !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Prepare events from tasks
    var events = [
        @foreach($tasks as $task)
        {
            id: '{{ $task->id }}',
            title: '{{ addslashes($task->title) }}',
            start: '{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i:s') : now()->format('Y-m-d\TH:i:s') }}',
            description: '{{ addslashes($task->description ?? '') }}',
            completed: {{ $task->completed ? 'true' : 'false' }},
            category: '{{ $task->category ?? 'others' }}',
            priority: '{{ $task->priority ?? 'medium' }}',
            status: '{{ $task->status ?? 'pending' }}',
            start_date: '{{ $task->start_date ?? '' }}',
            due_date: '{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}',
            reminder_time: '{{ $task->reminder_time ?? '' }}',
            repeat_frequency: '{{ $task->repeat_frequency ?? 'none' }}',
            email_notification: {{ $task->email_notification ? 'true' : 'false' }},
            in_app_notification: {{ $task->in_app_notification ? 'true' : 'false' }},
            attachment: '{{ $task->attachment ?? '' }}',
            backgroundColor: '{{ $task->completed ? '#6b7280' : '#3b82f6' }}',
            borderColor: '{{ $task->completed ? '#6b7280' : '#3b82f6' }}',
            classNames: {{ $task->completed ? "['fc-event-completed']" : '[]' }}
        },
        @endforeach
    ];

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: events,
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            showTaskDetails(info.event);
        },
        dateClick: function(info) {
            // Pre-fill the add task modal with clicked date
            document.getElementById('due_date').value = info.dateStr;
            new bootstrap.Modal(document.getElementById('addTaskModal')).show();
        },
        height: 'auto',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short'
        }
    });

    calendar.render();

    // Set minimum dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').min = today;
    document.getElementById('due_date').min = today;
});

function showTaskDetails(event) {
    document.getElementById('view_title').textContent = event.title;
    document.getElementById('view_due_date').textContent = new Date(event.start).toLocaleString();
    document.getElementById('view_description').textContent = event.extendedProps.description || 'No description';

    // Status badge
    var statusHtml = event.extendedProps.completed
        ? '<span class="badge bg-success"><i class="mdi mdi-check-circle me-1"></i>Completed</span>'
        : '<span class="badge bg-warning"><i class="mdi mdi-clock me-1"></i>Pending</span>';
    document.getElementById('view_status').innerHTML = statusHtml;

    // Set up edit button
    document.getElementById('editFromViewBtn').onclick = function() {
        bootstrap.Modal.getInstance(document.getElementById('viewTaskModal')).hide();

        // Build task object from event data
        var taskData = {
            id: event.id,
            title: event.title,
            description: event.extendedProps.description,
            category: event.extendedProps.category,
            start_date: event.extendedProps.start_date,
            due_date: event.extendedProps.due_date,
            reminder_time: event.extendedProps.reminder_time,
            priority: event.extendedProps.priority,
            status: event.extendedProps.status,
            repeat_frequency: event.extendedProps.repeat_frequency,
            email_notification: event.extendedProps.email_notification,
            in_app_notification: event.extendedProps.in_app_notification,
            attachment: event.extendedProps.attachment
        };

        editTask(taskData);
    };

    // Set up delete form
    document.getElementById('deleteFromViewForm').action = '/student/tasks/' + event.id;

    new bootstrap.Modal(document.getElementById('viewTaskModal')).show();
}

function editTask(task) {
    // Populate form fields
    document.getElementById('edit_title').value = task.title;
    document.getElementById('edit_description').value = task.description || '';
    document.getElementById('edit_category').value = task.category || 'others';
    document.getElementById('edit_start_date').value = task.start_date || '';
    document.getElementById('edit_due_date').value = task.due_date || '';
    document.getElementById('edit_reminder_time').value = task.reminder_time || '';
    document.getElementById('edit_priority').value = task.priority || 'medium';
    document.getElementById('edit_status').value = task.status || 'pending';
    document.getElementById('edit_repeat_frequency').value = task.repeat_frequency || 'none';

    // Handle checkboxes
    document.getElementById('edit_email_notification').checked = task.email_notification || false;
    document.getElementById('edit_in_app_notification').checked = task.in_app_notification || false;

    // Handle attachment
    const currentAttachment = document.getElementById('current-attachment');
    const removeAttachmentCheck = document.getElementById('remove-attachment-check');

    if (task.attachment) {
        currentAttachment.innerHTML = 'Current: <a href="/storage/' + task.attachment + '" target="_blank">View Attachment</a>';
        removeAttachmentCheck.style.display = 'block';
    } else {
        currentAttachment.innerHTML = '';
        removeAttachmentCheck.style.display = 'none';
    }

    // Set form action
    document.getElementById('editTaskForm').action = '/student/tasks/' + task.id;

    new bootstrap.Modal(document.getElementById('editTaskModal')).show();
}

function formatDateTimeLocal(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
}
</script>
@endsection
