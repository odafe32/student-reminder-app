
<div class="container-fluid py-4">
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

    <!-- User Info Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                 alt="Profile" class="rounded-circle" width="80" height="80">
                        @else
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <span class="text-white fs-3 fw-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h5 class="card-title mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-muted small mb-2">{{ auth()->user()->email }}</p>

                    @if(auth()->user()->department)
                        <span class="badge bg-light text-dark">{{ auth()->user()->department }}</span>
                    @endif

                    @if(auth()->user()->level)
                        <span class="badge bg-primary">{{ auth()->user()->level }} Level</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="text-primary mb-2">
                                <i class="fas fa-bell fa-2x"></i>
                            </div>
                            <h4 class="mb-1">0</h4>
                            <p class="text-muted small mb-0">Active Reminders</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="text-success mb-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h4 class="mb-1">0</h4>
                            <p class="text-muted small mb-0">Completed Tasks</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="text-warning mb-2">
                                <i class="fas fa-calendar fa-2x"></i>
                            </div>
                            <h4 class="mb-1">0</h4>
                            <p class="text-muted small mb-0">Upcoming Events</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent activity to show.</p>
                        <p class="small text-muted">Your reminders and notifications will appear here.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Create Reminder
                        </button>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar me-1"></i> View Calendar
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-user me-1"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Account Info</h5>
                </div>
                <div class="card-body">
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-success">{{ ucfirst(auth()->user()->status) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Role:</span>
                            <span>{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                        @if(auth()->user()->last_login_at)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Last Login:</span>
                                <span>{{ auth()->user()->last_login_at->format('M j, Y') }}</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Member Since:</span>
                            <span>{{ auth()->user()->created_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
