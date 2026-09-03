@extends('layouts.app')

@use('\App\Enums\TaskStatus')
@use('\App\Enums\TaskPriority')

@section('content')
    <div class="max-w-xl mx-auto my-6 p-6 sm:p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">

        <!-- Header with Task Stats -->
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/10">
            <div>
                <h2 class="text-2xl font-bold font-serif text-white tracking-wide">My Tasks</h2>
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

        <!-- Add task button -->
        <button 
            type="button"
            onclick="document.getElementById('taskModal').showModal()"
            class="py-2 px-4 mb-7 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
        >
            <span class="text-sm">+ Add Task</span>
        </button>

        <!-- Task List -->
        <ul class="space-y-3 mb-5">
            @forelse($tasks as $task)
                <li 
                    class="group flex items-center justify-between cursor-pointer p-3.5 rounded-xl border border-white/5 hover:border-white/10 transition-all duration-200 {{ match($task->status) {
                            TaskStatus::IN_PROGRESS => match($task->priority) {
                                TaskPriority::LOW => 'bg-blue-300/20 hover:bg-blue-300/30',
                                TaskPriority::MEDIUM => 'bg-yellow-300/20 hover:bg-yellow-300/30',
                                TaskPriority::HIGH => 'bg-red-300/20 hover:bg-red-300/30',
                                default => 'bg-gray-300/20 hover:bg-gray-300/30',
                            },
                            TaskStatus::COMPLETED => 'bg-green-300/20 hover:bg-green-300/30',
                            default => 'bg-gray-300/20 hover:bg-gray-300/30',
                        } }}"
                    onclick="document.getElementById('previewTaskModal_{{ $task->id }}').showModal()"
                >
                    <!-- First half -->
                    <div class="flex-1 flex-col items-center">
                        <!-- Task Title -->
                        <span class="text-sm font-medium transition-all mb-2 duration-200 {{ ($task->status) === (TaskStatus::COMPLETED) ? 'line-through text-slate-500' : 'text-slate-200' }}">
                            {{ $task->title }}
                        </span>

                        <!-- Priority and Status -->
                        <span class="flex gap-2 items-center font-roboto">
                            <span class="flex gap-2 items-center">
                                <x-codicon-circle-small-filled 
                                    class="w-3 h-3 scale-150 {{ match($task->priority) {
                                        TaskPriority::LOW => 'text-blue-400',
                                        TaskPriority::MEDIUM => 'text-yellow-400',
                                        TaskPriority::HIGH => 'text-red-500',
                                        default => 'text-gray-400',
                                    } }}"
                                />
                                <span class="text-[11px] uppercase text-slate-300">{{ $task->priority }}</span>
                            </span>

                            <span class="text-slate-300 select-none">|</span>

                            <span class="flex gap-2 items-center">
                                <x-codicon-circle-small-filled class="w-3 h-3 scale-150 {{ (($task->status) === TaskStatus::COMPLETED) ? ('text-emerald-400') : ('text-yellow-400') }}"/>
                                <span class="text-[11px] uppercase text-slate-300">{{ $task->status->label() }}</span>
                            </span>
                        </span>

                        <!-- Task due date and time -->
                        <span class="text-[11px] font-light text-gray-400 font-roboto">
                            {{ $task->due_at ? $task->due_at->format('M d, Y h:i A') : 'No due date' }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button 
                                type="submit"
                                onclick="event.stopPropagation()"
                                class="text-xs px-3 py-1.5 rounded-lg font-medium transition-all duration-200 cursor-pointer flex items-center gap-1 border {{ (($task->status) === TaskStatus::COMPLETED) ? ('bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20') : ('bg-slate-800 text-slate-300 border-slate-800 hover:bg-slate-800/70') }}"
                            >
                                @if($task->status === TaskStatus::COMPLETED)
                                    <x-entypo-check class="h-4 w-4"/>
                                    <span>Completed</span>
                                @else
                                    <x-carbon-in-progress class="h-4 w-4"/>
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
                                onclick="event.stopPropagation()"
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

                <!-- Include per task inside loop -->
                @include('modal.previewTask', ['task' => $task])
                
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

        {{ $tasks->appends(request()->query())->links() }}
    </div>

@include('modal.addTask')
@endsection