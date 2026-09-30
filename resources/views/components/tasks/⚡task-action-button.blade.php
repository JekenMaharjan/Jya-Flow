<?php

use App\Actions\Task\UpdateTaskAction;
use App\Actions\Task\DeleteTaskAction;
use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Events\TaskUpdated;
use App\Models\Task;
use Livewire\Component;
use Livewire\WithFileUploads;
use Kreait\Firebase\Contract\Firestore as ContractFirestore;

new class extends Component
{
    use WithFileUploads;

    public Task $task;

    public array $form = [];
    public $files = [];

    public function mount(): void
    {
        $this->form = [
            'title'       => $this->task->title ?? '',
            'description' => $this->task->description ?? '',
            'priority'    => $this->task->priority?->value ?? $this->task->priority ?? '',
            'status'      => $this->task->status?->value ?? $this->task->status ?? '',
            'due_at'      => $this->task->due_at_nepal?->format('Y-m-d\TH:i'),
        ];
    }

    public function saveTask(): void
    {
        UpdateTaskAction::run(auth()->user(), $this->task, $this->form, $this->files);

        $this->files = [];
        $this->dispatch('refresh-task-list');
        $this->dispatch('close-modal', 'preview-task-' . $this->task->id);

        session()->flash('success', 'Task updated successfully!');
    }

    public function markCompleted(): void
    {
        if ($this->task->status === TaskStatus::COMPLETED) {
            session()->flash('info', 'Task is already marked as completed.');
            return;
        }

        // Perform DB update directly on the model query without mutating $this->task in memory
        Task::where('id', $this->task->id)->update([
            'status'          => TaskStatus::COMPLETED,
            'last_updated_by' => auth()->user()?->email,
        ]);

        // Sync Firestore
        app(ContractFirestore::class)
            ->database()
            ->collection('tasks')
            ->document((string) $this->task->id)
            ->set([
                'status'          => TaskStatus::COMPLETED->value,
                'last_updated_by' => auth()->user()?->email,
                'updated_at'      => now()->timestamp,
            ], ['merge' => true]);

        broadcast(new TaskUpdated($this->task))->toOthers();

        // Tell parent task-list to re-render fresh data
        $this->dispatch('refresh-task-list');

        session()->flash('success', 'Task marked as completed!');
    }

    public function deleteTask(): void
    {
        DeleteTaskAction::run($this->task);
        $this->dispatch('refresh-task-list');
        session()->flash('success', 'Task deleted successfully!');
    }
};
?>

<li class="group flex items-center justify-between p-3.5 rounded-xl border border-white/5 hover:border-white/10 transition-all duration-200 bg-slate-700/10 hover:bg-slate-700/20">
    <!-- Task Details -->
    <div class="flex-1 flex-col items-center">
        <!-- Title -->
        <span class="text-sm font-medium transition-all mb-2 duration-200 {{ $task->status === TaskStatus::COMPLETED ? 'line-through text-slate-500' : 'text-slate-200' }}">
            {{ $task->title }}
        </span>

        <!-- Priority & Status Badges -->
        <div class="flex gap-2 items-center font-roboto">
            <span class="flex gap-1.5 items-center">
                <x-codicon-circle-small-filled 
                    class="w-3 h-3 scale-150 {{ match($task->priority) {
                        TaskPriority::LOW => 'text-blue-400',
                        TaskPriority::MEDIUM => 'text-yellow-400',
                        TaskPriority::HIGH => 'text-red-500',
                        default => 'text-gray-400',
                    } }}"
                />
                <span class="text-[11px] uppercase text-slate-300">{{ $task->priority->value ?? $task->priority }}</span>
            </span>

            <span class="text-slate-500 select-none">|</span>

            <span class="flex gap-1.5 items-center">
                <x-codicon-circle-small-filled class="w-3 h-3 scale-150 {{ $task->status === TaskStatus::COMPLETED ? 'text-emerald-400' : 'text-yellow-400' }}"/>
                <span class="text-[11px] uppercase text-slate-300">{{ $task->status?->label() ?? 'Pending' }}</span>
            </span>
        </div>

        <!-- Due Date -->
        <span class="text-[11px] font-light text-gray-400 font-roboto">
            {{ $task->due_at_nepal?->format('Y-m-d \a\t h:i A') ?? 'No due date' }}
        </span>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-2">
        <!-- Status Button -->
        <button 
            type="button"
            wire:click="markCompleted"
            wire:loading.attr="disabled"
            wire:target="markCompleted"
            class="text-xs px-3 py-1.5 rounded-lg font-medium transition-all duration-200 cursor-pointer flex items-center gap-1.5 border disabled:opacity-50 {{ $task->status === TaskStatus::COMPLETED ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-slate-400/10 text-slate-300 border-slate-500/20' }}"
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

        <!-- Edit Task Button -->
        <button 
            type="button" 
            @click="$dispatch('open-modal', 'edit-task-modal-{{ $task->id }}')" 
            class="text-xs px-3 py-1.5 rounded-lg font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 hover:bg-indigo-500/20 transition-all duration-200 cursor-pointer flex items-center gap-1.5"
        >
            <x-css-eye class="w-3.5 h-3.5"/>
            <span>Edit</span>
        </button>

        <!-- Edit Modal Teleport -->
        <template x-teleport="body">
            @include('tasks.edit-task-modal', ['task' => $task, 'members' => $members ?? []])
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
</li>