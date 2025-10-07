<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Created</title>
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
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .task-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .detail-row {
            margin: 10px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #667eea;
            display: inline-block;
            width: 140px;
        }
        .value {
            color: #333;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-high {
            background: #dc3545;
            color: white;
        }
        .badge-medium {
            background: #ffc107;
            color: #333;
        }
        .badge-low {
            background: #28a745;
            color: white;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 28px;">✅ New Task Created</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Your task has been successfully created</p>
    </div>
    
    <div class="content">
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        
        <p>You have successfully created a new task. Here are the details:</p>
        
        <div class="task-details">
            <h2 style="margin-top: 0; color: #667eea;">{{ $task->title }}</h2>
            
            @if($task->description)
                <div class="detail-row">
                    <span class="label">Description:</span>
                    <span class="value">{{ $task->description }}</span>
                </div>
            @endif
            
            <div class="detail-row">
                <span class="label">Category:</span>
                <span class="value">{{ ucfirst($task->category) }}</span>
            </div>
            
            <div class="detail-row">
                <span class="label">Priority:</span>
                <span class="badge badge-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
            </div>
            
            @if($task->start_date)
                <div class="detail-row">
                    <span class="label">Start Date:</span>
                    <span class="value">{{ $task->start_date->format('l, F j, Y') }}</span>
                </div>
            @endif
            
            @if($task->due_date)
                <div class="detail-row">
                    <span class="label">Due Date:</span>
                    <span class="value">{{ $task->due_date->format('l, F j, Y') }}</span>
                </div>
            @endif
            
            @if($task->reminder_time)
                <div class="detail-row">
                    <span class="label">Reminder Time:</span>
                    <span class="value">{{ $task->reminder_time->format('g:i A') }}</span>
                </div>
            @endif
            
            @if($task->repeat_frequency !== 'none')
                <div class="detail-row">
                    <span class="label">Repeat:</span>
                    <span class="value">{{ ucfirst($task->repeat_frequency) }}</span>
                </div>
            @endif
        </div>
        
        <p style="text-align: center;">
            <a href="{{ url('/student/tasks') }}" class="button">View All Tasks</a>
        </p>
        
        <p style="color: #6c757d; font-size: 14px; margin-top: 30px;">
            💡 <strong>Tip:</strong> You will receive reminder notifications when your task is due based on your notification preferences.
        </p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from Student Reminder System.</p>
        <p>&copy; {{ date('Y') }} Student Reminder System. All rights reserved.</p>
    </div>
</body>
</html>
