@extends('layouts.student')

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-xxl">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">My Profile</h4>
                    </div>
                </div>

                <!-- Display success messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Display error messages -->
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Profile Info Card -->
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="mb-3 position-relative">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                 alt="Profile" class="rounded-circle" width="100" height="100"
                                                 style="object-fit: cover;" id="current-avatar">
                                        @else
                                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 100px; height: 100px;" id="avatar-placeholder">
                                                <span class="text-white fs-1 fw-bold">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                        
                                        <!-- Camera icon overlay -->
                                        <div class="position-absolute bottom-0 end-0">
                                            <button type="button" class="btn btn-primary btn-sm rounded-circle p-1" 
                                                    onclick="document.getElementById('avatar-upload').click()" 
                                                    style="width: 30px; height: 30px;">
                                                <i class="mdi mdi-camera fs-6"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <h4 class="mb-1">{{ $user->name }}</h4>
                                    <p class="text-muted mb-2">{{ $user->email }}</p>
                                    <span class="badge bg-success">{{ ucfirst($user->status) }}</span>
                                </div>

                                <div class="mt-4">
                                    <h5 class="fs-15 mb-3">Account Information</h5>
                                    <div class="table-responsive">
                                        <table class="table table-borderless mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-medium">Role:</td>
                                                    <td>{{ ucfirst($user->role) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium">Phone:</td>
                                                    <td>{{ $user->phone ?? 'Not provided' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium">Department:</td>
                                                    <td>{{ $user->department ?? 'Not specified' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium">Level:</td>
                                                    <td>{{ $user->level ? $user->level . ' Level' : 'Not specified' }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-medium">Member Since:</td>
                                                    <td>{{ $user->created_at->format('M j, Y') }}</td>
                                                </tr>
                                                @if($user->last_login_at)
                                                <tr>
                                                    <td class="fw-medium">Last Login:</td>
                                                    <td>{{ $user->last_login_at->format('M j, Y g:i A') }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Form -->
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Avatar Upload Section -->
                                    <div class="mb-4">
                                        <h5 class="fs-15 mb-3">Profile Photo</h5>
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <div class="avatar-preview">
                                                    @if($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                             alt="Profile Preview" class="rounded-circle" 
                                                             width="60" height="60" style="object-fit: cover;" 
                                                             id="avatar-preview">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                             style="width: 60px; height: 60px;" id="avatar-preview">
                                                            <span class="text-white fs-4 fw-bold">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col">
                                                <input type="file" name="avatar" id="avatar-upload" 
                                                       class="form-control @error('avatar') is-invalid @enderror" 
                                                       accept="image/*" style="display: none;">
                                                
                                                <button type="button" class="btn btn-outline-primary btn-sm me-2" 
                                                        onclick="document.getElementById('avatar-upload').click()">
                                                    <i class="mdi mdi-upload me-1"></i>
                                                    Choose Photo
                                                </button>
                                                
                                                @if($user->avatar)
                                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                                            onclick="removeAvatar()">
                                                        <i class="mdi mdi-delete me-1"></i>
                                                        Remove
                                                    </button>
                                                    <input type="hidden" name="remove_avatar" id="remove-avatar" value="0">
                                                @endif
                                                
                                                <div class="form-text">
                                                    Upload JPG, PNG or GIF. Max size: 2MB
                                                </div>
                                                
                                                @error('avatar')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" id="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       required placeholder="Enter your full name"
                                                       value="{{ old('name', $user->name) }}">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="email" id="email"
                                                       class="form-control @error('email') is-invalid @enderror"
                                                       required placeholder="Enter your email"
                                                       value="{{ old('email', $user->email) }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="phone" class="form-label">Phone Number</label>
                                                <input type="text" name="phone" id="phone"
                                                       class="form-control @error('phone') is-invalid @enderror"
                                                       placeholder="Enter your phone number"
                                                       value="{{ old('phone', $user->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="department" class="form-label">Department</label>
                                                <select name="department" id="department"
                                                        class="form-control @error('department') is-invalid @enderror">
                                                    <option value="">Select Department</option>
                                                    <option value="Computer Science" {{ old('department', $user->department) == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                                    <option value="Mathematics" {{ old('department', $user->department) == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                                                    <option value="Physics" {{ old('department', $user->department) == 'Physics' ? 'selected' : '' }}>Physics</option>
                                                    <option value="Chemistry" {{ old('department', $user->department) == 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
                                                    <option value="Biology" {{ old('department', $user->department) == 'Biology' ? 'selected' : '' }}>Biology</option>
                                                    <option value="English" {{ old('department', $user->department) == 'English' ? 'selected' : '' }}>English</option>
                                                    <option value="History" {{ old('department', $user->department) == 'History' ? 'selected' : '' }}>History</option>
                                                    <option value="Economics" {{ old('department', $user->department) == 'Economics' ? 'selected' : '' }}>Economics</option>
                                                    <option value="Political Science" {{ old('department', $user->department) == 'Political Science' ? 'selected' : '' }}>Political Science</option>
                                                    <option value="Psychology" {{ old('department', $user->department) == 'Psychology' ? 'selected' : '' }}>Psychology</option>
                                                    <option value="Other" {{ old('department', $user->department) == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('department')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="level" class="form-label">Level</label>
                                                <select name="level" id="level"
                                                        class="form-control @error('level') is-invalid @enderror">
                                                    <option value="">Select Level</option>
                                                    <option value="100" {{ old('level', $user->level) == '100' ? 'selected' : '' }}>100 Level</option>
                                                    <option value="200" {{ old('level', $user->level) == '200' ? 'selected' : '' }}>200 Level</option>
                                                    <option value="300" {{ old('level', $user->level) == '300' ? 'selected' : '' }}>300 Level</option>
                                                    <option value="400" {{ old('level', $user->level) == '400' ? 'selected' : '' }}>400 Level</option>
                                                    <option value="500" {{ old('level', $user->level) == '500' ? 'selected' : '' }}>500 Level</option>
                                                </select>
                                                @error('level')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="fs-15 mb-3">Change Password (Optional)</h5>
                                    <p class="text-muted mb-3">Leave blank if you don't want to change your password</p>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="current_password" class="form-label">Current Password</label>
                                                <input type="password" name="current_password" id="current_password"
                                                       class="form-control @error('current_password') is-invalid @enderror"
                                                       placeholder="Enter current password">
                                                @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">New Password</label>
                                                <input type="password" name="password" id="password"
                                                       class="form-control @error('password') is-invalid @enderror"
                                                       placeholder="Enter new password">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                                <input type="password" name="password_confirmation" id="password_confirmation"
                                                       class="form-control" placeholder="Confirm new password">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-content-save me-1"></i>
                                            Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- container-xxl -->

        </div> <!-- content -->
    </div> <!-- content-page -->
</div> <!-- container-fluid -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarUpload = document.getElementById('avatar-upload');
    const avatarPreview = document.getElementById('avatar-preview');
    const currentAvatar = document.getElementById('current-avatar');
    const avatarPlaceholder = document.getElementById('avatar-placeholder');
    
    // Handle file selection
    avatarUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please select a valid image file.');
                return;
            }
            
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB.');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update preview in form
                avatarPreview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="rounded-circle" width="60" height="60" style="object-fit: cover;">`;
                
                // Update main avatar display
                if (currentAvatar) {
                    currentAvatar.src = e.target.result;
                } else if (avatarPlaceholder) {
                    avatarPlaceholder.innerHTML = `<img src="${e.target.result}" alt="Profile" class="rounded-circle" width="100" height="100" style="object-fit: cover;">`;
                }
            };
            reader.readAsDataURL(file);
        }
    });
});

function removeAvatar() {
    if (confirm('Are you sure you want to remove your profile photo?')) {
        document.getElementById('remove-avatar').value = '1';
        
        // Reset preview
        const avatarPreview = document.getElementById('avatar-preview');
        const userName = '{{ $user->name }}';
        const userInitial = userName.charAt(0).toUpperCase();
        
        avatarPreview.innerHTML = `
            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                 style="width: 60px; height: 60px;">
                <span class="text-white fs-4 fw-bold">${userInitial}</span>
            </div>
        `;
        
        // Reset main avatar
        const currentAvatar = document.getElementById('current-avatar');
        const avatarPlaceholder = document.getElementById('avatar-placeholder');
        
        if (currentAvatar) {
            currentAvatar.style.display = 'none';
        }
        
        if (avatarPlaceholder) {
            avatarPlaceholder.innerHTML = `<span class="text-white fs-1 fw-bold">${userInitial}</span>`;
            avatarPlaceholder.style.display = 'inline-flex';
        }
        
        // Clear file input
        document.getElementById('avatar-upload').value = '';
    }
}
</script>
@endsection