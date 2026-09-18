@props([
    'name',
    'title' => null,
    'maxWidth' => 'max-w-2xl'
])

<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    @open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    @close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs"
>
    <!-- Modal Container with vertical scrolling support -->
    <div 
        @click.outside="open = false"
        class="w-full {{ $maxWidth }} max-h-[90vh] flex flex-col rounded-xl backdrop-blur-lg bg-slate-800/90 text-white border border-white/10 shadow-2xl"
    >
        <!-- Modal Header -->
        @if ($title)
            <div class="p-6 pb-4 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-bold text-2xl font-lobster">{{ $title }}</h3>
                <button @click="open = false" type="button" class="text-slate-400 hover:text-white transition-colors">
                    ✕
                </button>
            </div>
        @endif

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 overflow-y-auto flex-1 font-roboto">
            {{ $slot }}
        </div>
    </div>
</div>