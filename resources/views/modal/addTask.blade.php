<!-- Modal to Add Task -->
    <dialog id="taskModal" class="m-auto rounded-2xl p-6 bg-slate-900 text-white backdrop:bg-black/80 max-w-md w-full">
        <form action="{{ route('tasks.store') }}" method="POST" class="flex flex-col items-center gap-3 mb-8" enctype="multipart/form-data">
            @csrf

            <!-- Row 1: Title & Description -->
            <div class="flex flex-col md:flex-row gap-5 w-full">
                <!-- Title -->
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        name="title" 
                        placeholder="Add title" 
                        required
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
                    >
                    @error('title')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="relative flex-1">
                    <textarea 
                        name="description" 
                        placeholder="Add description" 
                        required
                        rows="1"
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200 resize-none"
                    >
                    </textarea>
                    @error('description')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 2: File & Due Date -->
            <div class="flex flex-col md:flex-row gap-5 w-full">
                <!-- File Upload -->
                <div class="relative flex-1">
                    <input 
                        type="file" 
                        name="file_path" 
                        required
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
                    >
                    @error('file_path')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date and Time -->
                <div class="relative flex-1">
                    <input 
                        type="datetime-local" 
                        name="due_at" 
                        required
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
                    >
                    @error('due_at')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Row 3: Priority & Status -->
            <div class="flex flex-col md:flex-row gap-5 w-full">
                <!-- Priority -->
                <div class="relative flex-1">
                    <select 
                        name="priority" 
                        id="priority"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                    >
                        <option value="" disabled selected>Choose a priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    @error('priority')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="relative flex-1">
                    <select 
                        name="status" 
                        id="status"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition-all duration-200"
                    >
                        <option value="" disabled selected>Choose task status</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                    @error('status')
                        <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 mt-2">
                <button 
                    type="button" 
                    onclick="document.getElementById('taskModal').close()"
                    class="px-5 py-2.5 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl cursor-pointer"
                >
                    Cancel
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl cursor-pointer"
                >
                    Save Task
                </button>
            </div>
        </form>
    </dialog>