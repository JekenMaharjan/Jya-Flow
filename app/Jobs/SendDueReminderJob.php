<?php

namespace App\Jobs;

use App\Enums\TaskStatus;
use App\Mail\TaskDueDateSoonMail;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDueReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        // Checks if the user exists/has an email
        if (!$this->task->user?->email) {
            return;
        }

        // Send the email
        Mail::to($this->task->user->email)->send(new TaskDueDateSoonMail($this->task));
        
        // Log message with information
        Log::info("Due reminder email sent for task #{$this->task->id} to {$this->task->user->email}");
        
        // Mark task due reminder sent so it doesn't get sent again
        $this->task->update([
            'due_soon_alert_sent' => true
        ]);
    }
}
