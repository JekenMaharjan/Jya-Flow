@props([
    'name',
    'title' => null,
    'maxWidth' => 'max-w-2xl'
])

<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    style="display: none;"
    @open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    @close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <!-- Dark Backdrop overlay -->
    <div 
        x-show="open"
        x-transition.opacity
        @click="open = false" 
        class="fixed inset-0 bg-slate-950/80 backdrop-blur-xs"
    ></div>

    <!-- Modal Container -->
    <div 
        x-show="open"
        x-transition
        class="relative z-10 w-full {{ $maxWidth }} max-h-[90vh] flex flex-col rounded-xl bg-slate-800 text-white border border-white/10 shadow-2xl overflow-hidden"
    >
        <!-- Modal Header -->
        @if ($title)
            <div class="p-6 pb-4 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-bold text-2xl font-lobster">{{ $title }}</h3>
                <button @click="open = false" type="button" class="text-slate-400 hover:text-white transition-colors cursor-pointer">
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