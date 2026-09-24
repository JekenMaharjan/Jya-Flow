<?php

use App\Actions\Task\DeleteTaskAction;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\On;
use Kreait\Firebase\Contract\Firestore as ContractFirestore;

new class extends Component
{
    public Task $task;

    // Listen for the real-time event and refresh the task status
    #[On('refresh-task-list')]
    public function refreshTask()
    {
        // It checks the database to see what changed for THIS specific task
        $freshTask = Task::find($this->task->id);

        if ($freshTask) {
            $this->task = $freshTask; // Updates the component with new data
        }
    }

    public function markCompleted()
    {
        if ($this->task->status === TaskStatus::COMPLETED) {
            session()->flash('info', 'Task is already marked as completed.');
            return;
        }

        // Update 'status' and 'last_updated_by' in tasks table
        $this->task->update([
            'status' => TaskStatus::COMPLETED,
            'last_updated_by' => auth()->user()?->email,
        ]);

        // Update into firebase Firestore 'tasks' collection
        app(ContractFirestore::class)
            ->database()
            ->collection('tasks')
            ->document((string) $this->task->id)
            ->set([
                'title' => $this->task->title,
                'priority' => $this->task->priority->value,
                'status' => $this->task->status->value,
                'last_updated_by' => $this->task->last_updated_by,
                'updated_at' => now()->timestamp,
            ], ['merge' => true]);

        // Dispatch Livewire event locally
        $this->dispatch('refresh-task-list');

        session()->flash('success', 'Task marked as completed!');
    }

    public function deleteTask()
    {
        // Delete from SQLite
        DeleteTaskAction::run($this->task);

        $this->dispatch('refresh-task-list');

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
                <span class="text-[11px] uppercase text-slate-300">{{ $task?->status?->label() ?? 'Pending' }}</span>
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
                        <x-entypo-check class="h-3.5 w-3.5 shrink-0"/>
                        <span>Completed</span>
                    @else
                        <x-carbon-in-progress class="h-3.5 w-3.5 shrink-0"/>
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
                <span class="flex items-center gap-1.5">
                    <x-css-eye class="w-3.5 h-3.5"/>
                    <span>Edit</span>
                </span>
            </button>

            <!-- Teleport the Edit Modal out of the child component to the <body> -->
            <template x-teleport="body">
                @include('modal.previewTask', ['task' => $task])
            </template>

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
                    <x-monoicon-delete class="w-3.5 h-3.5"/>
                    <span>Delete</span>
                </span>
                <span wire:loading wire:target="deleteTask">Deleting...</span>
            </button>
        </div>
    </div>
</li>