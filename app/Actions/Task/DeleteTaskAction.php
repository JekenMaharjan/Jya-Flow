<?php

namespace App\Actions\Task;

use App\Events\TaskDeleted;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Contract\Firestore as ContractFirestore;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteTaskAction
{
    use AsAction;

    public function __construct(protected ContractFirestore $firestore)
    {
        //
    }

    public function handle(Task $task): void
    {
        // Delete physical files from local storage if present
        if (!empty($task->filename) && is_array($task->filename)) {
            foreach ($task->filename as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        // Delete from local SQLite database
        $taskId = $task->id;
        $task->delete();

        // Delete document from Firestore (Triggers real-time sync for other users)
        $this->firestore->database()->collection('tasks')->document((string) $taskId)->delete();

        // Capture task attributes as an array BEFORE deletion for queued mail
        $taskData = $task->toArray();
        $userEmail = $task->user->email;

        // Laravel Broadcasting
        broadcast(new TaskDeleted($taskData, $userEmail))->toOthers();
    }
}
