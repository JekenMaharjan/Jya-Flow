<!-- Global Authentication Error Banner -->
    @if ($errors->has('email') && old('email'))
        <div class="mb-6 p-3.5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-300 text-xs font-medium flex items-center gap-2.5">
            <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>These credentials do not match our records.</span>
        </div>
    @endif