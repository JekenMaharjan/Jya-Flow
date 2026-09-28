<?php

namespace App\Http\Controllers;

use App\Actions\Task\CreateTaskAction;
use App\Actions\Task\ShowTaskAction;
use App\Actions\Task\UpdateTaskAction;
use App\Enums\UserRole;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // GET: Retrieve all tasks with filter tasks
    public function index(Request $request)
    {
        // Run action to retrieve tasks with filters
        $result = ShowTaskAction::run(
            user: $request->user(),
            filters: $request->only(['status', 'priority'])
        );

        // Check admin user - enum value directly
        $isAdmin = $request->user()->role === UserRole::ADMIN
            || $request->user()->role === UserRole::ADMIN->value;

        $view = $isAdmin
            ? 'roles.admin.admin_task'
            : 'roles.member.member_task';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "List of Tasks for " . $request->user()->name . " - " . ($request->user()->email),
                'data' => [
                    'tasks' => $result['tasks'],
                    'counts' => $result['counts'],
                ]
            ], 200);
        }

        return view($view, [
            'members'              => User::where('role', UserRole::MEMBER)->get(),
            'tasks'                => $result['tasks'],
            'totalTasksCount'      => $result['counts']['total'],
            'inProgressTasksCount' => $result['counts']['in_progress'],
            'completedTasksCount'  => $result['counts']['completed'],
            'lowTasksCount'        => $result['counts']['low'],
            'mediumTasksCount'     => $result['counts']['medium'],
            'highTasksCount'       => $result['counts']['high'],
        ]);
    }


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

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Tasks created successfully - by ' . $request->user()->name . ' - ' . $request->user()->email,
                'task' => $task,
            ]);
        }

        return back()->with('success', 'Task created successfully!');
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
            user: $request->user(),
            task: $task,
            data: $request->validated(),
            files: $files
        );

        return back()->with('success', 'Task updated successfully!');
    }
}
