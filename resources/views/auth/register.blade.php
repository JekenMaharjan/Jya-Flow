@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto my-6 p-8 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl shadow-black/50">
    
    <!-- Header -->
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-bold text-white tracking-wide">Create an Account</h2>
        <p class="text-sm text-slate-400 mt-1">Get started with your task manager</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Full Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Full Name
            </label>
            <input 
                id="name"
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                placeholder="Jane Doe"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border @error('name') border-red-500/80 @else border-white/10 @enderror text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
            >
            @error('name') 
                <p class="mt-1.5 text-xs text-red-400 font-medium">{{ $message }}</p> 
            @enderror
        </div>

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
            @error('email') 
                <p class="mt-1.5 text-xs text-red-400 font-medium">{{ $message }}</p> 
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

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                Confirm Password
            </label>
            <input 
                id="password_confirmation"
                type="password" 
                name="password_confirmation" 
                placeholder="••••••••"
                required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 focus:bg-white/10 transition-all duration-200"
            >
        </div>

        <!-- Submit Button -->
        <button 
            type="submit" 
            class="w-full py-3.5 px-4 mt-2 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 active:scale-[0.98] border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
        >
            Register
        </button>
    </form>

    <!-- Sign In Link -->
    <p class="text-center text-xs text-slate-400 mt-6">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium underline underline-offset-4">
            Sign in
        </a>
    </p>

</div>
@endsection