@extends('layouts.student')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">My Tasks</h4>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="mdi mdi-plus me-1"></i> Add Task
            </button>
        </div>
    </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Filters -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" action="{{ route('student.tasks') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Search tasks..." 
                                       value="{{ $filters['search'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="all">All Status</option>
                                    <option value="pending" {{ ($filters['status'] ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="overdue" {{ ($filters['status'] ?? '') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-control">
                                    <option value="all">All Categories</option>
                                    <option value="assignment" {{ ($filters['category'] ?? '') === 'assignment' ? 'selected' : '' }}>Assignment</option>
                                    <option value="exam" {{ ($filters['category'] ?? '') === 'exam' ? 'selected' : '' }}>Exam</option>
                                    <option value="meeting" {{ ($filters['category'] ?? '') === 'meeting' ? 'selected' : '' }}>Meeting</option>
                                    <option value="personal" {{ ($filters['category'] ?? '') === 'personal' ? 'selected' : '' }}>Personal</option>
                                    <option value="event" {{ ($filters['category'] ?? '') === 'event' ? 'selected' : '' }}>Event</option>
                                    <option value="others" {{ ($filters['category'] ?? '') === 'others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-control">
                                    <option value="all">All Priorities</option>
                                    <option value="high" {{ ($filters['priority'] ?? '') === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="medium" {{ ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="low" {{ ($filters['priority'] ?? '') === 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="mdi mdi-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('student.tasks') }}" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-refresh me-1"></i> Clear
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tasks List -->
                <div class="card">
                    <div class="card-body">
                        @forelse($tasks as $task)
                            <div class="d-flex align-items-center p-3 border rounded mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <i class="mdi {{ $task->getCategoryIcon() }} fs-4 text-primary"></i>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <h6 class="mb-0 me-2">{{ $task->title }}</h6>
                                        <span class="badge {{ $task->getPriorityBadgeClass() }} me-2">{{ ucfirst($task->priority) }}</span>
                                        <span class="badge {{ $task->getStatusBadgeClass() }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                        
                                        @if($task->attachment)
                                            <i class="mdi mdi-paperclip ms-2 text-muted" title="Has attachment"></i>
                                        @endif
                                        
                                        @if($task->repeat_frequency !== 'none')
                                            <i class="mdi mdi-repeat ms-1 text-info" title="Recurring: {{ $task->repeat_frequency }}"></i>
                                        @endif
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="text-muted mb-1 small">{{ Str::limit($task->description, 100) }}</p>
                                    @endif
                                    
                                    <div class="d-flex align-items-center text-muted small">
                                        <span class="me-3">
                                            <i class="mdi mdi-tag me-1"></i>{{ ucfirst($task->category) }}
                                        </span>
                                        
                                        @if($task->start_date)
                                            <span class="me-3">
                                                <i class="mdi mdi-calendar-start me-1"></i>Start: {{ $task->start_date->format('M j, Y') }}
                                            </span>
                                        @endif
                                        
                                        @if($task->due_date)
                                            <span class="me-3">
                                                <i class="mdi mdi-calendar-end me-1"></i>Due: {{ $task->due_date->format('M j, Y') }}
                                                @if($task->isOverdue())
                                                    <span class="badge bg-danger ms-1">Overdue</span>
                                                @elseif($task->isDueToday())
                                                    <span class="badge bg-warning ms-1">Due Today</span>
                                                @elseif($task->isDueTomorrow())
                                                    <span class="badge bg-info ms-1">Due Tomorrow</span>
                                                @endif
                                            </span>
                                        @endif
                                        
                                        @if($task->reminder_time)
                                            <span class="me-3">
                                                <i class="mdi mdi-bell me-1"></i>{{ $task->reminder_time->format('H:i') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex-shrink-0">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @if($task->status !== 'completed')
                                                <form method="POST" action="{{ route('student.tasks.status', $task) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="mdi mdi-check me-1 text-success"></i> Mark Complete
                                                    </button>
                                                </form>
                                                
                                                @if($task->status !== 'in_progress')
                                                    <form method="POST" action="{{ route('student.tasks.status', $task) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="mdi mdi-play me-1 text-info"></i> Mark In Progress
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <form method="POST" action="{{ route('student.tasks.status', $task) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="mdi mdi-undo me-1 text-warning"></i> Mark Pending
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <div class="dropdown-divider"></div>
                                            
                                            <a class="dropdown-item" href="#" onclick="editTask({{ $task }})">
                                                <i class="mdi mdi-pencil me-1"></i> Edit
                                            </a>
                                            
                                            @if($task->attachment)
                                                <a class="dropdown-item" href="{{ asset('storage/' . $task->attachment) }}" target="_blank">
                                                    <i class="mdi mdi-download me-1"></i> Download Attachment
                                                </a>
                                            @endif
                                            
                                            <div class="dropdown-divider"></div>
                                            
                                            <form method="POST" action="{{ route('student.tasks.delete', $task) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this task?')">
                                                    <i class="mdi mdi-delete me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="mdi mdi-clipboard-text-outline fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">No tasks found</h5>
                                <p class="text-muted">Create your first task to get started!</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                    <i class="mdi mdi-plus me-1"></i> Add Task
                                </button>
                            </div>
                        @endforelse
                        
                        @if($tasks->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $tasks->appends($filters)->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Add Task Modal -->
                <div class="modal fade" id="addTaskModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('student.tasks.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                                                <input type="text" name="title" id="title" class="form-control" required>
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
                                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Add Task</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Edit Task Modal -->
                <div class="modal fade" id="editTaskModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Update Task</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                function editTask(task) {
                    // Populate form fields
                    document.getElementById('edit_title').value = task.title;
                    document.getElementById('edit_description').value = task.description || '';
                    document.getElementById('edit_category').value = task.category;
                    document.getElementById('edit_start_date').value = task.start_date;
                    document.getElementById('edit_due_date').value = task.due_date;
                    document.getElementById('edit_reminder_time').value = task.reminder_time;
                    document.getElementById('edit_priority').value = task.priority;
                    document.getElementById('edit_status').value = task.status;
                    document.getElementById('edit_repeat_frequency').value = task.repeat_frequency;

                    // Handle checkboxes
                    document.getElementById('edit_email_notification').checked = task.email_notification;
                    document.getElementById('edit_in_app_notification').checked = task.in_app_notification;

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

                    // Show modal
                    new bootstrap.Modal(document.getElementById('editTaskModal')).show();
                }

                // Set minimum dates
                document.addEventListener('DOMContentLoaded', function() {
                    const today = new Date().toISOString().split('T')[0];
                    document.getElementById('start_date').min = today;
                    document.getElementById('due_date').min = today;
                });
                </script>
@endsection
