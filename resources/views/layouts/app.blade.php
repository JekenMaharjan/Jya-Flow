<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jya-Flow</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=2">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400..900;1,6..96,400..900&family=Limelight&family=Lobster+Two:ital,wght@0,400;0,700;1,400;1,700&family=Monoton&family=Pinyon+Script&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="w-full h-full font-['Inter',sans-serif] bg-slate-950 text-slate-100">

    <!-- Header -->
    <header class="flex p-5 items-center justify-around bg-slate-900">

        <!-- Logo & Title -->
        <a 
            href="{{ route('intro') }}"
            class="flex items-center gap-2"
        >
            <span class="flex items-center -space-x-3">
                <x-si-jameson class="h-10 w-10 text-indigo-500 z-10 drop-shadow-md"/>
                <x-fileicon-flow class="h-10 w-10 text-indigo-400 opacity-90"/>
            </span>
            <h2 class="text-3xl font-monoton">Jya-Flow</h2>
        </a>

        @auth
        <!-- Auth Nagivation Links -->
        <div class="flex gap-5">
            <div class="flex items-center gap-3">
                <!-- User Avatar Circle -->
                <div class="w-9 h-9 rounded-full bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-300 font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <!-- Username & Email -->
                <div>
                    <h3 class="text-sm font-semibold text-white leading-none">{{ Auth::user()->name }}</h3>
                    <!-- <p class="text-xs text-slate-400 mt-1">{{ Auth::user()->email }}</p> -->
                </div>
            </div>

            <!-- Dashboard Button -->
            <a 
                href="{{ route('tasks.index') }}"
                class="py-2 px-4 mt-2 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
            >
                Dashboard
            </a>

            <!-- Logout Button / Form -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button 
                    type="submit" 
                    class="flex items-center gap-2 py-2 px-4 mt-2 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
        @endauth

        @guest
        <!-- Guest Nagivation Links -->
        <div class="flex gap-5">
            <!-- Login Button -->
            <a 
                href="{{ route('login') }}"
                class="w-full py-2 px-4 mt-2 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
            >
                Log in
            </a>

            <!-- Register Button -->
            <a
                href="{{ route('register') }}"
                class="w-full py-2 px-4 mt-2 rounded-xl text-sm font-semibold text-white bg-indigo-600/80 hover:bg-indigo-500/90 border border-indigo-400/30 shadow-lg shadow-indigo-600/30 backdrop-blur-sm transition-all duration-200 cursor-pointer"
            >
                Register
            </a>
        </div>
        @endguest
    </header>

    <hr class="text-slate-600">

    <!-- Main -->
    <main class="p-10">
        @yield('content')
    </main>

    @livewireScripts

    {{-- Flash Message Toast --}}
    @if (Session::has('success') || Session::has('info'))
        <div
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 3000)" 
            class="fixed bottom-5 right-5 z-50"
        >
            @if (Session::has('success'))
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl
                            bg-emerald-500/10
                            border border-emerald-500/20
                            text-emerald-400
                            shadow-xl shadow-black/30
                            backdrop-blur-md">

                    <span class="text-sm font-medium">
                        {{ Session::get('success') }}
                    </span>

                    <button
                        type="button"
                        @click="show = false"
                        class="text-emerald-400/70 hover:text-emerald-300 text-lg leading-none cursor-pointer"
                    >
                        &times;
                    </button>
                </div>
            @elseif (Session::has('info'))
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl
                            bg-slate-500/10
                            border border-slate-500/20
                            text-slate-300
                            shadow-xl shadow-black/30
                            backdrop-blur-md">

                    <span class="text-sm font-medium">
                        {{ Session::get('info') }}
                    </span>

                    <button
                        type="button"
                        @click="show = false"
                        class="text-slate-400 hover:text-white text-lg leading-none cursor-pointer"
                    >
                        &times;
                    </button>
                </div>
            @endif
        </div>
    @endif
</body>
</html>