<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function create()
    {
        return view('fileUploads');
    }

    public function store(Request $request)
    {
        return view('fileUploads');
    }
}
