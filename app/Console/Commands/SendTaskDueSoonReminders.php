<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Mail\TaskDueDateSoonMail;
use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTaskDueSoonReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tasks:send-due-soon-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send email reminders for tasks due in 24 hours';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Define the 24-hour target window (22h to 25h from now)
        // Timezome mismatch issue coz laravel default uses UTC timezone, change it to our country timezone
        $startWindow = now();
        // $startWindow = now()->addHours(22);
        $endWindow   = now()->addHours(25);

        // Fetch uncompleted tasks approaching the 24h deadline
        $tasks = Task::with('user')
            ->where('status', '!=', TaskStatus::COMPLETED->value)
            ->where('due_soon_alert_sent', false)
            ->whereNotNull('due_at')
            ->whereBetween('due_at', [$startWindow, $endWindow])
            ->get();

        if ($tasks->isEmpty()) {
            $this->info('No tasks due in the next 24 hours.');
            return;
        }

        $count = 0;

        foreach ($tasks as $task) {
            $recipientEmail = $task->user?->email;

            if ($recipientEmail) {
                // Mark as sent first to prevent race condition duplicates
                $task->update(['due_soon_alert_sent' => true]);

                // Queue the mailable asynchronously
                Mail::to($recipientEmail)->queue(new TaskDueDateSoonMail($task));

                $this->info("Queued 24h due reminder for Task #{$task->id} to {$recipientEmail}");
                $count++;
            }
        }

        $this->info("Successfully processed {$count} task reminder(s).");
    }
}