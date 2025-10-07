<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ $meta_title ?? 'Hando Student Reminder System - Smart Task Management for Students' }}</title>

    <!-- Meta -->
    <meta name="description" content="{{ $meta_desc ?? 'Hando Student Reminder System – Smart task and reminder management for students and administrators. Never miss deadlines, organize assignments, and boost productivity.' }}">
    <meta name="keywords" content="student reminder system, task management, assignment tracker, student productivity, academic planner">
    <meta name="author" content="Hando Student Reminder System" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Hando Student Reminder System" />
    <meta property="og:description" content="Smart task and reminder management for students and administrators" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('assets/images/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ url('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Page CSS -->
    <link href="{{ url('assets/css/welcome.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="mdi mdi-calendar-check me-2"></i>Hando
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonials</a>
                    </li>
                </ul>

                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content text-white">
                        <h1 class="hero-title">
                            Smart Task Management for
                            <span class="text-warning">Students</span>
                        </h1>
                        <p class="hero-subtitle">
                            Never miss another deadline! Organize assignments, set reminders, and boost your academic productivity with our intelligent student reminder system.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ route('register') }}" class="btn btn-primary-custom">
                                <i class="mdi mdi-rocket me-2"></i>Start Free Today
                            </a>
                            <a href="#features" class="btn btn-outline-custom">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <div class="position-relative">
                            <div class="bg-white rounded-4 p-4 shadow-lg" style="max-width: 400px; margin: 0 auto;">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary rounded-circle p-2 me-3">
                                        <i class="mdi mdi-calendar-check text-white fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Assignment Due</h6>
                                        <small class="text-muted">Mathematics Project</small>
                                    </div>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: 75%"></div>
                                </div>
                                <div class="d-flex justify-content-between text-sm">
                                    <span>Progress: 75%</span>
                                    <span class="text-danger">Due: Tomorrow</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-padding">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-3">Powerful Features for Academic Success</h2>
                    <p class="lead text-muted">Everything you need to stay organized and excel in your studies</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="feature-card">
                        <div class="feature-icon task">
                            <i class="mdi mdi-clipboard-list"></i>
                        </div>
                        <h5>Task Management</h5>
                        <p>Create, organize, and track all your assignments, projects, and study tasks in one centralized location.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="feature-card">
                        <div class="feature-icon reminder">
                            <i class="mdi mdi-bell-ring"></i>
                        </div>
                        <h5>Smart Reminders</h5>
                        <p>Get intelligent notifications via email, SMS, and in-app alerts to never miss important deadlines.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="feature-card">
                        <div class="feature-icon collaboration">
                            <i class="mdi mdi-account-group"></i>
                        </div>
                        <h5>Admin Dashboard</h5>
                        <p>Administrators can manage users, monitor progress, and oversee the entire student reminder system.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="feature-card">
                        <div class="feature-icon analytics">
                            <i class="mdi mdi-chart-line"></i>
                        </div>
                        <h5>Progress Analytics</h5>
                        <p>Track your academic progress with detailed analytics and insights to improve your study habits.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section section-padding">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="stats-counter">1,000+</div>
                    <p class="text-muted">Active Students</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stats-counter">50,000+</div>
                    <p class="text-muted">Tasks Completed</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stats-counter">99.9%</div>
                    <p class="text-muted">Uptime</p>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stats-counter">24/7</div>
                    <p class="text-muted">Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Why Choose Hando Student Reminder System?</h2>
                    <p class="lead mb-4">
                        Designed specifically for students and academic environments, our system helps you stay organized,
                        meet deadlines, and achieve academic excellence.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            <strong>User-Friendly Interface:</strong> Intuitive design that students love to use
                        </li>
                        <li class="mb-3">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            <strong>Multi-Platform Access:</strong> Available on web and mobile devices
                        </li>
                        <li class="mb-3">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            <strong>Real-Time Notifications:</strong> Never miss important deadlines again
                        </li>
                        <li class="mb-3">
                            <i class="mdi mdi-check-circle text-success me-2"></i>
                            <strong>Admin Oversight:</strong> Complete management tools for administrators
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="bg-light rounded-4 p-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="text-center p-3 bg-white rounded-3">
                                        <i class="mdi mdi-school fs-2 text-primary mb-2"></i>
                                        <h6>Academic Focus</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-white rounded-3">
                                        <i class="mdi mdi-shield-check fs-2 text-success mb-2"></i>
                                        <h6>Secure & Reliable</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-white rounded-3">
                                        <i class="mdi mdi-lightning-bolt fs-2 text-warning mb-2"></i>
                                        <h6>Fast Performance</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-white rounded-3">
                                        <i class="mdi mdi-head-cog fs-2 text-info mb-2"></i>
                                        <h6>Smart Features</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="section-padding bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-3">What Students Say</h2>
                    <p class="lead text-muted">Join thousands of students who have improved their academic performance</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">SA</div>
                            <div>
                                <h6 class="mb-0">Sarah Ahmed</h6>
                                <small class="text-muted">Computer Science Student</small>
                            </div>
                        </div>
                        <p class="mb-0">
                            "Hando has completely transformed how I manage my assignments. The reminder system ensures I never miss deadlines!"
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">MJ</div>
                            <div>
                                <h6 class="mb-0">Michael Johnson</h6>
                                <small class="text-muted">Engineering Student</small>
                            </div>
                        </div>
                        <p class="mb-0">
                            "The admin dashboard makes it easy for our department to track student progress and provide timely support."
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">AO</div>
                            <div>
                                <h6 class="mb-0">Amara Okafor</h6>
                                <small class="text-muted">Business Admin Student</small>
                            </div>
                        </div>
                        <p class="mb-0">
                            "I love how organized everything is. From project deadlines to study schedules, Hando keeps me on track."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="fw-bold mb-4">Ready to Boost Your Academic Performance?</h2>
                    <p class="lead mb-4">
                        Join thousands of students who are already using Hando to stay organized and achieve better grades.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-primary-custom btn-lg">
                            <i class="mdi mdi-account-plus me-2"></i>Get Started Free
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                            <i class="mdi mdi-login me-2"></i>Login to Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-4">
                        <h5 class="text-white mb-3">
                            <i class="mdi mdi-calendar-check me-2"></i>Hando
                        </h5>
                        <p class="text-light opacity-75">
                            Smart student reminder system designed to help students stay organized,
                            meet deadlines, and achieve academic success.
                        </p>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-white mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-light text-decoration-none opacity-75">Features</a></li>
                        <li><a href="#about" class="text-light text-decoration-none opacity-75">About</a></li>
                        <li><a href="{{ route('login') }}" class="text-light text-decoration-none opacity-75">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-light text-decoration-none opacity-75">Register</a></li>
                    </ul>
                </div>

                <div class="col-lg-4">
                    <h6 class="text-white mb-3">Contact Info</h6>
                    <div class="d-flex align-items-center mb-2">
                        <i class="mdi mdi-email text-secondary me-2"></i>
                        <span class="text-light opacity-75">support@handoms.com</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="mdi mdi-phone text-secondary me-2"></i>
                        <span class="text-light opacity-75">+234-XXX-XXX-XXXX</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-map-marker text-secondary me-2"></i>
                        <span class="text-light opacity-75">Nasarawa State University</span>
                    </div>
                </div>
            </div>

            <hr class="my-4 opacity-25">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light opacity-75 mb-0">
                        © {{ date('Y') }} Hando Student Reminder System. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-light opacity-75 mb-0">
                        Built with <i class="mdi mdi-heart text-danger"></i> for students
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="{{ url('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
</body>
</html>
