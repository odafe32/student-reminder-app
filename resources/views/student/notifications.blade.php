@extends('layouts.student')
@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Notifications</h1>
                    <p class="text-muted mb-0">{{ $unreadCount }} unread notifications</p>
                </div>
                <div>
                    @if($unreadCount > 0)
                        <form method="POST" action="{{ route('student.notifications.mark-all-read') }}" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-check-all me-1"></i> Mark All Read
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('student.notifications') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-control">
                        <option value="all">All Types</option>
                        <option value="info" {{ ($filters['type'] ?? '') === 'info' ? 'selected' : '' }}>Info</option>
                        <option value="success" {{ ($filters['type'] ?? '') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="warning" {{ ($filters['type'] ?? '') === 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="error" {{ ($filters['type'] ?? '') === 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="all">All Status</option>
                        <option value="unread" {{ ($filters['status'] ?? '') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ ($filters['status'] ?? '') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="mdi mdi-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('student.notifications') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-refresh me-1"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-body">
            @forelse($notifications as $notification)
                <div class="d-flex align-items-start p-3 border-bottom {{ $notification->is_read ? 'bg-light' : 'bg-white' }}">
                    <div class="flex-shrink-0 me-3">
                        <div class="notification-icon">
                            <i class="mdi {{ $notification->getTypeIcon() }} fs-4 {{ $notification->getTypeBadgeClass() }}"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                <p class="text-muted mb-1">{{ $notification->message }}</p>
                                <small class="text-muted">{{ $notification->getFormattedCreatedAt() }}</small>
                            </div>

                            <div class="flex-shrink-0">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('student.notifications.read', $notification) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="mdi mdi-check me-1"></i> Mark as Read
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="mdi mdi-bell-off-outline fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted">No notifications</h5>
                    <p class="text-muted">You're all caught up! No new notifications.</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->appends($filters)->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
