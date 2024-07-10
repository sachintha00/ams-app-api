<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileWriteController extends Controller
{
    public function writeToFile(Request $request)
    {
        $validatedData = $request->validate([
            'file_name' => 'required|string',
            'db_name' => 'required|string',
        ]);

        $fileName = $validatedData['file_name'];
        $dbName = $validatedData['db_name'];

        try {
            $filePath = 'public/tenant/' . $fileName . '.txt';

            if (Storage::exists($filePath)) {
                $existingContent = Storage::get($filePath);
                if ($existingContent == $dbName) {
                    return response()->json(['message' => 'File content unchanged.'], 200);
                }
            }

            Storage::put($filePath, $dbName);

            return response()->json(['message' => 'File written successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to write to file: ' . $e->getMessage()], 500);
        }
    }


    public function readFile(Request $request)
    {
        $validatedData = $request->validate([
            'file_name' => 'required|string'
        ]);

        $fileName = $validatedData['file_name'];

        try {
            $filePath = 'public/tenant/' . $fileName . '.txt';

            if (Storage::exists($filePath)) {
                $content = Storage::get($filePath);
                if ($content !== false) {
                    return response()->json(['message' => 'File read successfully!', 'data' => $content], 200);
                } else {
                    return response()->json(['error' => 'File exists but is empty.'], 500);
                }
            } else {
                return response()->json(['error' => 'File not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to read file: ' . $e->getMessage()], 500);
        }
    }

}