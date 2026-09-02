<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\json;

class FileUploadController extends Controller
{
    // GET: Retrieve all files
    public function uploadUI()
    {
        // Get all files from the database - newest first
        $files = File::latest()->get();
        
        // Pass them to the view
        return view('practice.fileUploads', compact('files'));
    }

    // POST: Upload file
    public function store(UploadRequest $request)
    {
        // Validate incoming request
        $request->validated();

        // Check if files were uploaded
        if ($request->hasFile('files')) {
            // Foreach loop through each file
            foreach ($request->file('files') as $file) {
                // Store each file in the 'public/uploads' directory
                $path = $file->store('uploads', 'public');
                File::create(['filename' => $path]);
            }

            // Return HTTP response with success flash message
            return back()->with('success', 'Files uploaded successfully!');
        }

        return back()->with('error', 'Files upload failed.');
    }

    // PUT: Update file
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

    // DELETE: Delete file
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
