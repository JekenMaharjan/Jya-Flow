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
        if (! empty($data['due_at'])) {
            $data['due_at'] = Carbon::parse($data['due_at'], 'Asia/Kathmandu')
            ->setTimezone('UTC');
        }

        // Process multiple file uploads
        $uploadedFiles = [];
        foreach ($files as $file) {
            $uploadedFiles[] = $file->store('uploads', 'public');
        }

        // Set uploaded files into filename
        $data['filename'] = $uploadedFiles;

        // Convert multiple collaborator emails into a single string
        if (!empty($data['collaborator_email'])) {
            $data['collaborator_email'] = implode(',', $data['collaborator_email']);
        }

        // Save task to local SQLite db via Eloquent
        $task = $user->tasks()->create($data);

        // Save task document to Firestore
        $database = $this->firestore->database();

        $database->collection('tasks')->document((string) $task->id)->set([
            'id' => $task->id,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'filename' => $data['filename'] ?? null,
            'priority' => $data['priority'] ?? 'low',
            'status' => $data['status'] ?? 'in_progress',
            'due_at' => isset($data['due_at']) ? $data['due_at']->toIso8601String() : null,
            'collaborator_email' => $data['collaborator_email'] ?? null,
            'last_updated_by' => $data['last_updated_by'] ?? null,
            'created_at' => now()->toIso8601String(),
            'due_soon_alert_sent' => $data['due_soon_alert_sent'] ?? null,
        ]);

        // Dispatch Event
        TaskCreated::dispatch($task);

        return $task;
    }
}
