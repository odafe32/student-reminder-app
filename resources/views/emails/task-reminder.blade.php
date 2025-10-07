<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px 20px;
            border-radius: 0 0 10px 10px;
        }
        .task-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        .priority-high { border-left: 4px solid #dc3545; }
        .priority-medium { border-left: 4px solid #ffc107; }
        .priority-low { border-left: 4px solid #28a745; }
        .task-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        .task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin: 15px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 12px;
        }
        @media (max-width: 600px) {
            .task-meta { flex-direction: column; gap: 8px; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Task Reminder</h1>
        <p>Don't forget about your upcoming task!</p>
    </div>

    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>This is a friendly reminder about your upcoming task:</p>

        <div class="task-card priority-{{ $task->priority }}">
            <div class="task-title">{{ $task->title }}</div>
            
            @if($task->description)
                <p>{{ $task->description }}</p>
            @endif

            <div class="task-meta">
                <div class="meta-item">
                    <strong>📂 Category:</strong> {{ ucfirst($task->category) }}
                </div>
                
                <div class="meta-item">
                    <strong>⚡ Priority:</strong> 
                    <span style="color: {{ $task->priority === 'high' ? '#dc3545' : ($task->priority === 'medium' ? '#ffc107' : '#28a745') }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </div>
                
                @if($task->due_date)
                    <div class="meta-item">
                        <strong>📅 Due Date:</strong> {{ $task->due_date->format('M j, Y') }}
                    </div>
                @endif
                
                @if($task->reminder_time)
                    <div class="meta-item">
                        <strong>⏰ Time:</strong> {{ $task->reminder_time->format('g:i A') }}
                    </div>
                @endif
            </div>

            @if($task->attachment)
                <div class="meta-item">
                    <strong>📎 Attachment:</strong> File attached to this task
                </div>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ route('student.tasks') }}" class="btn">View All Tasks</a>
        </div>

        <p>Stay organized and keep up the great work!</p>
        
        <p>Best regards,<br>
        <strong>Hando Student Reminder System</strong></p>
    </div>

    <div class="footer">
        <p>This is an automated reminder from Hando Student Reminder System.</p>
        <p>If you no longer wish to receive these reminders, you can update your notification preferences in your profile.</p>
    </div>
</body>
</html>