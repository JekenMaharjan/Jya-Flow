<?php

namespace App\Actions\Task;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Contract\Firestore as ContractFirestore;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTaskAction
{
    use AsAction;

    public function __construct(protected ContractFirestore $firestore)
    {
        //
    }

    public function handle(Task $task, array $data, array $files = []): Task
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
                    if (Storage::disk('public')->exists($oldFile)){
                        Storage::disk('public')->delete($oldFile);
                    }
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

        // Update task document to Firestore
        $this->firestore->database()
            ->collection('tasks')
            ->document((string) $task->id)
            ->set([
                'title'               => $task->title,
                'description'         => $task->description,
                'filename'            => $task->filename,
                'priority'            => $task->priority?->value ?? $task->priority,
                'status'              => $task->status?->value ?? $task->status,
                'due_at'              => $task->due_at?->toIso8601String(),
                'collaborator_email'  => $task->collaborator_email,
                'last_updated_by'     => $task->last_updated_by,
                'updated_at'          => now()->toIso8601String(),
                'due_soon_alert_sent' => $task->due_soon_alert_sent,
            ], ['merge' => true]);

        return $task;
    }
}
