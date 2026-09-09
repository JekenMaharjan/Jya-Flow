<!-- Modal to Add Task -->
<dialog id="taskModal" class="fixed inset-0 m-auto p-6 w-2xl rounded-xl backdrop-blur-lg bg-slate-700/50 text-white backdrop:bg-black/50">

    <!-- Add Task Form -->
    <form action="{{ route('tasks.store') }}" method="POST" class="flex flex-col gap-3 w-full font-roboto" enctype="multipart/form-data">
        @csrf

        <!-- Title -->
        <h3 class="font-bold text-3xl font-lobster mb-5">Add Your Task Here!</h3>

        <!-- Title -->
        <div class="relative flex-1">
            <label for="title" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Title</label>
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
            <label for="description" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Description</label>
            <textarea name="description" placeholder="Add description" value="{{ old('description') }}" rows="5" class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200 resize-none" required></textarea>
            @error('description')
                <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- File Upload -->
        <div class="relative flex-1">
            <label for="filename" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Upload File</label>
            <input 
                type="file" 
                name="files[]"
                class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer transition-all duration-200"
                multiple
            >
            @error('filename')
                <p class="absolute -bottom-5 left-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Due Date and Time -->
        <div class="relative flex-1">
            <label for="due_at" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Due Date</label>
            <p class="text-slate-400 font-light text-xs mb-3">Current time in UTC : {{ now()->format('Y-m-d \a\t h:i A') }}</p>
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

        <!-- Priority -->
        <div class="relative flex-1">
            <label for="priority" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Priority</label>
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
        <!-- <div class="relative flex-1">
            <label for="status" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Status</label>
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
        </div> -->

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