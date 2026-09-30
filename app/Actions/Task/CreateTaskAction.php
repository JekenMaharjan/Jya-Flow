<?php

namespace App\Actions\Task;

use App\Events\TaskCreated;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Kreait\Firebase\Contract\Firestore as ContractFirestore;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTaskAction
{
    use AsAction;

    // Inject Firestore Contract following dependency injection
    public function __construct(protected ContractFirestore $firestore)
    {
        //
    }

    public function handle(User $user, array $data, array $files = []): Task
    {
        // Convert Nepal time (NPT) into UTC if due_at is provided
        if (!empty($data['due_at'])) {
            $data['due_at'] = Carbon::parse($data['due_at'], 'Asia/Kathmandu')->setTimezone('UTC');
        }

        // Process multiple file uploads
        $uploadedFiles = [];
        foreach ($files as $file) {
            $uploadedFiles[] = $file->store('uploads', 'public');
        }
        $data['filename'] = $uploadedFiles;

        // Format collaborator_email string consistently
        if (!empty($data['collaborator_email']) && is_array($data['collaborator_email'])) {
            $data['collaborator_email'] = implode(',', array_filter($data['collaborator_email']));
        }

        // Create SQLite Eloquent Task
        $task = $user->tasks()->create($data);

        // Sync to Firestore (Triggers real-time sync for other users)
        $this->firestore->database()->collection('tasks')->document((string) $task->id)->set([
            'id'                    => $task->id,
            'user_id'               => $user->id,
            'user_email'            => $user->email,
            'title'                 => $task->title,
            'description'           => $task->description,
            'filename'              => $task->filename,
            'priority'              => $task->priority?->value ?? $task->priority,
            'status'                => $task->status?->value ?? $task->status,
            'due_at'                => $task->due_at?->toIso8601String(),
            'collaborator_email'    => $task->collaborator_email,
            'last_updated_by'       => $user->email,
            'created_at'            => now()->toIso8601String(),
            'updated_at'            => now()->toIso8601String(),
            'due_soon_alert_sent'   => false,
        ]);

        // Broadcast the TaskCreated Event to all other connected users
        broadcast(new TaskCreated($task))->toOthers();

        return $task;
    }
}
