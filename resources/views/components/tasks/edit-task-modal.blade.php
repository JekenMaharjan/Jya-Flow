@props(['task', 'members' => []])

@php
    $labelClass = "block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2";
    $inputClass = "w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 transition duration-200";
@endphp

<!-- Dynamic Modal Name using Task ID -->
<x-modal name="edit-task-modal-{{ $task->id }}" title="Edit Task">
    <form action="{{ route('tasks.change', $task) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 w-full">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label for="title_{{ $task->id }}" class="{{ $labelClass }}">Title</label>
            <input 
                type="text" 
                name="title" 
                id="title_{{ $task->id }}" 
                placeholder="Add title" 
                value="{{ old('title', $task->title) }}" 
                required 
                class="{{ $inputClass }}"
            >
            @error('title') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div>
            <label for="description_{{ $task->id }}" class="{{ $labelClass }}">Description</label>
            <textarea 
                name="description" 
                id="description_{{ $task->id }}" 
                placeholder="Add description" 
                rows="4" 
                required 
                class="{{ $inputClass }} resize-none"
            >{{ old('description', $task->description) }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- File Upload -->
        <div>
            <label for="files_{{ $task->id }}" class="{{ $labelClass }}">Upload Files</label>
            <input 
                type="file" 
                name="files[]" 
                id="files_{{ $task->id }}" 
                multiple
                class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer transition"
            >
            @error('files') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
            @error('files.*') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- Due Date -->
        <div>
            <label for="due_at_{{ $task->id }}" class="{{ $labelClass }}">Due Date</label>
            <input 
                type="datetime-local" 
                name="due_at" 
                id="due_at_{{ $task->id }}" 
                value="{{ old('due_at', $task->due_at_nepal?->format('Y-m-d\TH:i')) }}" 
                required 
                class="{{ $inputClass }}"
            >
            @error('due_at') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- Priority -->
        <div>
            <label for="priority_{{ $task->id }}" class="{{ $labelClass }}">Priority</label>
            <select name="priority" id="priority_{{ $task->id }}" required class="{{ $inputClass }} bg-slate-900">
                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
                    <option 
                        value="{{ $value }}" 
                        class="bg-slate-900 text-white"
                        {{ old('priority', $task->priority?->value ?? $task->priority) === $value ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('priority') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="status_{{ $task->id }}" class="{{ $labelClass }}">Status</label>
            <select name="status" id="status_{{ $task->id }}" required class="{{ $inputClass }} bg-slate-900">
                @foreach(['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $value => $label)
                    <option 
                        value="{{ $value }}" 
                        class="bg-slate-900 text-white"
                        {{ old('status', $task->status?->value ?? $task->status) === $value ? 'selected' : '' }}
                    >
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- Collaborators -->
        <div>
            <label class="{{ $labelClass }}">Collaborators</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm max-h-36 overflow-y-auto p-1">
                @forelse ($members as $member)
                    @php
                        $assignedEmails = old('collaborator_email', $task->collaborators?->pluck('email')->toArray() ?? []);
                    @endphp
                    <label class="flex items-center gap-2 p-2 rounded-lg bg-white/5 border border-white/5 text-slate-300 hover:bg-white/10 cursor-pointer transition">
                        <input
                            type="checkbox"
                            name="collaborator_email[]"
                            value="{{ $member->email }}"
                            @checked(in_array($member->email, $assignedEmails))
                            class="rounded border-white/10 text-indigo-600 focus:ring-indigo-500 bg-slate-900"
                        >
                        <span class="truncate">{{ $member->name }}</span>
                    </label>
                @empty
                    <p class="text-xs text-slate-500">No members available.</p>
                @endforelse
            </div>
            
            @error('collaborator_email') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
            @error('collaborator_email.*') <p class="mt-1 text-xs text-red-400 font-medium">{{ $message }}</p> @enderror
        </div>

        <!-- Modal Actions -->
        <div class="flex items-center justify-end gap-3 pt-4 mt-2 border-t border-white/10">
            <button 
                type="button" 
                @click="$dispatch('close-modal', 'edit-task-modal-{{ $task->id }}')"
                class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white text-sm font-medium rounded-xl transition cursor-pointer"
            >
                Cancel
            </button>
            <button 
                type="submit" 
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition cursor-pointer"
            >
                Save Task
            </button>
        </div>
    </form>
</x-modal>