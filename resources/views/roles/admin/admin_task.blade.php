@extends('layouts.app')

@use('\App\Enums\TaskStatus')
@use('\App\Enums\TaskPriority')

@section('content')
<div class="max-w-2xl mx-auto my-6 p-6 sm:p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/10">
        <div>
            <h2 class="text-2xl font-bold font-serif text-white tracking-wide">
                My Tasks <span class="font-light text-sm text-slate-400">( Admin )</span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Manage your daily priorities</p>
        </div>
        
        @if($totalTasksCount > 0)
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                {{ $completedTasksCount }} / {{ $totalTasksCount }} Done
            </span>
        @endif
    </div>

    <!-- Filter by Status -->
    <p class="text-xs text-slate-400 mb-2">Filter by status</p>
    <div class="flex gap-2 font-roboto text-xs">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
            class="px-3 py-1 rounded-md border transition {{ request('status', 'all') == 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}">
            All Tasks ({{ $totalTasksCount }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['status' => TaskStatus::IN_PROGRESS->value ?? 'in_progress']) }}"
            class="px-3 py-1 rounded-md border transition {{ request('status') == (TaskStatus::IN_PROGRESS->value ?? 'in_progress') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}">
            InProgress ({{ $inProgressTasksCount }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['status' => TaskStatus::COMPLETED->value ?? 'completed']) }}"
            class="px-3 py-1 rounded-md border transition {{ request('status') == (TaskStatus::COMPLETED->value ?? 'completed') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}">
            Completed ({{ $completedTasksCount }})
        </a>
    </div>

    <hr class="border-white/10 my-4">

    <!-- Filter by Priority -->
    <p class="text-xs text-slate-400 mb-2">Filter by priority</p>
    <div class="flex gap-2 font-roboto text-xs">
        <a href="{{ request()->fullUrlWithQuery(['priority' => 'all']) }}"
            class="px-3 py-1 rounded-md border transition {{ request('priority', 'all') == 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}">
            All Priority ({{ $totalTasksCount }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['priority' => TaskPriority::LOW->value ?? 'low']) }}"
            class="px-3 py-1 rounded-md border transition {{ request('priority') == (TaskPriority::LOW->value ?? 'low') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}">
            Low ({{ $lowTasksCount }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['priority' => TaskPriority::MEDIUM->value ?? 'medium']) }}"
            class="px-3 py-1 rounded-md border transition {{ request('priority') == (TaskPriority::MEDIUM->value ?? 'medium') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}">
            Medium ({{ $mediumTasksCount }})
        </a>

        <a href="{{ request()->fullUrlWithQuery(['priority' => TaskPriority::HIGH->value ?? 'high']) }}"
            class="px-3 py-1 rounded-md border transition {{ request('priority') == (TaskPriority::HIGH->value ?? 'high') ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}">
            High ({{ $highTasksCount }})
        </a>
    </div>

    <hr class="border-white/10 my-4">

    <!-- Add Task Button -->
    <button 
        type="button" 
        x-data
        @click="$dispatch('open-modal', 'create-task-modal')" 
        class="px-4 py-2 mb-5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-lg transition cursor-pointer"
    >
        + Add Task
    </button>

    <!-- Task List -->
    <div>
        <livewire:tasks.task-list />
    </div>
</div>

@include('tasks.create-task-modal')
@endsection