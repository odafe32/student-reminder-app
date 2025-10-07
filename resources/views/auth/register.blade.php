@extends('layouts.auth')

@section('content')

<div class="account-page">
    <div class="container-fluid p-0">
        <div class="row align-items-center g-0 px-3 py-3 vh-100">

            <!-- Left side: registration form -->
            <div class="col-xl-5">
                <div class="row">
                    <div class="col-md-9 mx-auto">
                        <div class="card shadow border-0 rounded-4">
                            <div class="card-body p-4">

                                <div class="text-center mb-4">
                                    <div class="auth-brand">
                                        <a class='logo logo-light' href="{{ route('login') }}">
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/logo-light-3.png') }}" alt="Hando Logo" height="40">
                                            </span>
                                        </a>
                                        <a class='logo logo-dark' href="{{ route('login') }}">
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/logo-dark-3.png') }}" alt="Hando Logo" height="40">
                                            </span>
                                        </a>
                                    </div>
                                    <h3 class="text-dark fw-semibold mb-1 mt-3">Create Your Account</h3>
                                    <p class="text-muted fs-14 mb-0">Join <strong>Hando</strong> and stay on top of your schedules and reminders.</p>
                                </div>

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

                                <form method="POST" action="{{ route('register') }}" class="mt-4">
                                    @csrf

                                    <!-- Hidden field to set role as student -->
                                    <input type="hidden" name="role" value="student">

                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label fw-medium">Full Name</label>
                                        <input type="text" name="name" id="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Enter your full name"
                                               value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label fw-medium">Email Address</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Enter your email"
                                               value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="phone" class="form-label fw-medium">Phone Number <span class="text-muted">(Optional)</span></label>
                                        <input type="text" name="phone" id="phone"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               placeholder="Enter your phone number"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password" class="form-label fw-medium">Password</label>
                                        <input type="password" name="password" id="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Create a password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                               class="form-control"  placeholder="Confirm your password">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="department" class="form-label fw-medium">Department <span class="text-muted">(Optional)</span></label>
                                        <select name="department" id="department"
                                                class="form-control @error('department') is-invalid @enderror">
                                            <option value="">Select Department</option>
                                            <option value="Computer Science" {{ old('department') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                            <option value="Mathematics" {{ old('department') == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                                            <option value="Physics" {{ old('department') == 'Physics' ? 'selected' : '' }}>Physics</option>
                                            <option value="Chemistry" {{ old('department') == 'Chemistry' ? 'selected' : '' }}>Chemistry</option>
                                            <option value="Biology" {{ old('department') == 'Biology' ? 'selected' : '' }}>Biology</option>
                                            <option value="English" {{ old('department') == 'English' ? 'selected' : '' }}>English</option>
                                            <option value="History" {{ old('department') == 'History' ? 'selected' : '' }}>History</option>
                                            <option value="Economics" {{ old('department') == 'Economics' ? 'selected' : '' }}>Economics</option>
                                            <option value="Political Science" {{ old('department') == 'Political Science' ? 'selected' : '' }}>Political Science</option>
                                            <option value="Psychology" {{ old('department') == 'Psychology' ? 'selected' : '' }}>Psychology</option>
                                            <option value="Other" {{ old('department') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('department')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="level" class="form-label fw-medium">Level <span class="text-muted">(Optional)</span></label>
                                        <select name="level" id="level"
                                                class="form-control @error('level') is-invalid @enderror">
                                            <option value="">Select Level</option>
                                            <option value="100" {{ old('level') == '100' ? 'selected' : '' }}>100 Level</option>
                                            <option value="200" {{ old('level') == '200' ? 'selected' : '' }}>200 Level</option>
                                            <option value="300" {{ old('level') == '300' ? 'selected' : '' }}>300 Level</option>
                                            <option value="400" {{ old('level') == '400' ? 'selected' : '' }}>400 Level</option>
                                            <option value="500" {{ old('level') == '500' ? 'selected' : '' }}>500 Level</option>
                                        </select>
                                        @error('level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group d-flex mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="checkbox-terms" >
                                            <label class="form-check-label small text-muted" for="checkbox-terms">
                                                I agree to the <a href="#" class="text-primary fw-medium">Terms and Conditions</a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group mb-0">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                Register as Student
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="text-center text-muted mt-4">
                                    <p class="mb-0">Already have an account?
                                        <a href="{{ route('login') }}" class="text-primary fw-medium">Login here</a>
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side splash section -->
            <div class="col-xl-7 d-none d-xl-inline-block">
                <div class="account-page-bg rounded-4 position-relative overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center"
                         style="background-image: url('{{ asset('assets/images/bg-auth.jpg') }}'); filter: brightness(70%);">
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
