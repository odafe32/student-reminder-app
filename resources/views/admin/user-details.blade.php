@extends('layouts.admin')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">User Details</h4>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                <i class="mdi mdi-arrow-left me-1"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile Picture"
                             class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                             style="width: 120px; height: 120px;">
                            <span class="text-white fs-2 fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif

                    <h4 class="mb-2">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <div class="mb-3">
                        <span class="badge fs-6 {{ $user->role === 'admin' ? 'bg-danger' : 'bg-success' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="badge fs-6 ms-2 {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>

                    @if($user->phone)
                        <div class="mb-2">
                            <i class="mdi mdi-phone text-muted me-2"></i>
                            <span class="text-muted">{{ $user->phone }}</span>
                        </div>
                    @endif

                    @if($user->department)
                        <div class="mb-2">
                            <i class="mdi mdi-office-building text-muted me-2"></i>
                            <span class="text-muted">{{ $user->department }}</span>
                        </div>
                    @endif

                    @if($user->level)
                        <div class="mb-2">
                            <i class="mdi mdi-school text-muted me-2"></i>
                            <span class="text-muted">Level {{ $user->level }}</span>
                        </div>
                    @endif

                    <div class="mt-4">
                        <small class="text-muted">Joined {{ $user->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->department }}', '{{ $user->level }}', '{{ $user->status }}', '{{ $user->role }}')">
                            <i class="mdi mdi-pencil me-1"></i>Edit User
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                            <i class="mdi mdi-delete me-1"></i>Delete User
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="toggleUserStatus({{ $user->id }}, '{{ $user->status }}')">
                            <i class="mdi mdi-account-check me-1"></i>
                            {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- User Tasks -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">User Tasks ({{ $user->tasks()->count() }})</h5>
                    @if($user->role === 'student')
                        <small class="text-muted">Student tasks and assignments</small>
                    @endif
                </div>
                <div class="card-body">
                    @forelse($user->tasks()->latest()->take(10)->get() as $task)
                        <div class="d-flex align-items-center mb-3 p-3 border rounded">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title {{ $task->status === 'completed' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="mdi {{ $task->status === 'completed' ? 'mdi-check-circle' : 'mdi-clock-outline' }} fs-20"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($task->title, 50) }}</h6>
                                <p class="text-muted mb-1 small">{{ Str::limit($task->description ?? 'No description', 80) }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <small class="badge bg-info">{{ ucfirst($task->category ?? 'others') }}</small>
                                    <small class="badge {{ $task->priority === 'high' ? 'bg-danger' : ($task->priority === 'medium' ? 'bg-warning' : 'bg-success') }}">
                                        {{ ucfirst($task->priority ?? 'medium') }}
                                    </small>
                                    @if($task->due_date)
                                        <small class="text-muted">
                                            Due: {{ $task->due_date->format('M d, Y') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if($task->status === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($task->status === 'overdue')
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="mdi mdi-clipboard-text-outline fs-48 text-muted mb-3"></i>
                            <p class="text-muted mb-0">No tasks found for this user.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- User Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i class="mdi mdi-clipboard-list fs-24 text-primary mb-2"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->tasks()->count() }}</h4>
                            <p class="text-muted small mb-0">Total Tasks</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i class="mdi mdi-check-circle fs-24 text-success mb-2"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->tasks()->where('status', 'completed')->count() }}</h4>
                            <p class="text-muted small mb-0">Completed</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i class="mdi mdi-clock-outline fs-24 text-warning mb-2"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->tasks()->where('status', '!=', 'completed')->count() }}</h4>
                            <p class="text-muted small mb-0">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal (same as in users.blade.php) -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white"><i class="mdi mdi-pencil me-2"></i>Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editUserForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="edit_email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_phone" class="form-label">Phone</label>
                                    <input type="text" name="phone" id="edit_phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_department" class="form-label">Department</label>
                                    <input type="text" name="department" id="edit_department" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" id="edit_role" class="form-control" required>
                                        <option value="student">Student</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="edit_status" class="form-control" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_level" class="form-label">Level</label>
                            <input type="text" name="level" id="edit_level" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editUser(id, name, email, phone, department, level, status, role) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_department').value = department;
            document.getElementById('edit_level').value = level;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_role').value = role;

            document.getElementById('editUserForm').action = `/admin/users/${id}`;

            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }

        function deleteUser(id, name) {
            if (confirm(`Are you sure you want to delete user "${name}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/users/${id}`;

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

        function toggleUserStatus(id, currentStatus) {
            const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
            const statusText = newStatus === 'active' ? 'activate' : 'deactivate';

            if (confirm(`Are you sure you want to ${statusText} this user?`)) {
                fetch(`/admin/users/${id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>
@endsection
