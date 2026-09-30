
<?php

use App\Models\Task;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public int $perPage = 7;

    public function loadMore(): void
    {
        $this->perPage += 7;
    }

    // Listener for Echo broadcasts or local events
    #[On('echo-private:tasks,TaskCreated')]
    #[On('echo-private:tasks,TaskUpdated')]
    #[On('echo-private:tasks,TaskDeleted')]
    #[On('refresh-task-list')]
    public function refreshList(): void 
    {
        // Livewire automatically Re-renders render() on any real-time update
    }

    public function render()
    {
        return view('components.tasks.⚡task-list', [
            'tasks' => Task::latest()->paginate($this->perPage),
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
        @empty
            <li class="text-center py-10 px-4 rounded-xl border border-dashed border-white/10 bg-white/5">
                <div class="w-10 h-10 mx-auto mb-3 rounded-full bg-slate-800/80 border border-white/10 flex items-center justify-center">
                    <x-carbon-task class="w-6 h-6 text-slate-500"/>
                </div>
                <p class="text-sm font-medium text-slate-300">No tasks found</p>
                <p class="text-xs text-slate-500 mt-1">Add a task above to get started!</p>
            </li>
        @endforelse
    </ul>

    @if($tasks->hasMorePages())
        <div 
            x-intersect.full="$wire.loadMore()" 
            class="py-6 text-center text-xs text-slate-400 cursor-pointer"
        >
            <span wire:loading.remove wire:target="loadMore">
                Scroll down (or click here) to load more...
            </span>
            <span wire:loading wire:target="loadMore">
                Loading more tasks...
            </span>
        </div>
    @endif
</div>