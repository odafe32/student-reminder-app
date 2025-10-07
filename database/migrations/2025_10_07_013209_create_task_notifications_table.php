<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['email', 'sms', 'in_app']); // notification type
            $table->enum('status', ['pending', 'sent', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('scheduled_at'); // when to send
            $table->timestamp('sent_at')->nullable(); // when actually sent
            $table->text('message')->nullable(); // notification content
            $table->json('metadata')->nullable(); // extra data (error messages, etc.)
            $table->timestamps();

            // Indexes for performance
            $table->index(['status', 'scheduled_at']);
            $table->index(['task_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_notifications');
    }
};