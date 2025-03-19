<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    private const UPLOAD_DIR = 'uploads';

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,png' // Limite à certains types
            ]);

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs(self::UPLOAD_DIR, $fileName, 'public');

            return response()->json([
                'message' => 'Fichier téléchargé avec succès',
                'file' => [
                    'name' => $fileName,
                    'url' => Storage::url($path) // Utilisation de "url" au lieu de "path"
                ]
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du téléchargement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getFiles()
    {
        try {
            $files = Storage::disk('public')->files(self::UPLOAD_DIR);
            
            $fileList = array_map(function ($file) {
                return [
                    'name' => basename($file),
                    'url' => Storage::url($file)
                ];
            }, $files);

            return response()->json($fileList, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des fichiers',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}