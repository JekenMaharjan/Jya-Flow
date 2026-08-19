<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request) 
    {
        $tasks = Task::latest()->get();

        // If the client sends 'Accept: application/json' (e.g. Postman or API)
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Retrieved All Tasks',
                'tasks' => $tasks
            ], 200);
        }

        // Return view of tasks 
        return view('tasks', compact('tasks'));
    }
    
    public function store(StoreTaskRequest $request) 
    {
        // 1. Validate input
        $validatedData = $request->validated();

        // 2. Save new task to database and store in $task variable
        $task = Task::create($validatedData);

        // 3. Return JSON if API client (Postman/Mobile/Frontend)
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Task created successfully!',
                'task' => $task
            ], 201); // 201 Created
        }

        // 4. Return back for traditional Blade web forms
        return back()->with('success', 'Task created successfully!');
    }

    public function update(Task $task) {
        $task->update(['is_completed' => !$task->is_completed]);
        return back();
    }

    public function destroy(Task $task) {
        $task->delete();
        return back();
    }
}
