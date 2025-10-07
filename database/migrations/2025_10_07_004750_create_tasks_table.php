<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            // Link each task to a user (student or admin)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Basic Info
            $table->string('title');
            $table->text('description')->nullable();
            
            // Task Classification
            $table->enum('category', [
                'assignment',
                'exam',
                'meeting',
                'personal',
                'event',
                'others'
            ])->default('others');
            
            // Task Timing
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->time('reminder_time')->nullable();
            
            // Recurring Option (optional)
            $table->enum('repeat_frequency', [
                'none',
                'daily',
                'weekly',
                'monthly',
                'yearly'
            ])->default('none');
            
            // Priority Level
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            
            // Status Tracking
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'overdue',
                'cancelled'
            ])->default('pending');
            
            // Notification Options
            $table->boolean('email_notification')->default(false);
            $table->boolean('sms_notification')->default(false);
            $table->boolean('in_app_notification')->default(true);
            
            // Attachment (optional: files, images, PDFs)
            $table->string('attachment')->nullable();
            
            // Completed timestamp for analytics
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};