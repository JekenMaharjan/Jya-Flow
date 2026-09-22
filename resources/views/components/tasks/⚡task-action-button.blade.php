<?php

use App\Actions\Task\DeleteTaskAction;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Models\Task;
use Livewire\Component;

new class extends Component
{
    public Task $task;

    public function markCompleted(): void
    {
        if ($this->task->status === TaskStatus::COMPLETED) {
            session()->flash('info', 'Task is already marked as completed.');
            return;
        }

        $this->task->update(['status' => TaskStatus::COMPLETED]);
        session()->flash('success', 'Task marked as completed!');
    }

    public function deleteTask()
    {
        DeleteTaskAction::run($this->task);
        session()->flash('success', 'Task deleted successfully!');
    }
};

?>

<li class="group flex items-center justify-between p-3.5 rounded-xl border border-white/5 hover:border-white/10 transition-all duration-200 bg-slate-700/10 hover:bg-slate-700/20">
    <!-- Task Details -->
    <div class="flex-1 flex-col items-center">
        <!-- Task Title -->
        <span class="text-sm font-medium transition-all mb-2 duration-200 {{ ($task->status === TaskStatus::COMPLETED) ? 'line-through text-slate-500' : 'text-slate-200' }}">
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
            {{ $task->due_at_nepal?->format('Y-m-d \a\t h:i A') ?? 'No due date' }}
        </span>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2">
            <!-- Status Button -->
            <button 
                type="button"
                wire:click="markCompleted"
                wire:loading.attr="disabled"
                wire:target="markCompleted"
                class="text-xs px-3 py-1.5 rounded-lg font-medium transition-all duration-200 cursor-pointer flex items-center gap-1.5 border disabled:opacity-50 {{ ($task->status === TaskStatus::COMPLETED) ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-slate-400/10 text-slate-300 border-slate-500/20' }}"
            >
                <span wire:loading.remove wire:target="markCompleted" class="flex items-center gap-1">
                    @if($task->status === TaskStatus::COMPLETED)
                        <x-entypo-check class="h-4 w-4 shrink-0"/>
                        <span>Completed</span>
                    @else
                        <x-carbon-in-progress class="h-4 w-4 shrink-0"/>
                        <span>In Progress</span>
                    @endif
                </span>
                <span wire:loading wire:target="markCompleted">Updating...</span>
            </button>

            <!-- Edit Button -->
            <button 
                type="button"
                x-on:click="$dispatch('open-modal', 'preview-task-{{ $task->id }}')"
                class="text-xs px-3 py-1.5 rounded-lg font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500/20 transition-all duration-200 cursor-pointer flex items-center gap-1.5"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span>Edit</span>
            </button>

            <!-- Delete Button -->
            <button 
                type="button"
                wire:click="deleteTask"
                wire:confirm="Are you sure you want to delete this task?"
                wire:loading.attr="disabled"
                wire:target="deleteTask"
                class="text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 font-medium bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-all duration-200 cursor-pointer disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="deleteTask" class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Delete</span>
                </span>
                <span wire:loading wire:target="deleteTask">Deleting...</span>
            </button>
        </div>
    </div>
</li>