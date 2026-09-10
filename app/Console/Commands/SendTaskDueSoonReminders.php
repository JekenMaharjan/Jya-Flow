<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Jobs\SendDueReminderJob;
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
    protected $description = 'Send email reminders for tasks due in next 3 hours';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Define the 3-hour target window (3hr from now)
        $startWindow = now();
        $endWindow   = now()->addHours(3);

        // Fetch uncompleted tasks approaching the 3h deadline
        $tasks = Task::with('user')
            ->where('status', '!=', TaskStatus::COMPLETED)
            ->where('due_soon_alert_sent', false)
            ->whereNotNull('due_at')
            ->whereBetween('due_at', [$startWindow, $endWindow])
            ->get();

        if ($tasks->isEmpty()) {
            $this->info('No tasks due in the next 3 hours.');
            return;
        }

        $count = 0;

        foreach ($tasks as $task) {
            // Skip if user doesn't exist
            if (!$task->user) {
                continue;
            }

            // Dispatch Job
            SendDueReminderJob::dispatch($task);
            $count++;
        }
        $this->info("Successfully processed {$count} task reminder(s).");
    }
}