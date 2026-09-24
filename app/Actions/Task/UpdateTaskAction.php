<?php

namespace App\Actions\Task;

use App\Models\Task;
use App\Models\User;
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

    public function handle(User $user, Task $task, array $data, array $files = []): Task
    {
        // Convert Nepal time (NPT) to UTC
        if (! empty($data['due_at'])) {
            $data['due_at'] = Carbon::parse($data['due_at'], 'Asia/Kathmandu')->setTimezone('UTC');
        }

        // Handle file upload and old file cleanup
        if (!empty($files)) {
            if (! empty($task->filename) && is_array($task->filename)) {
                foreach ($task->filename as $oldFile) {
                    if (Storage::disk('public')->exists($oldFile)){
                        Storage::disk('public')->delete($oldFile);
                    }
                }
            }
            
            $uploadedFiles = [];
            foreach ($files as $file) {
                $uploadedFiles[] = $file->store('uploads', 'public');
            }
            $data['filename'] = $uploadedFiles;
        }

        // Format collaborator array into a clean comma-separated string
        if (!empty($data['collaborator_email']) && is_array($data['collaborator_email'])) {
            $data['collaborator_email'] = implode(',', array_filter($data['collaborator_email']));
        }

        $data['last_updated_by'] = $user->email;

        // Fill and check dirty states
        $task->fill($data);

        if ($task->isDirty('due_at')) {
            $task->due_soon_alert_sent = false;
        }

        $task->save();

        // Sync to Firebase
        $this->firestore->database()->collection('tasks')->document((string) $task->id)->set([
                'title'                 => $task->title,
                'description'           => $task->description,
                'filename'              => $task->filename,
                'priority'              => $task->priority?->value ?? $task->priority,
                'status'                => $task->status?->value ?? $task->status,
                'due_at'                => $task->due_at?->toIso8601String(),
                'collaborator_email'    => $task->collaborator_email,
                'last_updated_by'       => $task->last_updated_by,
                'updated_at'            => now()->toIso8601String(),
                'due_soon_alert_sent'   => $task->due_soon_alert_sent,
            ], ['merge' => true]);

        return $task;
    }
}
