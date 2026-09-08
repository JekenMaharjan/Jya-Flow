<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Mail\TaskCompleted;
use App\Mail\TaskCreatedMail;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    // POST: Create Task
    public function store(StoreTaskRequest $request)
    {
        // Validate input data - PHP array
        $data = $request->validated();

        // Create empty array to hold all the uploaded files
        $uploadedFiles = [];

        // Checks request if there's any files or not   
        if ($request->hasFile('files')) {
            // Foreach loop through each file
            foreach ($request->file('files') as $file) {
                $uploadedFiles[] = $file->store('uploads', 'public');
            }
        }

        // Save array of paths into 'filename'
        $data['filename'] = $uploadedFiles;

        // Create task
        $task = $request->user()->tasks()->create($data);

        // Queue the confirmation email to the user who created it
        Mail::to($task->user)->queue(new TaskCreatedMail($task));

        // Return JSON respone
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task created successfully!',
                'task' => $task->load('user', 'files')   // Includes user info and files in JSON response
            ], 201);
        }
        
        // Return back
        return back()->with('success', 'Task created & Notification sent successfully!');
    }

    // ===============================================================

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

        // Get total, in_progress & completed status counts for counts display
        $totalTasksCount = $forTasksCount->count();
        $inProgressTasksCount = $forTasksCount->where('status', TaskStatus::IN_PROGRESS->value ?? 'in_progress')->count();
        $completedTasksCount = $forTasksCount->where('status', TaskStatus::COMPLETED->value ?? 'completed')->count(); 

        // Get low, medium & high priority counts for counts display
        $lowTasksCount = $forTasksCount->where('priority', TaskPriority::LOW->value ?? 'low')->count();
        $mediumTasksCount = $forTasksCount->where('priority', TaskPriority::MEDIUM->value ?? 'medium')->count();
        $highTasksCount = $forTasksCount->where('priority', TaskPriority::HIGH->value ?? 'high')->count();

        // Start query scoped to the logged-in user
        $query = $user->tasks()->with('user');

        // Conditionally apply the status filter if provided (and not 'all')
        $query->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        // Similarly for the priority filter if provided
        $query->when($request->filled('priority') && $request->priority !== 'all', function ($q) use ($request) {
            $q->where('priority', $request->priority);
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
                'low tasks' => $lowTasksCount,
                'medium tasks' => $mediumTasksCount,
                'high tasks' => $highTasksCount,
                'tasks' => $tasks,
            ], 200);
        }

        // Return Filtered Tasks
        return view('tasks', compact('tasks', 'totalTasksCount', 'inProgressTasksCount', 'completedTasksCount', 'lowTasksCount', 'mediumTasksCount', 'highTasksCount'));
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
        // Toggle strictly between the two enum values
        $newStatus = ($task->status === TaskStatus::COMPLETED)
            ? TaskStatus::IN_PROGRESS 
            : TaskStatus::COMPLETED;

        // Eloquent only updates the 'status' column in the database
        $task->update(['status' => $newStatus]);

        return back()->with('success', "Status updated to {$newStatus->label()}");
    }

    // ===============================================================

    // DELETE: Delete Task
    public function destroy(Task $task)
    {
        // Check if the task record in the DB has a file path stored
        if ($task->filename) {
            // Delete the physical file from the disk(public folder)
            Storage::disk('public')->delete($task->filename);
        }

        // Delete the content from the database using 'delete' method
        $task->delete();

        // Return back to the same page
        return back()->with('success', 'Task deleted successfully.');
    }
}
