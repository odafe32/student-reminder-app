@extends('layouts.admin')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Notifications</h4>
        </div>
        <div class="text-end">
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="mdi mdi-check-all me-1"></i>Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Notifications</h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.notifications') }}">All</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.notifications', ['status' => 'unread']) }}">Unread</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.notifications', ['status' => 'read']) }}">Read</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($notifications as $notification)
                        <div class="d-flex align-items-start mb-3 p-3 {{ $notification->is_read ? 'bg-light' : 'bg-primary-subtle' }}">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title {{ $notification->is_read ? 'bg-secondary-subtle text-secondary' : 'bg-primary-subtle text-primary' }} rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="mdi {{ $notification->getTypeIcon() }} fs-20"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $notification->title }}</h6>
                                        <p class="text-muted mb-1">{{ $notification->message }}</p>
                                        <small class="text-muted">{{ $notification->getFormattedCreatedAt() }}</small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('admin.notifications.read', $notification) }}" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">Read</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="mdi mdi-bell-off fs-48 text-muted mb-3"></i>
                            <p class="text-muted mb-0">No notifications found.</p>
                        </div>
                    @endforelse
                </div>

                @if($notifications->hasPages())
                    <div class="card-footer">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notification Statistics -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="mdi mdi-bell-ring fs-24"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $unreadCount }}</h4>
                    <p class="text-muted mb-0">Unread Notifications</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="mdi mdi-check-circle fs-24"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $notifications->total() - $unreadCount }}</h4>
                    <p class="text-muted mb-0">Read Notifications</p>
                </div>
            </div>
        </div>
    </div>
@endsection
