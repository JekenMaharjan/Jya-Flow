<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request) 
    {
        // 1. Safety Check: Ensure that a user is authenticated
        if (!$request->user()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated user.'
                ], 401);
            }
            return redirect()->route('login');
        }

        $totalTasksCount = $request->user()->tasks()->count();
        $completedTasksCount = $request->user()->tasks()->where('is_completed', true)->count(); 

        // 2. Eager load the user relationship to prevent N+1 query issues/problem
        $tasks = $request->user()->tasks()->with('user')->latest()->paginate(5);

        // Pagination -> (paginate(), simplePaginate() & cursorPaginate())
        // $tasks = Task::paginate(5);

        // 3. the client sends 'Accept: application/json' (e.g. Postman or API)
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Retrieved All Tasks',
                'total tasks' => $totalTasksCount,
                'completed tasks' => $completedTasksCount,
                // 'tasks' => $tasks
            ], 200);
        }

        // 3. Return view of tasks 
        return view('tasks', compact('tasks'));
    }
    
    public function store(StoreTaskRequest $request)
    {
        // 1. Validate input
        $validatedData = $request->validated();

        // 2. Automatically set user_id via relationship (instead of Task::create)
        $task = $request->user()->tasks()->create($validatedData);

        // 3. Return JSON if API client (Postman/Mobile/Frontend)
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task created successfully!',
                'task' => $task->load('user')   // Includes user info in JSON response
            ], 201); // 201 Created
        }

        // 4. Return back for traditional Blade web forms
        return back()->with('success', 'Task created successfully!');
    }

    public function update(Task $task): RedirectResponse
    {
        $task->update(['is_completed' => !$task->is_completed]);
        return back();
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return back();
    }
}
