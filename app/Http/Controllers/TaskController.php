<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // // GET: Retrieve all tasks
    // public function index(Request $request) 
    // {
    //     // 1. Safety Check: Ensure that a user is authenticated
    //     if (!$request->user()) {
    //         if ($request->wantsJson()) {
    //             return response()->json([
    //                 'message' => 'Unauthenticated user.'
    //             ], 401);
    //         }
    //         return redirect()->route('login');
    //     }

    //     // Total tasks and Completed tasks count
    //     $totalTasksCount = $request->user()->tasks()->count();
    //     $completedTasksCount = $request->user()->tasks()->where('is_completed', true)->count(); 

    //     // 2. Eager load the user relationship to prevent N+1 query issues/problem
    //     $tasks = $request->user()->tasks()->with('user')->latest()->paginate(5);

    //     // Pagination -> (paginate(), simplePaginate() & cursorPaginate())
    //     // $tasks = Task::paginate(5);

    //     // 3. the client sends 'Accept: application/json' (e.g. Postman or API)
    //     if ($request->wantsJson()) {
    //         return response()->json([
    //             'message' => 'Retrieved All Tasks',
    //             'total tasks' => $totalTasksCount,
    //             'completed tasks' => $completedTasksCount,
    //             // 'tasks' => $tasks
    //         ], 200);
    //     }

    //     // 3. Return view of tasks 
    //     return view('tasks', compact('tasks', 'totalTasksCount', 'completedTasksCount'));
    // }
    
    // POST: Create Task
    public function store(StoreTaskRequest $request)
    {
        // Validate input
        $data = $request->validated();

        // Checks request if there's any files or not   
        if ($request->hasFile('file_path')) {
            $file_path = $request->file('file_path')->store('uploads', 'public');
            $data['file_path'] = $file_path;
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
        return back()
            ->with('status', 'Task created successfully!');
    }

    // GET: Retrieve all Tasks with Filter Tasks
    public function index(Request $request)
    {
        // Safety Check: Ensure user is logged in
        if (!$request->user()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated user.'
                ], 401);
            }
            return redirect()->route('login');
        }
        
        // Start query scoped to the logged-in user
        $query = $request->user()->tasks()->with('user');

        // Conditionally filter by 'is_completed' if 'status' query parameter exists
        // if ($request->filled('status')) {
        //     $isCompleted = filter_var($request->status, FILTER_VALIDATE_BOOLEAN);
        //     $query->where('is_completed', $isCompleted);
        // }

        // Get total & completed counts for counts display
        $totalTasksCount = $request->user()->tasks()->count();
        $inProgressTasksCount = $request->user()->tasks()->where('status', 'in_progress')->count();
        $completedTasksCount = $request->user()->tasks()->where('status', 'completed')->count(); 

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
        return view('tasks', compact('tasks', 'totalTasksCount', 'completedTasksCount'));
    }

    // PATCH: Update Task
    public function update(Task $task)
    {
        $task->update([
            'status' => $task->status === 'completed'? 'in_progress' : 'completed'
        ]);
        return back();
    }

    // DELETE: Delete Task
    public function destroy(Task $task)
    {
        $task->delete();
        return back();
    }
}
