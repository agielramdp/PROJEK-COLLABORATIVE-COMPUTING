<?php

namespace App\Http\Controllers;

use App\Models\AcademicSetting;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AcademicSettingController extends Controller
{
    /**
     * Dapatkan Seluruh Parameter Global Akademik (Item 22)
     * GET /api/admin/settings
     */
    public function index()
    {
        try {
            $settings = AcademicSetting::all()->groupBy('group');

            return response()->json([
                'success' => true,
                'data'    => $settings,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Dapatkan Nilai Satu Parameter Tertentu
     * GET /api/admin/settings/{key}
     */
    public function show($key)
    {
        try {
            $setting = AcademicSetting::where('key', $key)->first();
            if (!$setting) {
                return response()->json(['success' => false, 'message' => "Pengaturan '{$key}' tidak ditemukan."], 404);
            }

            return response()->json([
                'success' => true,
                'key'     => $setting->key,
                'value'   => AcademicSetting::getVal($key),
                'meta'    => $setting,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Parameter Global (Single atau Bulk) (Item 22)
     * PUT /api/admin/settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings'   => 'required|array',
            'settings.*' => 'nullable',
        ], [
            'settings.required' => 'Data pengaturan wajib dikirimkan dalam format pasangan key-value.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $updated = [];
            foreach ($request->settings as $key => $value) {
                $setting = AcademicSetting::where('key', $key)->first();
                if ($setting) {
                    $setting->value = is_array($value) ? json_encode($value) : (string) $value;
                    $setting->save();
                    $updated[$key] = AcademicSetting::getVal($key);
                } else {
                    // Buat setting baru jika belum ada
                    $newSetting = AcademicSetting::create([
                        'key'       => $key,
                        'value'     => is_array($value) ? json_encode($value) : (string) $value,
                        'data_type' => is_numeric($value) ? 'number' : (is_bool($value) ? 'boolean' : 'string'),
                        'group'     => 'custom',
                    ]);
                    $updated[$key] = $newSetting->value;
                }
            }

            AuditLogger::log('UPDATE_ACADEMIC_SETTINGS', 'Settings', [
                'updated_keys' => array_keys($updated),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Parameter global akademik berhasil diperbarui!',
                'data'    => $updated,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
