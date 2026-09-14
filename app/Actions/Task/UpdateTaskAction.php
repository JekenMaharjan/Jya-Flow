<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTaskAction
{
    use AsAction;

    public function handle(Task $task, array $data, array $files = []):Task
    {
        // Convert Nepal time (NPT) into UTC if due_at is provided
        if (! empty($data['due_at'])) {
            $data['due_at'] = Carbon::parse($data['due_at'], 'Asia/Kathmandu')
            ->setTimezone('UTC');
        }

        // Handle file replacement if new files were uploaded
        if (! empty($task->filename) && is_array($task->filename)) {
            // Delete old physical files from public storage
            if (is_array($task->filename)) {
                foreach ($task->filename as $oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }

            // Store new files
            $uploadedFiles = [];
            foreach ($files as $file) {
                $uploadedFiles[] = $file->store('uploads', 'public');
            }

            $data['filename'] = $uploadedFiles;
        }

        // Fill model attributes to check dirty state before saving
        $task->fill($data);

        // If due date was changed, reset the reminder alert state
        if ($task->isDirty('due_at')) {
            $task->due_soon_alert_sent = false;
        }

        // Save updates to database
        $task->save();

        return $task;
    }
}
