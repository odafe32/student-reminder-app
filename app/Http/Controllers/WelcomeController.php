<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $viewData = [
           'meta_title' => 'Hando Student Reminder System - Smart Task Management for Students',
           'meta_desc' => 'Hando Student Reminder System – Smart task and reminder management for students and administrators. Never miss deadlines, organize assignments, and boost productivity.',
        ];

        return view('welcome', $viewData);
    }
}
