<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        $viewData = [
           'meta_title'=> 'Login | Nsuk Document Management System',
           'meta_desc'=> 'Departmental Information Management System with document management and announcements',
           'meta_image'=> url('assets/images/favicon.ico'),
        ];

        return view('auth.login', $viewData);
    }

    public function showRegister()
    {
        $viewData = [
           'meta_title'=> 'Register | Nsuk Document Management System',
           'meta_desc'=> 'Departmental Information Management System with document management and announcements',
           'meta_image'=> url('assets/images/favicon.ico'),
        ];

        return view('auth.register', $viewData);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
           
            'phone'     => ['nullable', 'string', 'max:50'],
            'department'=> ['nullable', 'string', 'max:255'],
            'level'     => ['nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            
            'phone'         => $validated['phone'] ?? null,
            'department'    => $validated['department'] ?? null,
            'level'         => $validated['level'] ?? null,
            'status'        => 'active',
        ]);

        Auth::login($user);

        return redirect()->intended($user->getDashboardRoute());
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
            'remember' => 'nullable',
        ]);

        $credentials = $request->only('email', 'password');

        // Check if user exists and is active
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => ['Your account is not active. Please contact administrator.'],
            ]);
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Update last login time
            $user->forceFill(['last_login_at' => now()])->save();

            // Redirect based on user role
            return redirect()->intended($user->getDashboardRoute());
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function showForgotPassword()
    {
        $viewData = [
           'meta_title'=> 'Forgot password | Nsuk Document Management System',
           'meta_desc'=> 'Departmental Information Management System with document management and announcements',
           'meta_image'=> url('assets/images/favicon.ico'),
        ];

        return view('auth.forgot', $viewData);
    }
}
