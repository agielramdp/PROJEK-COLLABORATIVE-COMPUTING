<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\StudentStatus;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Throwable;

class StudentStatusController extends Controller
{
    /**
     * Daftar Status Mahasiswa
     * GET /api/admin/students/status
     */
    public function index(Request $request)
    {
        try {
            $query = User::where('role', 'Mahasiswa')->with(['studentStatuses.academicYear']);

            if ($request->filled('status') && $request->status !== 'Semua') {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('npm', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            $perPage = (int) $request->input('per_page', 10);
            $students = $query->orderBy('id', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data'    => $students->items(),
                'meta'    => [
                    'current_page' => $students->currentPage(),
                    'last_page'    => $students->lastPage(),
                    'per_page'     => $students->perPage(),
                    'total'        => $students->total(),
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Ubah Status Kemahasiswaan (Item 19)
     * POST /api/admin/students/{id}/status
     */
    public function changeStatus(Request $request, $id)
    {
        $student = User::where('id', $id)->first();
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Mahasiswa tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status'           => 'required|in:Aktif,Cuti,Drop Out,Lulus,Non-Aktif,Mangkir,Meninggal',
            'reason'           => 'nullable|string|max:500',
            'decree_number'    => 'nullable|string|max:100', // Nomor SK
            'effective_date'   => 'nullable|date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
        ], [
            'status.required' => 'Status kemahasiswaan baru wajib dipilih.',
            'status.in'       => 'Pilihan status tidak valid (Aktif, Cuti, Drop Out, Lulus, Non-Aktif, Mangkir, Meninggal).',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $oldStatus = $student->status ?? 'Aktif';
            $newStatus = $request->status;
            $academicYearId = $request->academic_year_id ?: optional(AcademicYear::getActive())->id;
            $adminUser = Auth::user();

            // 1. Simpan riwayat perubahan status
            $history = StudentStatus::create([
                'student_id'         => $student->id,
                'academic_year_id'   => $academicYearId,
                'status'             => $newStatus,
                'reason'             => $request->reason,
                'decree_number'      => $request->decree_number,
                'updated_by_user_id' => $adminUser ? $adminUser->id : null,
                'effective_date'     => $request->effective_date ? $request->effective_date : now()->toDateString(),
            ]);

            // 2. Update kolom status pada users jika ada
            if (Schema::hasColumn('users', 'status')) {
                $student->status = $newStatus;
                $student->save();
            }

            DB::commit();

            AuditLogger::log('CHANGE_STUDENT_STATUS', 'Students', [
                'student_id'    => $student->id,
                'student_name'  => $student->name,
                'npm'           => $student->npm,
                'old_status'    => $oldStatus,
                'new_status'    => $newStatus,
                'decree_number' => $request->decree_number,
                'reason'        => $request->reason,
            ], $student->id);

            return response()->json([
                'success' => true,
                'message' => "Status mahasiswa '{$student->name}' berhasil diubah dari '{$oldStatus}' menjadi '{$newStatus}'.",
                'data'    => [
                    'student_id' => $student->id,
                    'name'       => $student->name,
                    'npm'        => $student->npm,
                    'status'     => $student->status,
                    'history'    => $history,
                ],
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Dapatkan Riwayat Perubahan Status Mahasiswa
     * GET /api/admin/students/{id}/status-history
     */
    public function getHistory($id)
    {
        try {
            $student = User::find($id);
            if (!$student) {
                return response()->json(['success' => false, 'message' => 'Mahasiswa tidak ditemukan.'], 404);
            }

            $history = StudentStatus::with(['academicYear', 'updatedBy'])
                ->where('student_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'student' => [
                    'id'     => $student->id,
                    'name'   => $student->name,
                    'npm'    => $student->npm,
                    'status' => $student->status,
                ],
                'data'    => $history,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
