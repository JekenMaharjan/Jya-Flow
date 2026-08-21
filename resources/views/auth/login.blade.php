@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto my-6 p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">
    
    <!-- Header -->
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-white tracking-wide">Welcome Back</h2>
        <p class="text-sm text-slate-400 mt-1">Sign in to manage your tasks</p>
    </div>

    <x-form-errors />

    <x-logout-success />

    <form action="{{ route('api.login') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Email Address
            </label>
            <input 
                id="email"
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                placeholder="name@example.com"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border @error('email') border-red-500/80 @else border-white/10 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
            >
            <!-- Field-level error (shows only if email fails basic email format validation) -->
            @error('email') 
                @unless(old('email'))
                    <p class="mt-1.5 text-xs text-red-400 font-medium">{{ $message }}</p> 
                @endunless
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Password
            </label>
            <input 
                id="password"
                type="password" 
                name="password" 
                placeholder="••••••••"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border @error('password') border-red-500/80 @else border-white/10 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
            >
            @error('password') 
                <p class="mt-1.5 text-xs text-red-400 font-medium">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="flex items-center space-x-2.5 cursor-pointer select-none">
                <input 
                    id="remember"
                    type="checkbox" 
                    name="remember"
                    class="w-4 h-4 rounded bg-white/5 border-white/20 text-indigo-600 focus:ring-indigo-500/30 focus:ring-offset-0 focus:ring-2 checked:bg-indigo-600 transition duration-150"
                >
                <span class="text-xs font-medium text-slate-300">Remember me</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full py-3.5 px-4 mt-2 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 active:scale-[0.98] border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
        >
            Log In
        </button>
    </form>

    <!-- Sign Up Link -->
    <p class="text-center text-xs text-slate-400 mt-6">
        Don't have an account? 
        <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium underline underline-offset-4">
            Create one
        </a>
    </p>

</div>
@endsection