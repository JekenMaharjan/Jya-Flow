<?php

namespace App\Jobs;

use App\Enums\TaskStatus;
use App\Mail\TaskDueDateSoonMail;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDueReminderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->task->user && $this->task->user->email) {
            
            // Mark as sent first to prevent race condition duplicates
            $this->task->update([
                'due_soon_alert_sent' => true
            ]);

            Mail::to($this->task->user->email)->queue(new TaskDueDateSoonMail($this->task));
            
            Log::info("Due reminder email sent for task #{$this->task->id} to {$this->task->user->email}");
        }
    }
}
