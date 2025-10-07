@extends('layouts.admin')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Task Details</h4>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.tasks') }}" class="btn btn-outline-primary">
                <i class="mdi mdi-arrow-left me-1"></i>Back to Tasks
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Task Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $task->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Task Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="border-0 px-0"><strong>Title:</strong></td>
                                    <td class="border-0">{{ $task->title }}</td>
                                </tr>
                                <tr>
                                    <td class="border-0 px-0"><strong>Category:</strong></td>
                                    <td class="border-0">
                                        <span class="badge bg-info">{{ ucfirst($task->category ?? 'others') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 px-0"><strong>Priority:</strong></td>
                                    <td class="border-0">
                                        <span class="badge {{ $task->priority === 'high' ? 'bg-danger' : ($task->priority === 'medium' ? 'bg-warning' : 'bg-success') }}">
                                            {{ ucfirst($task->priority ?? 'medium') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 px-0"><strong>Status:</strong></td>
                                    <td class="border-0">
                                        @if($task->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($task->status === 'overdue')
                                            <span class="badge bg-danger">Overdue</span>
                                        @elseif($task->status === 'in_progress')
                                            <span class="badge bg-info">In Progress</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Timeline</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="border-0 px-0"><strong>Start Date:</strong></td>
                                    <td class="border-0">{{ $task->start_date ? $task->start_date->format('M d, Y') : 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td class="border-0 px-0"><strong>Due Date:</strong></td>
                                    <td class="border-0">
                                        @if($task->due_date)
                                            <span class="{{ $task->isOverdue() ? 'text-danger' : '' }}">
                                                {{ $task->due_date->format('M d, Y') }}
                                                @if($task->reminder_time)
                                                    at {{ $task->reminder_time->format('g:i A') }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">No due date</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border-0 px-0"><strong>Reminder:</strong></td>
                                    <td class="border-0">{{ $task->reminder_time ? $task->reminder_time->format('g:i A') : 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td class="border-0 px-0"><strong>Repeat:</strong></td>
                                    <td class="border-0">{{ ucfirst($task->repeat_frequency ?? 'none') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($task->description)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <div class="border p-3 rounded bg-light">
                                {{ $task->description }}
                            </div>
                        </div>
                    @endif

                    @if($task->attachment)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Attachment</h6>
                            <div class="border p-3 rounded">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-paperclip me-2"></i>
                                    <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank" class="text-decoration-none">
                                        View Attachment
                                    </a>
                                    <small class="text-muted ms-2">({{ number_format(\Storage::disk('public')->size($task->attachment) / 1024, 1) }} KB)</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Notifications</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" {{ $task->email_notification ? 'checked' : '' }} disabled>
                                    <label class="form-check-label">
                                        <i class="mdi mdi-email me-1"></i>Email Notifications
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" {{ $task->in_app_notification ? 'checked' : '' }} disabled>
                                    <label class="form-check-label">
                                        <i class="mdi mdi-bell me-1"></i>In-App Notifications
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- User Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Assigned User</h5>
                </div>
                <div class="card-body text-center">
                    @if($task->user->avatar)
                        <img src="{{ asset('storage/' . $task->user->avatar) }}" alt="User Avatar"
                             class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 80px; height: 80px;">
                            <span class="text-white fs-3">{{ strtoupper(substr($task->user->name, 0, 1)) }}</span>
                        </div>
                    @endif

                    <h6 class="mb-1">{{ $task->user->name }}</h6>
                    <p class="text-muted mb-2">{{ $task->user->email }}</p>

                    <div class="mb-3">
                        <span class="badge {{ $task->user->role === 'admin' ? 'bg-danger' : 'bg-success' }}">
                            {{ ucfirst($task->user->role) }}
                        </span>
                        <span class="badge ms-2 {{ $task->user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($task->user->status) }}
                        </span>
                    </div>

                    @if($task->user->phone)
                        <div class="mb-2">
                            <i class="mdi mdi-phone text-muted me-2"></i>
                            <span class="text-muted">{{ $task->user->phone }}</span>
                        </div>
                    @endif

                    @if($task->user->department)
                        <div class="mb-2">
                            <i class="mdi mdi-office-building text-muted me-2"></i>
                            <span class="text-muted">{{ $task->user->department }}</span>
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('admin.users.show', $task->user) }}" class="btn btn-outline-primary btn-sm">
                            <i class="mdi mdi-account me-1"></i>View User Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Task Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteTask({{ $task->id }}, '{{ $task->title }}')">
                            <i class="mdi mdi-delete me-1"></i>Delete Task
                        </button>
                    </div>
                </div>
            </div>

            <!-- Task Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Task Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="mb-2">
                                <i class="mdi mdi-calendar-clock fs-24 text-primary"></i>
                            </div>
                            <h6 class="mb-1">{{ $task->created_at->diffForHumans() }}</h6>
                            <small class="text-muted">Created</small>
                        </div>

                        @if($task->completed_at)
                            <div class="col-12">
                                <div class="mb-2">
                                    <i class="mdi mdi-check-circle fs-24 text-success"></i>
                                </div>
                                <h6 class="mb-1">{{ $task->completed_at->diffForHumans() }}</h6>
                                <small class="text-muted">Completed</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteTask(id, title) {
            if (confirm(`Are you sure you want to delete task "${title}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/tasks/${id}`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
