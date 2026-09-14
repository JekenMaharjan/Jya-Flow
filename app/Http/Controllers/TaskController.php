<?php

namespace App\Http\Controllers;

use App\Actions\Task\CreateTaskAction;
use App\Actions\Task\ShowTaskAction;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Mail\TaskDeletedMail;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    // POST: Create Task
    public function store(StoreTaskRequest $request)
    {
        // Run action to create task, upload files & queue email
        $task = CreateTaskAction::run(
            user: $request->user(),
            data: $request->validated(),
            files: $request->file('files', [])
        );

        return back()
            ->with('success', 'Task created & Notification sent successfully!');
    }

    // ===============================================================

    // GET: Retrieve all tasks with filter tasks
    public function index(Request $request)
    {
        // Run action to retrieve taskgis with filters
        $result = ShowTaskAction::run(
            user: $request->user(),
            filters: $request->only(['status', 'priority'])
        );

        return view('tasks', [
            'tasks'                 => $result['tasks'],
            'totalTasksCount'       => $result['counts']['total'],
            'inProgressTasksCount'  => $result['counts']['in_progress'],
            'completedTasksCount'   => $result['counts']['completed'],
            'lowTasksCount'         => $result['counts']['low'],
            'mediumTasksCount'      => $result['counts']['medium'],
            'highTasksCount'        => $result['counts']['high'],
        ]);
    }

    // ===============================================================

    // GET: Preview Task
    public function preview(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized Access.'], 403);
        }

        return response()->json([
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'filename' => $task->filename,
            'due_at' => $task->due_at,
            'priority' => $task->priority,
            'status' => $task->status,
        ]);
    }

    // ===============================================================

    // PUT: Change Task details
    public function change(UpdateTaskRequest $request, Task $task)
    {
        // Validate incoming request
        $data = $request->validated();

        // Create empty array to hold all the uploaded files
        $updateUploadedFiles = [];

        if ($request->hasFile('files')) {
            // Foreach loop through each file and Delete previous file if exists
            foreach ($task->filename as $file) {
                Storage::disk('public')->delete($file);
            }

            // Foreach loop through each file
            foreach ($request->file('files') as $file) {
                $updateUploadedFiles[] = $file->store('uploads', 'public');
            }

            // Store new file
            $data['filename'] = $updateUploadedFiles;
        }

        if ($task->isDirty('due_at')) {
            $task->due_soon_alert_sent = false;
        }

        // Update task details using 'update' method
        $task->update($data);
            
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task updated successfully!',
                'updated_task' => $task
            ]);
        }

        // Return back to the same page
        return back()->with('success', 'Task updated successfully!');
    }

    // ===============================================================

    // PATCH: Update Task
    public function update(Task $task)
    {
        if ($task['status'] === TaskStatus::COMPLETED) {
            return back()->with('info', 'Status is completed, action skipped.');
        }

        // Eloquent only updates the 'status' column in the database
        $task->update(['status' => TaskStatus::COMPLETED]);
        
        return back()->with('success', "Status updated to Completed.");
    }

    // ===============================================================

    // DELETE: Delete Task
    public function destroy(Request $request, Task $task)
    {
        if ($task->filename) {
            // Ensure array handling for filenames
            $files = is_array($task->filename) ? $task->filename : [$task->filename];
            foreach ($files as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        // Capture task attributes as an array BEFORE deletion
        $taskData = $task->toArray();

        // Delete the task record
        $task->delete();

        // Dispatch the queue mail with the plain array
        Mail::to($request->user()->email)->queue(new TaskDeletedMail($taskData));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task deleted successfully!',
                'deleted_task' => $taskData
            ]);
        }

        return back()->with('success', 'Task deleted successfully.');
    }
}
