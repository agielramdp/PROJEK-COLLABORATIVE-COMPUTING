<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AcademicYearController extends Controller
{
    /**
     * Daftar Tahun Akademik
     * GET /api/admin/academic-years
     */
    public function index()
    {
        try {
            $years = AcademicYear::orderBy('year_name', 'desc')
                                 ->orderBy('semester', 'asc')
                                 ->get();

            return response()->json([
                'success' => true,
                'data'    => $years,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Dapatkan Semester & Tahun Akademik Aktif
     * GET /api/admin/academic-years/active
     */
    public function getActive()
    {
        try {
            $activeYear = AcademicYear::getActive();

            return response()->json([
                'success' => true,
                'data'    => $activeYear,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tambah Tahun Akademik Baru
     * POST /api/admin/academic-years
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'year_name'   => 'required|string|max:30', // Misal: 2026/2027
            'semester'    => 'required|in:Ganjil,Genap,Antara',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_active'   => 'nullable|boolean',
            'description' => 'nullable|string',
        ], [
            'year_name.required' => 'Nama tahun akademik wajib diisi (contoh: 2026/2027).',
            'semester.required'  => 'Semester wajib dipilih (Ganjil, Genap, Antara).',
            'end_date.after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $isActive = $request->boolean('is_active');
            if ($isActive) {
                // Nonaktifkan tahun akademik lain
                AcademicYear::query()->update(['is_active' => false]);
            }

            $academicYear = AcademicYear::create([
                'year_name'   => trim($request->year_name),
                'semester'    => $request->semester,
                'is_active'   => $isActive,
                'start_date'  => $request->start_date,
                'end_date'    => $request->end_date,
                'description' => $request->description,
            ]);

            DB::commit();

            AuditLogger::log('CREATE_ACADEMIC_YEAR', 'AcademicYear', [
                'year_name' => $academicYear->year_name,
                'semester'  => $academicYear->semester,
                'is_active' => $academicYear->is_active,
            ], $academicYear->id);

            return response()->json([
                'success' => true,
                'message' => 'Tahun akademik berhasil dibuat!',
                'data'    => $academicYear,
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Detail Tahun Akademik
     * GET /api/admin/academic-years/{id}
     */
    public function show($id)
    {
        try {
            $year = AcademicYear::with(['krsPeriods', 'courseClasses'])->find($id);
            if (!$year) {
                return response()->json(['success' => false, 'message' => 'Tahun akademik tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $year]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Tahun Akademik
     * PUT /api/admin/academic-years/{id}
     */
    public function update(Request $request, $id)
    {
        $academicYear = AcademicYear::find($id);
        if (!$academicYear) {
            return response()->json(['success' => false, 'message' => 'Tahun akademik tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'year_name'   => 'required|string|max:30',
            'semester'    => 'required|in:Ganjil,Genap,Antara',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_active'   => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $isActive = $request->boolean('is_active');
            if ($isActive && !$academicYear->is_active) {
                AcademicYear::where('id', '!=', $id)->update(['is_active' => false]);
            }

            $academicYear->update($request->all());

            DB::commit();

            AuditLogger::log('UPDATE_ACADEMIC_YEAR', 'AcademicYear', [
                'year_name' => $academicYear->year_name,
                'semester'  => $academicYear->semester,
            ], $academicYear->id);

            return response()->json([
                'success' => true,
                'message' => 'Tahun akademik berhasil diperbarui!',
                'data'    => $academicYear,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Set Semester & Tahun Akademik Aktif
     * POST /api/admin/academic-years/{id}/set-active
     */
    public function setActive($id)
    {
        try {
            $year = AcademicYear::find($id);
            if (!$year) {
                return response()->json(['success' => false, 'message' => 'Tahun akademik tidak ditemukan.'], 404);
            }

            DB::beginTransaction();

            // Nonaktifkan semua
            AcademicYear::query()->update(['is_active' => false]);

            // Aktifkan yang dipilih
            $year->is_active = true;
            $year->save();

            DB::commit();

            AuditLogger::log('SET_ACTIVE_SEMESTER', 'AcademicYear', [
                'academic_year_id' => $year->id,
                'year_name'        => $year->year_name,
                'semester'         => $year->semester,
            ], $year->id);

            return response()->json([
                'success' => true,
                'message' => "Tahun Akademik {$year->year_name} Semester {$year->semester} telah diaktifkan sebagai semester aktif!",
                'data'    => $year,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Tahun Akademik
     * DELETE /api/admin/academic-years/{id}
     */
    public function destroy($id)
    {
        try {
            $year = AcademicYear::find($id);
            if (!$year) {
                return response()->json(['success' => false, 'message' => 'Tahun akademik tidak ditemukan.'], 404);
            }

            if ($year->is_active) {
                return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus tahun akademik yang sedang aktif.'], 422);
            }

            $label = "{$year->year_name} ({$year->semester})";
            $year->delete();

            AuditLogger::log('DELETE_ACADEMIC_YEAR', 'AcademicYear', ['label' => $label], $id);

            return response()->json(['success' => true, 'message' => "Tahun akademik {$label} berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
