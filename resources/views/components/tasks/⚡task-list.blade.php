<?php

use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    // Re-render the entire list when ANY user creates, deletes, or updates a task
    #[On('refresh-task-list')]
    public function render()
    {
        return view('components.tasks.⚡task-list', [
            'tasks' => Task::latest()->get(),
        ]);
    }
};
?>

<div>
    <ul class="space-y-3 mb-5">
        @forelse($tasks as $task)
            <livewire:tasks.task-action-button
                :task="$task" 
                :wire:key="'task-row-'.$task->id" 
            />

            <!-- Include per task inside loop -->
            @include('modal.previewTask', ['task' => $task])
            
        @empty
            <!-- Empty State -->
            <li class="text-center py-10 px-4 rounded-xl border border-dashed border-white/10 bg-white/1">
                <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-slate-800/80 border border-white/10 flex items-center justify-center">
                    <x-carbon-task class="w-6.25 text-slate-500"/>
                </div>
                <p class="text-sm font-medium text-slate-300">No tasks found</p>
                <p class="text-xs text-slate-500 mt-1">Add a task above to get started!</p>
            </li>
        @endforelse
    </ul>
</div>