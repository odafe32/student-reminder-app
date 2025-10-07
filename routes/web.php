<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes Group (only accessible when NOT logged in)
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        // Registration Routes
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register');

        // Login Routes
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login');

        // Forgot password
        Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
    });
});

// Authentication Required Routes
Route::middleware(['auth'])->group(function () {
    // Auth Controller Routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });

    // Admin Routes - Requires authentication AND admin role
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'showDashboard')->name('dashboard');
            Route::get('/profile', 'showProfile')->name('profile');
        });
    });

    // Student Routes - Requires authentication AND student role
    Route::middleware(['role:student'])->prefix('student')->name('student.')->group(function () {
        Route::controller(StudentController::class)->group(function () {
            Route::get('/dashboard', 'showDashboard')->name('dashboard');
            Route::get('/profile', 'showProfile')->name('profile');
            Route::post('/profile', 'updateProfile')->name('profile.update');
            
            // Task routes
            Route::get('/calendar', 'showCalendar')->name('calendar');
            Route::get('/tasks', 'showTasks')->name('tasks');
            Route::post('/tasks', 'storeTask')->name('tasks.store');
            Route::put('/tasks/{task}', 'updateTask')->name('tasks.update');
            Route::patch('/tasks/{task}/status', 'updateTaskStatus')->name('tasks.status');
            Route::delete('/tasks/{task}', 'deleteTask')->name('tasks.delete');
        });
    });
});

// Fallback route
Route::fallback(function () {
    return redirect()->route('login');
});