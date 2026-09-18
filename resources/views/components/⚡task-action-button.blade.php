{{-- Class Components --}}
<?php

use App\Actions\Task\DeleteTaskAction;
use App\Enums\TaskStatus;
use App\Models\Task;
use Livewire\Component;

new class extends Component
{
    public Task $task;

    public function markCompleted()
    {
        if ($this->task->status === TaskStatus::COMPLETED) {
            session()->flash('info', 'Task is already marked as completed.');
            return;
        }

        $this->task->update(['status' => TaskStatus::COMPLETED]);

        session()->flash('success', 'Task marked as completed!');
        $this->dispatch('task-updated', taskId: $this->task->id);
    }

    public function deleteTask()
    {
        DeleteTaskAction::run($this->task);

        session()->flash('success', 'Task deleted successfully!');

        $this->skipRender();
    }
};

?>

{{-- Templates --}}
<div class="flex flex-col gap-2">
    {{-- Local Flash Messages --}}
    <div class="text-center">
        @if (session()->has('success'))
            <div class="mb-2 text-[10px] text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 p-1.5 rounded-lg font-medium transition-all duration-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('info'))
            <div class="mb-2 text-[10px] text-slate-300 bg-slate-500/10 border border-slate-500/20 p-1.5 rounded-lg font-medium transition-all duration-200">
                {{ session('info') }}
            </div>
        @endif
    </div>

    {{-- Action Buttons --}}
    <div class="flex items-center gap-2">
        {{-- Status Toggle Button --}}
        <button 
            type="button"
            wire:click.stop="markCompleted"
            wire:loading.attr="disabled"
            wire:target="markCompleted"
            class="text-xs px-3 py-1.5 rounded-lg font-medium transition-all duration-200 cursor-pointer flex items-center gap-1.5 border disabled:opacity-50 disabled:cursor-not-allowed {{ ($task->status === TaskStatus::COMPLETED) ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20 hover:bg-emerald-500/20' : 'bg-slate-400/10 text-slate-300 border-slate-500/20 hover:bg-slate-500/30' }}"
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

            <span wire:loading wire:target="markCompleted" class="flex items-center gap-1">
                <svg class="animate-spin h-3.5 w-3.5 text-current" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Updating...</span>
            </span>
        </button>

        {{-- Preview Modal Trigger Button --}}
        <button 
            type="button"
            @click.stop="$dispatch('open-modal', 'preview-task-{{ $task->id }}')"
            class="text-xs px-3 py-1.5 rounded-lg font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500/20 transition-all duration-200 cursor-pointer flex items-center gap-1.5"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>Edit</span>
        </button>

        {{-- Delete Button --}}
        <button 
            type="button"
            wire:click.stop="deleteTask"
            wire:confirm="Are you sure you want to delete this task?"
            wire:loading.attr="disabled"
            wire:target="deleteTask"
            class="text-xs px-3 py-1.5 rounded-lg flex items-center gap-1.5 font-medium bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
        >
            <span wire:loading.remove wire:target="deleteTask" class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Delete</span>
            </span>

            <span wire:loading wire:target="deleteTask" class="flex items-center gap-1">
                <svg class="animate-spin h-3.5 w-3.5 text-current" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Deleting...</span>
            </span>
        </button>
    </div>
</div>