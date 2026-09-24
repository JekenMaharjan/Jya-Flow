@if (isset($task) && $task)
<x-modal name="preview-task-{{ $task->id }}" title="Task Details">
    <form action="{{ route('tasks.change', $task?->id ?? 0) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 w-full">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label for="title_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Title</label>
            <input 
                type="text"
                name="title"
                id="title_{{ $task->id }}"
                placeholder="Add Title..."
                value="{{ old('title', $task->title) }}"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
            >
            @error('title')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Description</label>
            <textarea 
                rows="4" 
                name="description" 
                id="description_{{ $task->id }}" 
                placeholder="Add Description..." 
                class="w-full max-h-48 overflow-y-auto px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
            >{{ old('description', $task->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- File Upload & Display -->
        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Uploaded Files</label>
            
            @if(!empty($task->filename))
                <div class="mb-3 flex flex-col gap-2 px-4 py-2 bg-slate-800/80 border border-white/10 rounded-xl">
                    @foreach($task->filename as $file)    
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2 truncate">
                                <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                                <span class="text-xs text-slate-300 truncate font-mono">
                                    {{ basename($file) }}
                                </span>
                            </div>
                            <a href="{{ Storage::url($file) }}" target="_blank" class="text-xs text-indigo-400 hover:text-indigo-300 underline shrink-0 ml-2">
                                View
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <input 
                type="file"
                id="files_{{ $task->id }}"
                name="files[]"
                multiple
                class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer transition-all duration-200"
            >
            @error('file_path')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Due Date and Time -->
        <div>
            <label for="due_at_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Due Date</label>
            <input 
                type="datetime-local"
                name="due_at"
                id="due_at_{{ $task->id }}"
                value="{{ old('due_at', $task->due_at_nepal ? $task->due_at_nepal->format('Y-m-d\TH:i') : '') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
            >
            @error('due_at')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Priority -->
        <div>
            <label for="priority_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Priority</label>
            <select 
                name="priority"
                id="priority_{{ $task->id }}"
                class="w-full px-4 py-3 rounded-xl cursor-pointer bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
            >
                <option value="" disabled {{ old('priority', $task->priority) ? '' : 'selected' }}>Choose a priority</option>
                <option value="low" @selected(old('priority', $task->priority?->value) === 'low')>Low</option>
                <option value="medium" @selected(old('priority', $task->priority?->value) === 'medium')>Medium</option>
                <option value="high" @selected(old('priority', $task->priority?->value) === 'high')>High</option>
            </select>
            @error('priority')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="status_{{ $task->id }}" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Status</label>
            <select 
                name="status"
                id="status_{{ $task->id }}"
                class="w-full px-4 py-3 rounded-xl cursor-pointer bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
            >
                <option value="" disabled {{ old('status', $task->status) ? '' : 'selected' }}>Choose task status</option>
                <option value="in_progress" @selected(old('status', $task->status?->value) === 'in_progress')>In Progress</option>
                <option value="completed" @selected(old('status', $task->status?->value) === 'completed')>Completed</option>
            </select>
            @error('status')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
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
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl cursor-pointer transition-all duration-200"
            >
                Save Task
            </button>
        </div>
    </form>
</x-modal>
@endif