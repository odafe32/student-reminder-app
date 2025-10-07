<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function showDashboard()
    {
        $viewData = [
           'meta_title'=> 'Student Dashboard | Student Reminder System',
           'meta_desc'=> 'Student Reminder System',
           'meta_image'=> url('logo.png'),
        ];

        return view('student.dashboard', $viewData);
    }
}
