@extends('layouts.student')

@section('content')

                @endsection

<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
    }

    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 2px 4px;
    }

    .fc-event-completed {
        opacity: 0.6;
        text-decoration: line-through;
    }

    .fc-daygrid-event {
        white-space: normal !important;
        align-items: normal !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Prepare events from tasks
    var events = [
        @foreach($tasks as $task)
        {
            id: '{{ $task->id }}',
            title: '{{ addslashes($task->title) }}',
            start: '{{ $task->due_date->format('Y-m-d\TH:i:s') }}',
            description: '{{ addslashes($task->description ?? '') }}',
            completed: {{ $task->completed ? 'true' : 'false' }},
            backgroundColor: '{{ $task->completed ? '#6b7280' : '#3b82f6' }}',
            borderColor: '{{ $task->completed ? '#6b7280' : '#3b82f6' }}',
            classNames: {{ $task->completed ? "['fc-event-completed']" : '[]' }}
        },
        @endforeach
    ];

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: events,
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            showTaskDetails(info.event);
        },
        dateClick: function(info) {
            // Pre-fill the add task modal with clicked date
            var clickedDate = new Date(info.dateStr);
            clickedDate.setHours(12, 0); // Set to noon
            document.getElementById('due_date').value = formatDateTimeLocal(clickedDate);
            new bootstrap.Modal(document.getElementById('addTaskModal')).show();
        },
        height: 'auto',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: 'short'
        }
    });

    calendar.render();

    // Set minimum date to now for new tasks
    document.getElementById('due_date').min = new Date().toISOString().slice(0, 16);
});

function showTaskDetails(event) {
    document.getElementById('view_title').textContent = event.title;
    document.getElementById('view_due_date').textContent = new Date(event.start).toLocaleString();
    document.getElementById('view_description').textContent = event.extendedProps.description || 'No description';
    
    // Status badge
    var statusHtml = event.extendedProps.completed 
        ? '<span class="badge bg-success"><i class="mdi mdi-check-circle me-1"></i>Completed</span>'
        : '<span class="badge bg-warning"><i class="mdi mdi-clock me-1"></i>Pending</span>';
    document.getElementById('view_status').innerHTML = statusHtml;
    
    // Set up edit button
    document.getElementById('editFromViewBtn').onclick = function() {
        bootstrap.Modal.getInstance(document.getElementById('viewTaskModal')).hide();
        editTask(event.id, event.title, event.extendedProps.description, event.start, event.extendedProps.completed);
    };
    
    // Set up delete form
    document.getElementById('deleteFromViewForm').action = '/student/tasks/' + event.id;
    
    new bootstrap.Modal(document.getElementById('viewTaskModal')).show();
}

function editTask(id, title, description, dueDate, completed) {
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_due_date').value = formatDateTimeLocal(new Date(dueDate));
    document.getElementById('edit_completed').checked = completed;
    document.getElementById('editTaskForm').action = '/student/tasks/' + id;
    
    new bootstrap.Modal(document.getElementById('editTaskModal')).show();
}

function formatDateTimeLocal(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
}
</script>