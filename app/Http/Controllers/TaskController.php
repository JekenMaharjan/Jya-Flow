<?php

namespace App\Http\Controllers;

use App\Actions\Task\CreateTaskAction;
use App\Actions\Task\DeleteTaskAction;
use App\Actions\Task\ShowTaskAction;
use App\Actions\Task\UpdateTaskAction;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // POST: Create Task
    public function store(StoreTaskRequest $request)
    {
        // Safely extract uploaded files array or fallback to an empty array
        $files = $request->hasFile('files') ? $request->file('files') : [];

        // Run action to create task, upload files & queue email
        $task = CreateTaskAction::run(
            user: $request->user(),
            data: $request->validated(),
            files: $files
        );

        return back()
            ->with('success', 'Task created & Notification sent successfully!');
    }

    // GET: Retrieve all tasks with filter tasks
    public function index(Request $request)
    {
        // Run action to retrieve tasks with filters
        $result = ShowTaskAction::run(
            user: $request->user(),
            filters: $request->only(['status', 'priority'])
        );

        return view('roles.member.member_task', [
            'tasks'                 => $result['tasks'],
            'totalTasksCount'       => $result['counts']['total'],
            'inProgressTasksCount'  => $result['counts']['in_progress'],
            'completedTasksCount'   => $result['counts']['completed'],
            'lowTasksCount'         => $result['counts']['low'],
            'mediumTasksCount'      => $result['counts']['medium'],
            'highTasksCount'        => $result['counts']['high'],
        ]);
    }

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

    // PUT: Change Task details
    public function change(UpdateTaskRequest $request, Task $task)
    {
        // Safely extract uploaded files array or fallback to an empty array
        $files = $request->hasFile('files') ? $request->file('files') : [];

        UpdateTaskAction::run(
            task: $task,
            data: $request->validated(),
            files: $files
        );

        return back()->with('success', 'Task updated successfully!');
    }

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

    // DELETE: Delete Task
    public function destroy(Task $task)
    {
        DeleteTaskAction::run($task);

        return back()->with('success', 'Task deleted successfully.');
    }
}
