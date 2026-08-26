<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management App</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tangerine&family=Iceberg&family=Stardos+Stencil&family=Bigshot+One&family=Caacupe+One&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="w-full h-full font-['Inter',sans-serif] bg-slate-950 text-slate-100">
    <!-- Header -->
    <header class="flex p-5 items-center justify-around bg-slate-900">
        <!-- Logo & Title -->
        <a 
            href="{{ route('intro') }}"
            class="flex items-center gap-2"
        >
            <x-phosphor-check-square-fill class="h-10 w-10 text-indigo-500"/>
            <h2 class="text-2xl font-bold font-stardos">Task Management App</h2>
        </a>

        <!-- Auth Nagivation Links -->
        <div class="flex gap-5">
            <!-- Login Button -->
            <a 
                href="{{ route('login') }}"
                class="bg-indigo-600 inline-block rounded-xl px-4 py-2 text-sm cursor-pointer hover:bg-indigo-500"
            >
                Log in
            </a>

            <!-- Register Button -->
            <a
                href="{{ route('register') }}"
                class="bg-indigo-600 inline-block rounded-xl px-4 py-2 text-sm cursor-pointer hover:bg-indigo-500"
            >
                Register
            </a>
        </div>
    </header>

    <hr class="text-slate-600">

    <!-- Main -->
    <main class="p-10">
        @yield('content')
    </main>
</body>
</html>