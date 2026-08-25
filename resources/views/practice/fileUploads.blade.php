@extends('layouts.app')

@section('content')
<div class="p-4 bg-gray-700 rounded-xl w-full">
    <div class="flex flex-col items-centre justify-center">
        <div>
            <div class="card flex flex-col gap-5">
                <div class="card-header bg-primary text-white text-2xl font-weight-bold text-center">
                    Upload Your File Here!
                </div>

                <div class="card-body text-center">
                    <!-- Display Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Upload Form -->
                    <form action="{{ route('upload.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center justify-center">
                        @csrf

                        <div class="mb-3 flex flex-col">
                            <!-- <label for="document" class="form-label">Select Your Document</label> -->
                            <input 
                                type="file" 
                                class="form-control @error('document') is-invalid @enderror border border-gray-500 rounded-xl p-2 cursor-pointer text-gray-400" 
                                id="document" 
                                name="document"
                                required
                            >
                        </div>

                        <button type="submit" class="bg-blue-500 p-2 rounded-xl cursor-pointer">
                            Upload File
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <hr class="my-6 text-gray-500">

        {{-- Uploaded Images Gallery --}}
        <div class="flex flex-col">
            <h3 class="text-xl font-semibold mb-5 text-center">Uploaded Gallery</h3>

            @if(count($files) > 0)
                <!-- <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4"> -->
                <div class="grid grid-cols-4 gap-4">
                    @foreach($files as $file)
                        <div class="border rounded-lg p-3 bg-gray-50 shadow-sm flex flex-col justify-between">
                            <div class="w-full h-48 bg-gray-200/60 rounded-md mb-2 flex items-center justify-center overflow-hidden p-2">
                                <img src="{{ asset('storage/' . $file->file_path) }}" 
                                    alt="Uploaded Image"
                                    width="150"
                                    class="object-contain">
                            </div>

                            <!-- Filename -->
                            <p class="text-xs text-gray-500 truncate text-center">
                                {{ pathinfo($file->file_path, PATHINFO_FILENAME) }}
                            </p>

                            <!-- Update and Delete Buttons -->
                            <div class="flex justify-around m-2">
                                <!-- Update Button -->
                                <button class="bg-blue-500 text-white px-2 py-1 rounded-md font-sans cursor-pointer"
                                    onclick="document.getElementById('edit-modal-{{ $file->file }}').showModal()"
                                >
                                    Update
                                </button>

                                <!-- Native HTML Dialog -->
                                <dialog id="edit-modal-{{ $file->file }}"
                                    class="m-auto p-5 rounded-md bg-gray-600 text-white border-2 border-gray-800"
                                    >
                                    <h3 class="text-xl text-center mb-5">Update File</h3>

                                    <form action="{{ route('upload.edit', $file->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-5">
                                            <input 
                                                type="file" 
                                                class="form-control @error('document') is-invalid @enderror border border-gray-500 rounded-xl p-2 cursor-pointer text-gray-400" 
                                                id="document" 
                                                name="document"
                                                required
                                            >
                                        </div>

                                        <div class="flex justify-around">
                                            <button 
                                                type="submit"
                                                class="bg-blue-500 py-1 px-3 rounded-md cursor-pointer"
                                            >
                                                Upload & Save
                                            </button>

                                            <!-- Close button using built-in form method="dialog" -->
                                            <button 
                                                type="button" 
                                                class="bg-red-500 py-1 px-3 rounded-md cursor-pointer"
                                                onclick="document.getElementById('edit-modal-{{ $file->file }}').close()"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </dialog>

                                <!-- Delete Form -->
                                <form action="{{ route('upload.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded-md font-sans cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 italic text-center">No images uploaded yet.</p>
            @endif
        </div>

    </div>
</div>
@endsection