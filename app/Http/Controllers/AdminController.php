<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function showDashboard()
    {
        $viewData = [
           'meta_title'=> 'Admin Dashboard | Student Reminder System',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
        ];

        return view('admin.dashboard', $viewData);
    }

}
