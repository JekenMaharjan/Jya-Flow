@extends('layouts.app')

@section('content')
<div class="p-4 bg-gray-700 rounded-xl w-md">
    <div class="flex items-centre justify-center">
        <div class="">
            <div class="card flex flex-col gap-5">
                <div class="card-header bg-primary text-white text-2xl font-weight-bold text-center">
                    Upload Your File Here!
                </div>

                <div class="card-body">
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
                                class="form-control @error('document') is-invalid @enderror border rounded-xl p-2 cursor-pointer" 
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
    </div>
</div>
@endsection