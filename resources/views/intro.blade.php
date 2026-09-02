@extends('layouts.app')

@section('content')
<div class="bg-slate-800 border-2 border-slate-700 p-10 rounded-2xl">
    <p class="text-center font-lobster text-5xl p-5 mb-5">
        A platform built for a new way of working
    </p>

    <p class="text-md font-roboto text-center bg-slate-700/70 p-5 rounded-xl">
        From chaotic to-do lists to effortless execution, stream line your projects, align your team, and take total control of your time with an all-in-one task management workspace built to turn your big ideas into finished work.
    </p>

    <span class="flex justify-center items-center m-5 gap-5">
        <p class="text-md font-lobster">Get started in less than 1 minute.</p>
        <!-- Getting Started Button -->
        @auth
        <a
            href="{{ route('tasks') }}"
            class="bg-indigo-600 rounded-xl inline-block px-4 py-2 text-sm cursor-pointer hover:bg-indigo-500"
        >
            Dashboard
        </a>
        @endauth

        @guest
        <a
            href="{{ route('register') }}"
            class="bg-indigo-600 rounded-xl inline-block px-4 py-2 text-sm cursor-pointer hover:bg-indigo-500"
        >
            Get Started
        </a>
        @endguest
    </span>

    <p class="text-md font-roboto text-center bg-slate-700/70 p-5 rounded-xl">
        A workflow system designed to add tasks, track tasks progress and manage the tasks.
        <span class="font-bold">Jya-Flow</span> helps individuals to organize daily workflows and tasks and ensures tasks complete on time.
    </p>
</div>
@endsection