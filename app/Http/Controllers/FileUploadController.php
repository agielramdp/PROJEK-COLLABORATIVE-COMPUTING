<?php

namespace App\Http\Controllers;

use App\Models\AcademicSetting;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class FileUploadController extends Controller
{
    /**
     * Dapatkan Aturan Validasi File Unggahan (Item 9)
     * GET /api/admin/upload-rules
     */
    public function getRules()
    {
        $maxSizeMb = (int) AcademicSetting::getVal('max_file_upload_size_mb', 5);
        $allowedExtStr = (string) AcademicSetting::getVal('allowed_file_types', 'pdf,jpg,jpeg,png,docx,xlsx,csv');
        $allowedExts = array_map('trim', explode(',', strtolower($allowedExtStr)));

        return response()->json([
            'success' => true,
            'rules'   => [
                'max_file_size_mb'   => $maxSizeMb,
                'max_file_size_kb'   => $maxSizeMb * 1024,
                'max_file_size_bytes'=> $maxSizeMb * 1024 * 1024,
                'allowed_extensions' => $allowedExts,
                'allowed_types_hint' => 'PDF, Dokumen Office (DOCX, XLSX, CSV), dan Gambar (JPG, PNG)',
            ],
        ]);
    }

    /**
     * Upload File dengan Validasi Ukuran dan Jenis File (Item 9)
     * POST /api/admin/upload-file
     */
    public function upload(Request $request)
    {
        $maxSizeMb = (int) AcademicSetting::getVal('max_file_upload_size_mb', 5);
        $maxSizeKb = $maxSizeMb * 1024;
        $maxSizeBytes = $maxSizeKb * 1024;

        $allowedExtStr = (string) AcademicSetting::getVal('allowed_file_types', 'pdf,jpg,jpeg,png,docx,xlsx,csv');
        $allowedExts = array_map('trim', explode(',', strtolower($allowedExtStr)));

        // 1. Validasi dasar keberadaan file
        if (!$request->hasFile('file')) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang diunggah.',
            ], 422);
        }

        $file = $request->file('file');

        if (!$file->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'File yang diunggah rusak atau tidak valid: ' . $file->getErrorMessage(),
            ], 422);
        }

        // 2. Validasi Ukuran File (Max File Size)
        $fileSizeBytes = $file->getSize();
        if ($fileSizeBytes > $maxSizeBytes) {
            $actualMb = round($fileSizeBytes / (1024 * 1024), 2);
            return response()->json([
                'success' => false,
                'message' => "Ukuran file ({$actualMb} MB) melebihi batas maksimal yang diizinkan ({$maxSizeMb} MB).",
                'max_allowed_mb' => $maxSizeMb,
                'file_size_mb'   => $actualMb,
            ], 422);
        }

        // 3. Validasi Ekstensi & Tipe File (MIME / Extension)
        $extension = strtolower($file->getClientOriginalExtension());
        if (empty($extension) || !in_array($extension, $allowedExts)) {
            return response()->json([
                'success' => false,
                'message' => "Jenis file (.{$extension}) tidak diizinkan! Hanya ekstensi berikut yang diperbolehkan: {$allowedExtStr}.",
                'allowed_extensions' => $allowedExts,
                'uploaded_extension' => $extension,
            ], 422);
        }

        // 4. Validasi kategori opsional
        $category = $request->input('category', 'general');
        $validCategories = ['avatar', 'document', 'announcement', 'import', 'general'];
        if (!in_array($category, $validCategories)) {
            $category = 'general';
        }

        try {
            $originalName = $file->getClientOriginalName();
            $mimeType     = $file->getMimeType();

            // Generate nama file unik yang aman
            $safeFileName = time() . '_' . Str::random(10) . '.' . $extension;
            $folderPath   = 'uploads/' . $category;

            $path = $file->storeAs($folderPath, $safeFileName, 'public');
            $url  = asset('storage/' . $path);

            AuditLogger::log('UPLOAD_FILE', 'Files', [
                'original_name' => $originalName,
                'saved_as'      => $safeFileName,
                'category'      => $category,
                'size_bytes'    => $fileSizeBytes,
                'mime_type'     => $mimeType,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'File berhasil divalidasi dan diunggah.',
                'data'    => [
                    'original_name' => $originalName,
                    'file_name'     => $safeFileName,
                    'category'      => $category,
                    'size'          => round($fileSizeBytes / 1024, 2) . ' KB',
                    'size_bytes'    => $fileSizeBytes,
                    'mime_type'     => $mimeType,
                    'path'          => $path,
                    'url'           => $url,
                ],
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunggah file: ' . $e->getMessage(),
            ], 500);
        }
    }
}
