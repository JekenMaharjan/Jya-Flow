@if (session()->has('success') || session()->has('error'))
    <div 
        x-data="{ show: true }" 
        x-init="setTimeout(() => show = false, 4000)" 
        x-show="show"
        id="toast-box"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-5 right-5 z-50 flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg border border-gray-700 dark:text-gray-400 dark:bg-gray-800"
        role="alert"
    >
        @if (session('success'))
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                ✓
            </div>
            <div class="ms-3 text-sm font-normal text-gray-800 dark:text-gray-200">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                ✕
            </div>
            <div class="ms-3 text-sm font-normal text-gray-800 dark:text-gray-200">
                {{ session('error') }}
            </div>
        @endif

        <button 
            onclick="document.getElementById('toast-box').remove()" 
            type="button" 
            class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 cursor-pointer hover:text-gray-900 rounded-lg p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
        >
            ✕
        </button>
    </div>
@endif