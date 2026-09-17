<?php

namespace App\Actions\Task;

use App\Events\TaskDeleted;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Contract\Firestore as ContractFirestore;
use Lorisleiva\Actions\Concerns\AsAction;
use Throwable;

class DeleteTaskAction
{
    use AsAction;

    public function __construct(protected ContractFirestore $firestore)
    {
        //
    }

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

        // Delete task document from Firestore (Real-time sync)
        try {
            $database = $this->firestore->database();

            $documentId = (string) $task->id;

            $database->collection('tasks')->document($documentId)->delete();
        } catch (Throwable $e) {
            // Log error so local db deletion proceeds if Firestore fails
            logger()->error("Failed to delete task from Firestore: " . $e->getMessage());
        }

        // Capture task attributes as an array BEFORE deletion for queued mail
        $taskData = $task->toArray();
        $userEmail = $task->user->email;

        // Delete record from database
        $task->delete();

        TaskDeleted::dispatch($taskData, $userEmail);
    }
}
