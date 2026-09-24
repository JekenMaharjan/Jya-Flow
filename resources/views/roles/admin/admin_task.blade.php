@extends('layouts.app')

@use('\App\Enums\TaskStatus')
@use('\App\Enums\TaskPriority')

@section('content')
<div class="max-w-2xl mx-auto my-6 p-6 sm:p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">

    <!-- Header with Task Stats -->
    <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/10">
        <div>
            <h2 class="text-2xl font-bold font-serif text-white tracking-wide">My Tasks <span class="font-light text-sm text-slate-400">( Admin )</span></h2>
            <p class="text-xs text-slate-400 mt-0.5">Manage your daily priorities</p>
        </div>
        
        @if($tasks->count() > 0)
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                {{ $completedTasksCount }} / {{ $totalTasksCount }} Done
            </span>
        @endif
    </div>

    <!-- Filter by Status -->
    <p class="text-xs text-slate-400 mb-2">Filter by status (Completed / InProgress)</p>

    <div class="flex gap-2 font-roboto text-xs">
        {{-- All Tasks --}}
        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
            class="px-3 py-1 rounded-md cursor-pointer border transition {{ request('status', 'all') === 'all' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
            All Tasks ({{ $totalTasksCount }})
        </a>

        {{-- In Progress --}}
        <a href="{{ request()->fullUrlWithQuery(['status' => 'in_progress']) }}"
            class="px-3 py-1 rounded-md cursor-pointer border transition {{ request('status') === 'in_progress' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
            InProgress ({{ $inProgressTasksCount }})
        </a>
        
        {{-- Completed --}}
        <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}"
            class="px-3 py-1 rounded-md cursor-pointer border transition {{ request('status') === 'completed' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
            Completed ({{ $completedTasksCount }})
        </a>
    </div>

    <hr class="text-white/10 my-4">

    <!-- Filter by Priority -->
    <p class="text-xs text-slate-400 mb-2">Filter by priority (Low / Medium / High)</p>

    <div class="flex gap-2 font-roboto text-xs">
        {{-- Default --}}
        <a href="{{ request()->fullUrlWithQuery(['priority' => 'all']) }}"
            class="px-3 py-1 rounded-md cursor-pointer border {{ request('priority') === null || request('priority') === '' || request('priority') === 'all' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
            All Tasks ({{ $totalTasksCount }})
        </a>

        {{-- Pending Button --}}
        <a href="{{ request()->fullUrlWithQuery(['priority' => 'low']) }}"
            class="px-3 py-1 rounded-md cursor-pointer border {{ request('priority') === 'low' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
            Low ({{ $lowTasksCount }})
        </a>
        
        {{-- Done Button --}}
        <a href="{{ request()->fullUrlWithQuery(['priority' => 'medium']) }}"
            class="px-3 py-1 rounded-md cursor-pointer border {{ request('priority') === 'medium' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
            Medium ({{ $mediumTasksCount }})
        </a>
        
        {{-- Done Button --}}
        <a href="{{ request()->fullUrlWithQuery(['priority' => 'high']) }}"
            class="px-3 py-1 rounded-md cursor-pointer border {{ request('priority') === 'high' ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-200' }}">
            High ({{ $highTasksCount }})
        </a>
    </div>

    <hr class="text-white/10 my-4">

    <!-- Add Task Button -->
    <div x-data>
        <button 
            type="button"
            @click="$dispatch('open-modal', 'add-task-modal')"
            class="py-2 px-4 mb-7 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
        >
            <span class="text-sm">+ Add Task</span>
        </button>
    </div>

    {{-- Task List Container --}}
    <div>
        <livewire:tasks.task-list />
    </div>

    {{ $tasks->appends(request()->query())->links() }}
</div>

@include('modal.addTask')
@endsection