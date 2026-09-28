@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto my-6 p-6 sm:p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/10">
        <div>
            <h2 class="text-2xl font-bold font-serif text-white tracking-wide">
                My Tasks <span class="font-light text-sm text-slate-400">( Member )</span>
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Manage your daily priorities</p>
        </div>
        
        @if($totalTasksCount > 0)
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                {{ $completedTasksCount }} / {{ $totalTasksCount }} Done
            </span>
        @endif
    </div>

    <!-- Filter by Status & Priority -->
    <livewire:tasks.task-filter />

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

    <!-- Task List Container -->
    <livewire:tasks.task-list />

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tasks->appends(request()->query())->links() }}
    </div>
</div>

@include('modal.addTask')
@endsection