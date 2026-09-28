{{-- <x-modal name="add-task-modal" title="Add Your Task Here!">
    <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 w-full">
        @csrf

        <!-- Title Input -->
        <div>
            <label for="title" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Title</label>
            <input 
                type="text" 
                name="title" 
                id="title"
                placeholder="Add title"
                value="{{ old('title') }}"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition duration-200"
            >
            @error('title')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description Input -->
        <div>
            <label for="description" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Description</label>
            <textarea 
                name="description" 
                id="description"
                placeholder="Add description" 
                rows="4" 
                required 
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 resize-none transition duration-200"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- File Upload -->
        <div>
            <label for="files" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Upload Files</label>
            <input 
                type="file" 
                name="files[]"
                id="files"
                multiple
                class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm focus:outline-none focus:border-indigo-500/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer transition duration-200"
            >
            @error('files')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Due Date -->
        <div>
            <label for="due_at" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Due Date</label>
            <input 
                type="datetime-local" 
                name="due_at" 
                id="due_at"
                value="{{ old('due_at') }}"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition duration-200"
            >
            @error('due_at')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Priority -->
        <div>
            <label for="priority" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Priority</label>
            <select 
                name="priority" 
                id="priority"
                required
                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-white/10 text-white text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition duration-200"
            >
                <option value="" disabled {{ old('priority') ? '' : 'selected' }}>Choose a priority</option>
                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
            </select>
            @error('priority')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Collaborators -->
        <div>
            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Collaborators</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm max-h-36 overflow-y-auto p-1">
                @forelse ($members as $member)
                    <label class="flex items-center gap-2 p-2 rounded-lg bg-white/5 border border-white/5 text-slate-300 hover:bg-white/10 cursor-pointer transition">
                        <input
                            type="checkbox"
                            name="collaborator_email[]"
                            value="{{ $member->email }}"
                            {{ is_array(old('collaborator_email')) && in_array($member->email, old('collaborator_email')) ? 'checked' : '' }}
                            class="rounded border-white/10 text-indigo-600 focus:ring-indigo-500 bg-slate-900"
                        >
                        <span class="truncate">{{ $member->name }}</span>
                    </label>
                @empty
                    <p class="text-xs text-slate-500">No members available.</p>
                @endforelse
            </div>
            @error('collaborator_email')
                <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Modal Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 mt-2 border-t border-white/10">
            <button 
                type="button" 
                @click="$dispatch('close-modal', 'add-task-modal')"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition duration-200"
            >
                Cancel
            </button>
            <button 
                type="submit" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition duration-200"
            >
                Save Task
            </button>
        </div>
    </form>
</x-modal> --}}