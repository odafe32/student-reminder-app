<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();

        $taskTemplates = [
            // Assignments
            [
                'title' => 'Complete Mathematics Assignment Chapter 5',
                'description' => 'Solve problems 1-20 from Chapter 5: Calculus and Derivatives. Show all working steps.',
                'category' => 'assignment',
                'priority' => 'high',
                'status' => 'pending',
                'days_from_now' => 3,
                'email_notification' => true,
                'in_app_notification' => true,
            ],
            [
                'title' => 'Physics Lab Report - Pendulum Experiment',
                'description' => 'Write a comprehensive lab report on the simple pendulum experiment conducted last week.',
                'category' => 'assignment',
                'priority' => 'medium',
                'status' => 'in_progress',
                'days_from_now' => 5,
                'email_notification' => true,
            ],
            [
                'title' => 'English Literature Essay - Shakespeare Analysis',
                'description' => 'Write a 2000-word essay analyzing the themes in Hamlet. Focus on revenge and madness.',
                'category' => 'assignment',
                'priority' => 'high',
                'status' => 'pending',
                'days_from_now' => 7,
                'email_notification' => true,
                'sms_notification' => true,
            ],

            // Exams
            [
                'title' => 'Midterm Exam - Computer Science',
                'description' => 'Covers chapters 1-8: Data Structures, Algorithms, and Object-Oriented Programming.',
                'category' => 'exam',
                'priority' => 'high',
                'status' => 'pending',
                'days_from_now' => 10,
                'email_notification' => true,
                'sms_notification' => true,
                'in_app_notification' => true,
            ],
            [
                'title' => 'Chemistry Final Exam',
                'description' => 'Comprehensive exam covering organic and inorganic chemistry topics.',
                'category' => 'exam',
                'priority' => 'high',
                'status' => 'pending',
                'days_from_now' => 21,
                'email_notification' => true,
                'sms_notification' => true,
            ],

            // Meetings
            [
                'title' => 'Group Project Meeting - Software Development',
                'description' => 'Discuss project progress, assign remaining tasks, and plan final presentation.',
                'category' => 'meeting',
                'priority' => 'medium',
                'status' => 'pending',
                'days_from_now' => 2,
                'in_app_notification' => true,
            ],
            [
                'title' => 'Academic Advisor Meeting',
                'description' => 'Discuss course selection for next semester and career planning.',
                'category' => 'meeting',
                'priority' => 'medium',
                'status' => 'pending',
                'days_from_now' => 4,
                'email_notification' => true,
            ],

            // Personal Tasks
            [
                'title' => 'Library Book Return',
                'description' => 'Return borrowed books: "Advanced Algorithms" and "Database Systems".',
                'category' => 'personal',
                'priority' => 'low',
                'status' => 'overdue',
                'days_from_now' => -2, // Overdue
                'in_app_notification' => true,
            ],
            [
                'title' => 'Update Resume',
                'description' => 'Add recent projects and internship experience to resume for job applications.',
                'category' => 'personal',
                'priority' => 'medium',
                'status' => 'pending',
                'days_from_now' => 6,
            ],

            // Events
            [
                'title' => 'Tech Conference - AI & Machine Learning',
                'description' => 'Attend the annual tech conference focusing on AI trends and machine learning applications.',
                'category' => 'event',
                'priority' => 'medium',
                'status' => 'pending',
                'days_from_now' => 14,
                'email_notification' => true,
            ],
            [
                'title' => 'Career Fair',
                'description' => 'University career fair with tech companies. Bring updated resume and dress professionally.',
                'category' => 'event',
                'priority' => 'high',
                'status' => 'pending',
                'days_from_now' => 8,
                'email_notification' => true,
                'sms_notification' => true,
            ],

            // Completed Tasks
            [
                'title' => 'Submit Course Registration',
                'description' => 'Complete online course registration for next semester.',
                'category' => 'personal',
                'priority' => 'high',
                'status' => 'completed',
                'days_from_now' => -5,
                'completed' => true,
            ],
            [
                'title' => 'Statistics Assignment - Data Analysis',
                'description' => 'Analyze the provided dataset and create visualizations using R or Python.',
                'category' => 'assignment',
                'priority' => 'medium',
                'status' => 'completed',
                'days_from_now' => -3,
                'completed' => true,
            ],
        ];

        foreach ($students as $student) {
            // Create a random selection of tasks for each student
            $selectedTasks = collect($taskTemplates)->random(rand(8, 12));
            
            foreach ($selectedTasks as $template) {
                $dueDate = Carbon::now()->addDays($template['days_from_now']);
                $startDate = $dueDate->copy()->subDays(rand(1, 5));
                
                $taskData = [
                    'user_id' => $student->id,
                    'title' => $template['title'],
                    'description' => $template['description'],
                    'category' => $template['category'],
                    'start_date' => $startDate,
                    'due_date' => $dueDate,
                    'reminder_time' => sprintf('%02d:%02d', rand(8, 18), rand(0, 59)),
                    'repeat_frequency' => collect(['none', 'none', 'none', 'weekly', 'monthly'])->random(),
                    'priority' => $template['priority'],
                    'status' => $template['status'],
                    'email_notification' => $template['email_notification'] ?? false,
                    'sms_notification' => $template['sms_notification'] ?? false,
                    'in_app_notification' => $template['in_app_notification'] ?? true,
                ];

                // Set completed_at for completed tasks
                if (isset($template['completed']) && $template['completed']) {
                    $taskData['completed_at'] = $dueDate->copy()->subDays(rand(0, 2));
                }

                Task::create($taskData);
            }

            // Add some recurring tasks
            Task::create([
                'user_id' => $student->id,
                'title' => 'Weekly Study Group - Mathematics',
                'description' => 'Weekly study group session to review mathematics concepts and solve practice problems.',
                'category' => 'meeting',
                'start_date' => Carbon::now()->next('Wednesday'),
                'due_date' => Carbon::now()->next('Wednesday'),
                'reminder_time' => '14:00',
                'repeat_frequency' => 'weekly',
                'priority' => 'medium',
                'status' => 'pending',
                'in_app_notification' => true,
            ]);

            Task::create([
                'user_id' => $student->id,
                'title' => 'Monthly Budget Review',
                'description' => 'Review monthly expenses and update budget plan.',
                'category' => 'personal',
                'start_date' => Carbon::now()->startOfMonth()->addMonth(),
                'due_date' => Carbon::now()->startOfMonth()->addMonth(),
                'reminder_time' => '10:00',
                'repeat_frequency' => 'monthly',
                'priority' => 'low',
                'status' => 'pending',
                'email_notification' => true,
            ]);
        }
    }
}