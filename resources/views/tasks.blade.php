@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="max-w-xl mx-auto my-6 p-6 sm:p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">

    <!-- Header with Task Stats -->
    <div class="flex items-center justify-between mb-5 pb-4 border-b border-white/10">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-wide">My Tasks</h2>
            <p class="text-xs text-slate-400 mt-0.5">Manage your daily priorities</p>
        </div>
        
        @if($tasks->count() > 0)
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                <!-- {{ $tasks->where('is_completed', true)->count() }} / {{ $tasks->count() }} Done -->
                {{ $completedTasksCount }} / {{ $totalTasksCount }} Done
            </span>
        @endif
    </div>

    <p class="text-xs text-slate-400 mb-2">Filter by status (Completed/InProgress)</p>

    <div class="mb-5 font-roboto text-xs">
        <form action="{{ route('tasks') }}" method="GET" class="flex gap-2 mb-4 items-center flex-wrap">
            {{-- All Tasks Button --}}
            <button type="submit" name="status" value="" 
                class="px-3 py-1 rounded-md cursor-pointer border {{ request('status') === null || request('status') === '' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                All Tasks ({{ $totalTasksCount }})
            </button>

            {{-- Done Button --}}
            <button type="submit" name="status" value="1" 
                class="px-3 py-1 rounded-md cursor-pointer border {{ request('status') === '1' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                Completed ({{ $completedTasksCount }})
            </button>

            {{-- Pending Button --}}
            <button type="submit" name="status" value="0" 
                class="px-3 py-1 rounded-md cursor-pointer border {{ request('status') === '0' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                InProgress ({{ $totalTasksCount - $completedTasksCount }})
            </button>
        </form>
    </div>

    <!-- Form to Add Task -->
    <form action="{{ route('tasks.store') }}" method="POST" class="flex items-center gap-3 mb-8">
        @csrf
        <div class="relative flex-1">
            <input 
                type="text" 
                name="title" 
                placeholder="Add a new task..." 
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
            >
            @error('title')
                <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <button 
            type="submit" 
            class="px-5 py-3 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 active:scale-[0.98] border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer flex items-center gap-1.5"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Add</span>
        </button>
    </form>

    <!-- Task List -->
    <ul class="space-y-3 mb-5">
        @forelse($tasks as $task)
            <li class="group flex items-center justify-between cursor-pointer p-3.5 rounded-xl bg-white/[0.03] hover:bg-white/[0.07] border border-white/5 hover:border-white/10 transition-all duration-200">
                
                <!-- Task Title -->
                <span class="text-sm font-medium transition-all duration-200 {{ $task->is_completed ? 'line-through text-slate-500' : 'text-slate-200' }}">
                    {{ $task->title }}
                </span>

                <!-- Action Buttons -->
                <div class="flex items-center gap-2">
                    <!-- Toggle Completion -->
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button 
                            type="submit" 
                            class="text-xs px-3 py-1.5 rounded-lg font-medium transition-all duration-200 cursor-pointer flex items-center gap-1 border {{ !($task->is_completed) ? 'bg-slate-800 text-slate-300 border-slate-700 hover:bg-slate-700' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20' }}"
                        >
                            @if($task->is_completed)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Completed</span>
                            @else
                                <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2.5"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <span>InProgress</span>
                            @endif
                        </button>
                    </form>

                    <!-- Delete Task -->
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="text-xs px-3 py-1.5 rounded-lg flex gap-2 items-center font-medium bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-all duration-200 cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </li>
        @empty
            <!-- Empty State -->
            <li class="text-center py-10 px-4 rounded-xl border border-dashed border-white/10 bg-white/[0.01]">
                <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-slate-800/80 border border-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-300">No tasks found</p>
                <p class="text-xs text-slate-500 mt-1">Add a task above to get started!</p>
            </li>
        @endforelse
    </ul>

    <!-- {{ $tasks->onEachSide(5)->links() }} -->
    {{ $tasks->links() }} 

</div>
@endsection