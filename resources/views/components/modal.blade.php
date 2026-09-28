@props([
    'name',
    'title' => null,
    'maxWidth' => 'max-w-2xl',
    'show' => false,
])

<div
    x-data="{ open: @js($show || $errors->any()) }"
    x-show="open"
    x-cloak
    style="display: none;"
    @open-modal.window="open = ($event.detail === @js($name))"
    @close-modal.window="if ($event.detail === @js($name)) open = false"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-xs"
>
    <!-- Modal Box -->
    <div
        x-show="open"
        x-transition.scale.95
        @click.outside="open = false"
        class="relative w-full {{ $maxWidth }} max-h-[90vh] flex flex-col rounded-xl bg-slate-800 text-white border border-white/10 shadow-2xl overflow-hidden"
    >
        @if ($title)
            <div class="p-6 pb-4 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-bold text-2xl font-lobster">{{ $title }}</h3>
                <button type="button" @click="open = false" class="text-slate-400 hover:text-white text-xl cursor-pointer">
                    &times;
                </button>
            </div>
        @endif

        <div class="p-6 overflow-y-auto flex-1 font-roboto">
            {{ $slot }}
        </div>
    </div>
</div>