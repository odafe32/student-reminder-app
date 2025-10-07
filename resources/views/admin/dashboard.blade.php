@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">Admin Dashboard</h1>
                    <p class="text-muted mb-0">System overview and management</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-primary mb-2">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h4 class="mb-1">{{ \App\Models\User::count() }}</h4>
                    <p class="text-muted small mb-0">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-success mb-2">
                        <i class="fas fa-user-graduate fa-2x"></i>
                    </div>
                    <h4 class="mb-1">{{ \App\Models\User::where('role', 'student')->count() }}</h4>
                    <p class="text-muted small mb-0">Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-warning mb-2">
                        <i class="fas fa-user-shield fa-2x"></i>
                    </div>
                    <h4 class="mb-1">{{ \App\Models\User::where('role', 'admin')->count() }}</h4>
                    <p class="text-muted small mb-0">Admins</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="text-info mb-2">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h4 class="mb-1">{{ \App\Models\User::where('status', 'active')->count() }}</h4>
                    <p class="text-muted small mb-0">Active Users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Users</h5>
                    <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Manage Users
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(\App\Models\User::latest()->take(5)->get() as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}"
                                                         alt="Profile" class="rounded-circle me-2" width="32" height="32">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center me-2"
                                                         style="width: 32px; height: 32px;">
                                                        <span class="text-white small fw-bold">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($user->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('M j, Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                    view
                                                </a>
                                                <a href="#" class="btn btn-outline-secondary btn-sm" onclick="editUser({{ $user->id }})">
                                                    <i class="fas fa-edit"></i>
                                                    edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">No users found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Admin Profile -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                 alt="Profile" class="rounded-circle" width="80" height="80">
                        @else
                            <div class="bg-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <span class="text-white fs-3 fw-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h5 class="card-title mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-muted small mb-2">{{ auth()->user()->email }}</p>
                    <span class="badge bg-danger">Administrator</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i> Manage Users
                        </a>
                        <a href="{{ route('admin.tasks') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-tasks me-1"></i> View Tasks
                        </a>
                        <a href="{{ route('admin.profile') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user-cog me-1"></i> Profile Settings
                        </a>
                        <a href="{{ route('admin.notifications') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-bell me-1"></i> Notifications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
