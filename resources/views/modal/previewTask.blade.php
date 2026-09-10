
<dialog id="previewTaskModal_{{ $task->id }}" class="fixed inset-0 m-auto p-6 w-2xl rounded-xl backdrop-blur-lg bg-slate-700/50 text-white backdrop:bg-black/50">
    
    <!-- Update Form -->
    <form action="{{ route('tasks.change', $task) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-4">
            <h3 class="text-3xl font-lobster">Task Details</h3>

            <!-- Input fields -->
            <div class="flex flex-col items-center gap-4">

                <!-- Title -->
                <div class="flex flex-col w-full relative">
                    <label for="title" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Title</label>
                    <input 
                        type="text"
                        name="title"
                        placeholder="Add Title..."
                        value="{{ $task->title }}"
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
                    >
                    @error('title')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="flex flex-col w-full relative">
                    <label for="description" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Description</label>
                    <textarea rows="4" placeholder="Add Description..." name="description" id="description" class="w-full max-h-48 overflow-y-auto px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200">{{ old('description', $task->description) }}</textarea>
                    @error('description')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="flex flex-col w-full gap-3 relative">
                    <label for="filename" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">Upload File</label>

                    <!-- Display current file if it exists -->
                    @if(!empty($task->filename))
                        <div class="mb-2 flex flex-col gap-2 justify-between px-4 py-2 bg-slate-800/80 border border-white/10 rounded-xl">
                            @foreach($task->filename as $file)    
                                <div class="flex gap-5">
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

                    <!-- File input for new file upload -->
                    <input 
                        type="file"
                        id="files_{{ $task->id }}"
                        name="files[]"
                        multiple
                        class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer transition-all duration-200"
                    >
                    
                    @error('file_path')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date and Time-->
                <div class="flex w-full gap-3 relative">   
                    <div class="flex flex-col w-full">
                        <label for="due_at" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Due Date</label>
                        <p class="text-slate-400 font-light text-xs mb-3">Current time in UTC : {{ now()->format('Y-m-d \a\t h:i A') }}</p>
                        <input 
                            type="datetime-local"
                            name="due_at"
                            id="due_at"
                            value="{{ old('due_at', $task->due_at ? $task->due_at->format('Y-m-d\TH:i') : '') }}"
                            class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
                        >
                    </div>
                    @error('due_at')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div class="flex flex-col w-full relative">
                    <label for="priority" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Priority</label>
                    <select 
                        name="priority"
                        id="priority"
                        class="w-full px-4 py-3 rounded-xl cursor-pointer bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                    >
                        <option value="" disabled {{ old('priority', $task->priority) ? '' : 'selected' }}>Choose a priority</option>
                        <option value="low" @selected(old('priority', $task->priority?->value) === 'low')>Low</option>
                        <option value="medium" @selected(old('priority', $task->priority?->value) === 'medium')>Medium</option>
                        <option value="high" @selected(old('priority', $task->priority?->value) === 'high')>High</option>
                    </select>
                    @error('priority')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="flex flex-col w-full relative">       
                    <label for="status" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Status</label>
                    <select 
                        name="status"
                        id="status"
                        class="w-full px-4 py-3 rounded-xl cursor-pointer bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                    >
                        <option value="" disabled {{ old('status', $task->status) ? '' : 'selected' }}>Choose task status</option>
                        <option value="in_progress" @selected(old('status', $task->status?->value) === 'in_progress')>In Progress</option>
                        <option value="completed" @selected(old('status', $task->status?->value) === 'completed')>Completed</option>
                    </select>
                    @error('status')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 font-semibold">
                <!-- Cancel -->
                <button 
                    type="button"
                    class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm rounded-xl cursor-pointer"
                    onclick="document.getElementById('previewTaskModal_{{ $task->id }}').close()"
                    >
                    Cancel
                </button>

                <!-- Save -->
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-xl cursor-pointer"
                >
                    Save Task
                </button>
            </div>
        </div> 
    </form>
</dialog>