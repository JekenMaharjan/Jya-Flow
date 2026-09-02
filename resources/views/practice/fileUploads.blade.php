@extends('layouts.app')

@section('content')
<div class="p-4 bg-gray-700 rounded-xl w-full">
    <div class="flex flex-col items-centre justify-center">
        <div class="card flex flex-col gap-5">
            <h3 class="card-header bg-primary text-white text-2xl font-weight-bold text-center">
                Upload Your File Here!
            </h3>

            <div class="flex items-center justify-center">
                <!-- Upload Form -->
                <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center justify-center w-xl gap-5">
                    @csrf

                    <input 
                        type="file" 
                        name="files[]"
                        class="w-full px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 text-sm focus:outline-none focus:border-indigo-500/80 focus:ring-2 focus:ring-indigo-500/20 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer transition-all duration-200" 
                        multiple
                    >

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 px-4 py-2 text-sm font-bold rounded-xl cursor-pointer">
                        Upload File
                    </button>
                </form>
            </div>
        </div>

        <hr class="my-6 text-gray-500">

        <!-- Uploaded Images Gallery -->
        @if(Storage::disk('public')->exists('uploads'))
        <h2>Uploaded Files:</h2>
        <ul>
            @foreach(Storage::disk('public')->files('uploads') as $file)
                <li>
                    <a href="{{ Storage::url($file) }}" target="_blank">
                        {{ basename($file) }}
                    </a>
                </li>
            @endforeach
        </ul>
        @endif

    </div>
</div>
@endsection