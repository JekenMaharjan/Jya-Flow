<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    // POST: Create Task
    public function store(StoreTaskRequest $request)
    {
        // Validate input
        $data = $request->validated();

        // Checks request if there's any files or not   
        if ($request->hasFile('file_path')) {
            $data['file_path'] = $request->file('file_path')->store('uploads', 'public');
        }

        // // TEST: Check response
        // return response()->json([
        //     'data' => $data,
        //     'file_path' => $file_path,
        // ]);

        // Automatically set user_id via relationship (instead of Task::create)
        $task = $request->user()->tasks()->create($data);
        
        // Return JSON respone
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task created successfully!',
                'task' => $task->load('user')   // Includes user info in JSON response
            ], 201);
        }
        
        // Return back
        return back()->with('success', 'Task created successfully!');
    }

    // GET: Retrieve all Tasks with Filter Tasks
    public function index(Request $request)
    {
        $user = $request->user();
        // Safety Check: Ensure user is logged in
        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated user.'
                ], 401);
            }
            return redirect()->route('login');
        }
        
        // For tasks counts
        $forTasksCount = $user->tasks()->get();

        // Get total, in_progress & completed counts for counts display
        $totalTasksCount = $forTasksCount->count();
        $inProgressTasksCount = $forTasksCount->where('status', TaskStatus::IN_PROGRESS->value ?? 'in_progress')->count();
        $completedTasksCount = $forTasksCount->where('status', TaskStatus::COMPLETED->value ?? 'completed')->count(); 

         // Start query scoped to the logged-in user
        $query = $user->tasks()->with('user');

        // 2. Conditionally apply the status filter if provided (and not 'all')
        $query->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        // Fetch paginated tasks and append query parameters so pagination links preserve filter state
        $tasks = $query->latest()->paginate(5)->withQueryString();

        // JSON Response for API clients
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Filtered Tasks',
                'total tasks' => $totalTasksCount,
                'in_progress tasks' => $inProgressTasksCount,
                'completed tasks' => $completedTasksCount,
                'tasks' => $tasks
            ], 200);
        }

        // Return Filtered Tasks
        return view('tasks', compact('tasks', 'totalTasksCount', 'inProgressTasksCount', 'completedTasksCount'));
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
            'file_path' => $task->file_path,
            'due_at' => $task->due_at,
            'priority' => $task->priority,
            'status' => $task->status,
        ]);
    }

    // PUT: Change Task details
    public function change(StoreTaskRequest $request, Task $task)
    {
        // Validate incoming request
        $data = $request->validated();

        if ($request->hasFile('file_path')) {
            // Delete previous file if exists
            if ($task->file_path) {
                Storage::disk('public')->delete($task->file_path);
            }

            // Store new file
            $data['file_path'] = $request->file('file_path')->store('uploads', 'public');
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

    // PATCH: Update Task
    public function update(Task $task)
    {
        // Toggle strictly between the two enum values
        $newStatus = ($task->status === TaskStatus::COMPLETED)
            ? TaskStatus::IN_PROGRESS 
            : TaskStatus::COMPLETED;

        // Eloquent only updates the 'status' column in the database
        $task->update(['status' => $newStatus]);

        return back()->with('success', "Status updated to {$newStatus->label()}");
    }

    // DELETE: Delete Task
    public function destroy(Task $task)
    {
        // Check if the task record in the DB has a file path stored
        if ($task->file_path) {
            // Delete the physical file from the disk(public folder)
            Storage::disk('public')->delete($task->file_path);
        }

        // Delete the content from the database using 'delete' method
        $task->delete();

        // Return back to the same page
        return back()->with('success', 'Task deleted successfully.');
    }
}
