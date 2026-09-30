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
        // Keep the task ID before deleting the model
        $taskId = $task->id;
        
        // Capture the data BEFORE deleting the task
        $taskData = $task->toArray();
        $userEmail = $task->user->email;

        // Delete physical files from local storage if present
        if (!empty($task->filename) && is_array($task->filename)) {
            foreach ($task->filename as $file) {
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        // Delete from local SQLite database
        $task->delete();

        // Delete from Firestore
        $this->firestore
            ->database()
            ->collection('tasks')
            ->document((string) $taskId)
            ->delete();

        // Broadcast deletion to other connected users
        broadcast(
            new TaskDeleted($taskId, $taskData, $userEmail)
        )->toOthers();
    }
}
