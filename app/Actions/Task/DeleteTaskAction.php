<?php

namespace App\Actions\Task;

use App\Mail\TaskDeletedMail;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTaskAction
{
    use AsAction;

    public function handle(Task $task): void
    {
        // Delete associated physical files from public disk    
        if ($task->filename) {
            // Ensure array handling for filenames
            $files = is_array($task->filename) ? $task->filename : [$task->filename];
            
            foreach ($files as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        // Capture task attributes as an array BEFORE deletion for queued mail
        $taskData = $task->toArray();
        $userEmail = $task->user->email;

        // Delete record from database
        $task->delete();

        // Dispatch the queue mail with the plain array
        Mail::to($userEmail)->queue(new TaskDeletedMail($taskData));
    }
}
