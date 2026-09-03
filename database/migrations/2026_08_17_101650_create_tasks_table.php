<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');    // links to users.id automatically 
            
            // Task details
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('filename')->nullable();

            // Scheduling & Status
            $table->dateTime('due_at')->nullable();
            $table->string('priority')->default(TaskPriority::LOW->value);
            $table->string('status')->default(TaskStatus::IN_PROGRESS->value);

            $table->timestamps();
        });
    }

    /**~
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
