@if (isset($task) && $task)
<x-modal name="preview-task-{{ $task->id }}" title="Task Details">
    <form wire:submit="saveTask" class="flex flex-col gap-4 w-full">

        <!-- Title -->
        <div>
            <label for="title_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Title</label>
            <input 
                type="text"
                wire:model="form.title"
                id="title_{{ $task->id }}"
                placeholder="Add Title..."
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
            >
            @error('form.title')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Description</label>
            <textarea 
                rows="4" 
                wire:model="form.description" 
                id="description_{{ $task->id }}" 
                placeholder="Add Description..." 
                class="w-full max-h-48 overflow-y-auto px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
            ></textarea>
            @error('form.description')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- File Upload -->
        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Uploaded Files</label>
            @if(!empty($task->filename))
                <div class="mb-3 flex flex-col gap-2 px-4 py-2 bg-slate-800/80 border border-white/10 rounded-xl">
                    @foreach($task->filename as $file)    
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-300 truncate font-mono">{{ basename($file) }}</span>
                            <a href="{{ Storage::url($file) }}" target="_blank" class="text-xs text-indigo-400 hover:text-indigo-300 underline font-medium">View</a>
                        </div>
                    @endforeach
                </div>
            @endif

            <input 
                type="file"
                wire:model="files"
                multiple
                class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 transition-all duration-200"
            >
        </div>

        <!-- Due Date -->
        <div>
            <label for="due_at_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Due Date</label>
            <input 
                type="datetime-local"
                wire:model="form.due_at"
                id="due_at_{{ $task->id }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
            >
        </div>

        <!-- Priority -->
        <div>
            <label for="priority_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Priority</label>
            <select 
                wire:model="form.priority"
                id="priority_{{ $task->id }}"
                class="w-full px-4 py-3 rounded-xl cursor-pointer bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
            >
                <option value="" disabled>Choose a priority</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div>

        <!-- Status -->
        <div>
            <label for="status_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Status</label>
            <select 
                wire:model="form.status"
                id="status_{{ $task->id }}"
                class="w-full px-4 py-3 rounded-xl cursor-pointer bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
            >
                <option value="" disabled>Choose task status</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 mt-2 border-t border-white/10">
            <button 
                type="button"
                @click="$dispatch('close-modal', 'preview-task-{{ $task->id }}')"
                class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl cursor-pointer transition-all duration-200"
            >
                Cancel
            </button>
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl cursor-pointer transition-all duration-200 disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="saveTask">Save Task</span>
                <span wire:loading wire:target="saveTask">Saving...</span>
            </button>
        </div>
    </form>
</x-modal>
@endif