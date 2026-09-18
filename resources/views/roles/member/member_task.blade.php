@extends('layouts.app')

@use('\App\Enums\TaskStatus')
@use('\App\Enums\TaskPriority')

@section('content')
    <div class="max-w-2xl mx-auto my-6 p-6 sm:p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">

        <!-- Header with Task Stats -->
        <div class="flex items-center justify-between mb-4 pb-4 border-b border-white/10">
            <div>
                <h2 class="text-2xl font-bold font-serif text-white tracking-wide">My Tasks <span class="font-light text-sm text-slate-400">( Member )</span></h2>
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
        <button 
            type="button"
            @click="$dispatch('open-modal', 'add-task-modal')"
            class="py-2 px-4 mb-7 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
        >
            <span class="text-sm">+ Add Task</span>
        </button>

        <!-- Task List -->
        <ul class="space-y-3 mb-5">
            @forelse($tasks as $task)
                <li 
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition.out.opacity.duration.300ms
                    @task-deleted.window="if ($event.detail.taskId === {{ $task->id }}) show = false"
                    @click="if (!$event.target.closest('button')) $dispatch('open-modal', 'preview-task-{{ $task->id }}')"
                    class="group flex items-center justify-between cursor-pointer p-3.5 rounded-xl border border-white/5 hover:border-white/10 transition-all duration-200 bg-slate-700/10 hover:bg-slate-700/20"
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
                            {{ $task->due_at_nepal->format('Y-m-d \a\t h:i A') ?? 'No due date' }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-2">
                        <livewire:task-action-button :task="$task" :key="$task->id" />
                    </div>
                </li>

                <!-- Include per task inside loop -->
                @include('modal.previewTask', ['task' => $task])
                
            @empty
                <!-- Empty State -->
                <li class="text-center py-10 px-4 rounded-xl border border-dashed border-white/10 bg-white/1">
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