<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    public function uploadUI()
    {
        // Fetch File Eloquent Models from DB (Not raw strings)
        $files = File::all();

        return view('practice.fileUploads', compact('files'));
    }

    public function store(UploadRequest $request)
    {
        // Retrieve validated data (automatically runs rules defined in UploadRequest)
        $request->validated();

        if ($request->hasFile('document')) {
            // Get the uploaded file instance
            $file = $request->file('document');

            // Store the file safely using storeAs
            $filePath = $file->storeAs('uploads', $file->getClientOriginalName(), 'public');

            // Save file metadata in the database
            $fileRecord = File::create([
                'name' => basename($filePath),
                'file_path' => $filePath
            ]);

            // Return HTTP response with success flash message
            return redirect()->back()->with('success', 'File uploaded successfully!');
        }

        return redirect()->back()->with('error', 'File upload failed.');
    }

    public function edit(Request $request, File $file)
    {
        // Validate request using the incoming $request object
        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,jpg,png,docx', 'max:5120']
        ]);

        // Check if a new file was uploaded in the request
        if ($request->hasFile('document')) {

            // Delete the OLD physical file if it exists
            if (Storage::disk($file->disk ?? 'public')->exists($file->file_path)) {
                Storage::disk($file->disk ?? 'public')->delete($file->file_path);
            }

            // Get uploaded file instance
            $newFile = $request->file('document');

            // Save the NEW physical file
            $newFilePath = $newFile->storeAs('uploads', $newFile->getClientOriginalName(), 'public');

            // Update database record with new detials
            $file->update([
                'name' => basename($newFilePath),
                'file_path' => $newFilePath,
            ]);
        }

        return redirect()->back()->with('success', 'File updated successfully!');
    }

    public function destroy(File $file)
    {
        // 1. Delete physical file from disk (make sure property matches your DB column)
        if (Storage::disk($file->disk ?? 'public')->exists($file->file_path)) {
            Storage::disk($file->disk ?? 'public')->delete($file->file_path);
        }

        // 2. Delete database record
        $file->delete();

        return back()->with('success', 'File deleted successfully!');
    }
}
