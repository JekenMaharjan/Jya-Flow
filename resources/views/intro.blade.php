@extends('layouts.app')

@section('title', 'Welcome to Task Manager')

@section('content')
<div class="max-w-6xl mx-auto my-8 bg-slate-900 text-slate-100 rounded-3xl p-8 shadow-2xl border border-slate-800">
    
    <!-- Top Bar with Auth Actions -->
    <div class="flex items-center justify-between pb-8 mb-8 border-b border-slate-800">
        <!-- Brand Badge -->
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center shadow-lg shadow-indigo-500/25">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="text-lg font-bold text-white tracking-tight">Task Manager</span>
        </div>

        <!-- Auth Navigation Links -->
        <div class="flex items-center space-x-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 transition-all border border-slate-700">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800 transition-all">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-all shadow-md shadow-indigo-600/30">
                            Register
                        </a>
                    @endif
                @endauth
            @else
                <!-- Generic Links if route helpers are not setup -->
                <a href="/login" class="px-4 py-2 rounded-xl text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800 transition-all">
                    Log in
                </a>
                <a href="/register" class="px-4 py-2 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-all shadow-md shadow-indigo-600/30">
                    Register
                </a>
            @endif
        </div>
    </div>

    <!-- Hero Section -->
    <div class="text-center max-w-3xl mx-auto space-y-6 py-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span>Streamline Your Workflow</span>
        </div>

        <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-200 to-slate-400 bg-clip-text text-transparent">
            Organize work, boost focus, and achieve more every day.
        </h1>

        <p class="text-lg text-slate-400 leading-relaxed">
            Our Task Manager helps individuals and teams prioritize tasks, track progress seamlessly, and keep all your project goals in one central dashboard.
        </p>

        <div class="flex items-center justify-center gap-4 pt-4">
            <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold transition-all shadow-lg shadow-indigo-600/30">
                Get Started Free
            </a>
            <a href="#features" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold border border-slate-700 transition-all">
                Explore Features
            </a>
        </div>
    </div>

    <!-- Feature Cards Section -->
    <div id="features" class="grid md:grid-cols-3 gap-6 pt-16 border-t border-slate-800 mt-12">
        
        <!-- Feature 1 -->
        <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 hover:border-indigo-500/50 transition-all">
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Smart Categorization</h3>
            <p class="text-slate-400 text-sm leading-relaxed">Organize tasks into distinct categories, assign priorities, and tag items to keep your workload clear and manageable.</p>
        </div>

        <!-- Feature 2 -->
        <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 hover:border-indigo-500/50 transition-all">
            <div class="w-12 h-12 rounded-xl bg-violet-500/10 text-violet-400 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Deadline Tracking</h3>
            <p class="text-slate-400 text-sm leading-relaxed">Set clear due dates and time frames so you never miss an important project deadline again.</p>
        </div>

        <!-- Feature 3 -->
        <div class="p-6 rounded-2xl bg-slate-800/50 border border-slate-700/50 hover:border-indigo-500/50 transition-all">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Real-time Progress</h3>
            <p class="text-slate-400 text-sm leading-relaxed">Track completed, pending, and in-progress tasks visually with real-time status updates.</p>
        </div>

    </div>

</div>
@endsection