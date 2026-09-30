@extends('layouts.app')

@use('\App\Enums\TaskStatus')
@use('\App\Enums\TaskPriority')

@section('content')
<div class="max-w-2xl mx-auto my-6 p-6 sm:p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/10">
        <div>
            <div class="flex items-center gap-2.5">
                <h2 class="text-xl font-semibold tracking-tight text-white font-sans">
                    Task Management
                </h2>

                <!-- Context / Role Badge -->
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Member Workspace
                </span>
            </div>

            <p class="text-xs text-slate-400 mt-1">
                Stay organized, track your tasks, and keep your deliverables on schedule.
            </p>
        </div>

        @if($totalTasksCount > 0)
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                {{ $completedTasksCount }} / {{ $totalTasksCount }} Done
            </span>
        @endif
    </div>

    <!-- Filter by Status & Priority -->
    <livewire:tasks.task-filter 
        :total-tasks-count="$totalTasksCount"
        :in-progress-tasks-count="$inProgressTasksCount"
        :completed-tasks-count="$completedTasksCount"
        :low-tasks-count="$lowTasksCount"
        :medium-tasks-count="$mediumTasksCount"
        :high-tasks-count="$highTasksCount"
    />

    <hr class="border-white/10 my-4">

    <!-- Add Task Button -->
    <button 
        type="button" 
        x-data
        @click="$dispatch('open-modal', 'create-task-modal')" 
        class="px-4 py-2 mb-5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl cursor-pointer"
    >
        + Add Task
    </button>

    <!-- Task List -->
    <livewire:tasks.task-list />
</div>

@include('tasks.create-task-modal')
@endsection