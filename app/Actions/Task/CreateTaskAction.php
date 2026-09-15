<?php

namespace App\Actions\Task;

use App\Events\TaskCreated;
use App\Mail\TaskCreatedMail;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTaskAction
{
    use AsAction;

    public function handle(User $user, array $data, array $files = []): Task
    {
        // Convert Nepal time (NPT) into UTC if due_at is provided
        if (! empty($data['due_at'])) {
            $data['due_at'] = Carbon::parse($data['due_at'], 'Asia/Kathmandu')
            ->setTimezone('UTC');
        }

        // Process mutiple file uploads
        $uploadedFiles = [];
        foreach ($files as $file) {
            $uploadedFiles[] = $file->store('uploads', 'public');
        }

        $data['filename'] = $uploadedFiles;

        // Create task owned by the authenticated user
        $task = $user->tasks()->create($data);
        // $userEmail = $task->user->email;

        TaskCreated::dispatch($task);

        return $task;
    }
}
