<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show($filename)
    {
        $path = public_path('uploads/profile_image/' . $filename);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Image not found.'], 404);
        }

        return response()->file($path);
    }

    public function showProfileImages($imagename)
    {
        try {
            $filePath = 'uploads/profile_image/' . $imagename;
    
            // Check if the file exists in the storage
            if (Storage::disk('public')->exists($filePath)) {
                // Get the full path to the file
                $path = storage_path('app/public/' . $filePath);
    
                // Return the file as a response with the correct content type
                return response()->file($path);
            } else {
                // File does not exist
                return response()->json(['error' => 'File not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle any other errors
            return response()->json(['error' => 'Failed to read file: ' . $e->getMessage()], 500);
        }
    }

    public function showAssetImages($imagename)
    {
        try {
            $filePath = 'uploads/assets/thumbnail_image/' . $imagename;
    
            // Check if the file exists in the storage
            if (Storage::disk('public')->exists($filePath)) {
                // Get the full path to the file
                $path = storage_path('app/public/' . $filePath);
    
                // Return the file as a response with the correct content type
                return response()->file($path);
            } else {
                // File does not exist
                return response()->json(['error' => 'File not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle any other errors
            return response()->json(['error' => 'Failed to read file: ' . $e->getMessage()], 500);
        }
    }
}
