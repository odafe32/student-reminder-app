@extends('layouts.student')
@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Student Dashboard</h1>
                    <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Tasks</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Overdue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $overdueTasks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Recent Tasks -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Tasks</h6>
                    <a href="{{ route('student.tasks') }}" class="btn btn-sm btn-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @forelse($recentTasks as $task)
                        <div class="d-flex align-items-center p-3 border-bottom">
                            <div class="flex-shrink-0 me-3">
                                <i class="mdi {{ $task->getCategoryIcon() }} fs-4 text-primary"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="mb-0 me-2">{{ Str::limit($task->title, 50) }}</h6>
                                    <span class="badge {{ $task->getPriorityBadgeClass() }} me-2">{{ ucfirst($task->priority) }}</span>
                                    <span class="badge {{ $task->getStatusBadgeClass() }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                </div>

                                @if($task->description)
                                    <p class="text-muted mb-1 small">{{ Str::limit($task->description, 80) }}</p>
                                @endif

                                <div class="d-flex align-items-center text-muted small">
                                    <span class="me-3">
                                        <i class="mdi mdi-tag me-1"></i>{{ ucfirst($task->category) }}
                                    </span>

                                    @if($task->due_date)
                                        <span class="me-3">
                                            <i class="mdi mdi-calendar-end me-1"></i>Due: {{ $task->due_date->format('M j, Y') }}
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
                                        <a class="dropdown-item" href="#" onclick="editTask({{ $task }})">
                                            <i class="mdi mdi-pencil me-1"></i> Edit
                                        </a>
                                        @if($task->attachment)
                                            <a class="dropdown-item" href="{{ asset('storage/' . $task->attachment) }}" target="_blank">
                                                <i class="mdi mdi-download me-1"></i> Download
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="mdi mdi-clipboard-text-outline fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No pending tasks</h5>
                            <p class="text-muted mb-3">All caught up! Create a new task to get started.</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                <i class="mdi mdi-plus me-1"></i> Create Task
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="mdi mdi-plus-circle me-2"></i>Create New Task
                        </button>
                        <a href="{{ route('student.tasks') }}" class="btn btn-outline-primary">
                            <i class="mdi mdi-format-list-checks me-2"></i>View All Tasks
                        </a>
                        <a href="{{ route('student.calendar') }}" class="btn btn-outline-info">
                            <i class="mdi mdi-calendar me-2"></i>Calendar View
                        </a>
                        <a href="{{ route('student.profile') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-account-edit me-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Notifications</h6>
                    <a href="{{ route('student.notifications') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="mdi mdi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    @forelse(auth()->user()->notifications()->take(3)->get() as $notification)
                        <div class="d-flex align-items-start mb-3 p-2 border-bottom {{ $notification->is_read ? 'bg-light' : '' }}">
                            <div class="flex-shrink-0 me-3">
                                <i class="mdi {{ $notification->getTypeIcon() }} fs-5 {{ $notification->getTypeBadgeClass() }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 small">{{ Str::limit($notification->title, 40) }}</h6>
                                <p class="text-muted mb-1 small">{{ Str::limit($notification->message, 60) }}</p>
                                <small class="text-muted">{{ $notification->getFormattedCreatedAt() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-3">
                            <i class="mdi mdi-bell-off-outline fs-2 text-muted mb-2"></i>
                            <p class="text-muted mb-0 small">No notifications yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Priority Tasks Alert -->
            @if($highPriorityTasks > 0)
                <div class="card shadow mb-4 border-left-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="mdi mdi-alert-circle text-danger fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-danger mb-1">High Priority Tasks</h6>
                                <p class="text-muted mb-0">You have {{ $highPriorityTasks }} high priority {{ Str::plural('task', $highPriorityTasks) }} pending</p>
                                <a href="{{ route('student.tasks', ['priority' => 'high']) }}" class="text-decoration-none">
                                    View high priority tasks →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Today's Tasks -->
            @php
                $todayTasks = auth()->user()->tasks()
                    ->where('due_date', today())
                    ->where('status', '!=', 'completed')
                    ->count();
            @endphp
            @if($todayTasks > 0)
                <div class="card shadow mb-4 border-left-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <i class="mdi mdi-calendar-today text-warning fs-3"></i>
                            </div>
                            <div>
                                <h6 class="text-warning mb-1">Due Today</h6>
                                <p class="text-muted mb-0">{{ $todayTasks }} {{ Str::plural('task', $todayTasks) }} due today</p>
                                <a href="{{ route('student.tasks', ['due_date' => today()->format('Y-m-d')]) }}" class="text-decoration-none">
                                    View today's tasks →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Progress Overview -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Progress Overview</h6>
                </div>
                <div class="card-body">
                    @if($totalTasks > 0)
                        @php
                            $completionRate = round(($completedTasks / $totalTasks) * 100);
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Completion Rate</span>
                                <span class="font-weight-bold">{{ $completionRate }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $completionRate }}%"
                                     aria-valuenow="{{ $completionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endif

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="h4 mb-0 text-success">{{ $completedTasks }}</div>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-0 text-warning">{{ $pendingTasks }}</div>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
