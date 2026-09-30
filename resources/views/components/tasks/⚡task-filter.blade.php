<?php

use Livewire\Component;
use Livewire\Attributes\Url;

new class extends Component
{
    #[Url]
    public string $status = 'all';

    #[Url]
    public string $priority = 'all';

    // Accept initial counts passed from controller or view
    public int $totalTasksCount = 0;
    public int $inProgressTasksCount = 0;
    public int $completedTasksCount = 0;
    public int $lowTasksCount = 0;
    public int $mediumTasksCount = 0;
    public int $highTasksCount = 0;

    public function setStatus(string $value): void
    {
        $this->status = $value;
        $this->dispatch(
            'filter-changed',
            status: $this->status, 
            priority: $this->priority
        );
    }

    public function setPriority(string $value): void
    {
        $this->priority = $value;
        $this->dispatch(
            'filter-changed', 
            status: $this->status, 
            priority: $this->priority
        );
    }
};

?>

<div>
    <div>
        <p class="text-xs text-slate-400 mb-2">Filter by status</p>
        <div class="flex gap-2 font-roboto text-xs">
            <button 
                type="button"
                wire:click="setStatus('all')"
                class="px-3 py-1 rounded-md border cursor-pointer transition {{ $status === 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}"
            >
                All Tasks ({{ $totalTasksCount }})
            </button>

            <button 
                type="button"
                wire:click="setStatus('in_progress')"
                class="px-3 py-1 rounded-md border cursor-pointer transition {{ $status === 'in_progress' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}"
            >
                InProgress ({{ $inProgressTasksCount }})
            </button>

            <button 
                type="button"
                wire:click="setStatus('completed')"
                class="px-3 py-1 rounded-md border cursor-pointer transition {{ $status === 'completed' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}"
            >
                Completed ({{ $completedTasksCount }})
            </button>
        </div>
    </div>

    <hr class="border-white/10 my-4">

    <div>
        <p class="text-xs text-slate-400 mb-2">Filter by priority</p>
        <div class="flex gap-2 font-roboto text-xs">
            <button 
                type="button"
                wire:click="setPriority('all')"
                class="px-3 py-1 rounded-md border cursor-pointer transition {{ $priority === 'all' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}"
            >
                All Priority ({{ $totalTasksCount }})
            </button>

            <button 
                type="button"
                wire:click="setPriority('low')"
                class="px-3 py-1 rounded-md border cursor-pointer transition {{ $priority === 'low' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}"
            >
                Low ({{ $lowTasksCount }})
            </button>

            <button 
                type="button"
                wire:click="setPriority('medium')"
                class="px-3 py-1 rounded-md border cursor-pointer transition {{ $priority === 'medium' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}"
            >
                Medium ({{ $mediumTasksCount }})
            </button>

            <button 
                type="button"
                wire:click="setPriority('high')"
                class="px-3 py-1 rounded-md border cursor-pointer transition {{ $priority === 'high' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300' }}"
            >
                High ({{ $highTasksCount }})
            </button>
        </div>
    </div>
</div>