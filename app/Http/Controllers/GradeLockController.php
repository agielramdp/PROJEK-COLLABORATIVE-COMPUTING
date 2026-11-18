<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\CourseClass;
use App\Models\GradeLock;
use App\Models\GradeSubmissionDeadline;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class GradeLockController extends Controller
{
    // ========================================================
    // 1. PENGELOLAAN PEMBUKAAN KEMBALI KUNCI NILAI (Item 12)
    // ========================================================

    /**
     * Daftar Status Kunci Nilai per Kelas Kuliah
     * GET /api/admin/grades/locks
     */
    public function indexLocks(Request $request)
    {
        try {
            $query = CourseClass::with(['course', 'academicYear', 'classLecturers.lecturer', 'gradeLock']);

            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            }

            $classes = $query->get();

            $data = $classes->map(function ($c) {
                $lock = $c->gradeLock;
                $isAllowed = $lock ? $lock->isGradeInputAllowed() : true;

                return [
                    'class_id'       => $c->id,
                    'class_name'     => $c->name,
                    'course_code'    => $c->course->code ?? '-',
                    'course_name'    => $c->course->name ?? '-',
                    'academic_year'  => $c->academicYear ? "{$c->academicYear->year_name} ({$c->academicYear->semester})" : '-',
                    'is_locked'      => $lock ? $lock->is_locked : false,
                    'is_allowed_now' => $isAllowed,
                    'unlock_until'   => $lock && $lock->unlock_until ? $lock->unlock_until->format('Y-m-d H:i') : null,
                    'unlock_reason'  => $lock ? $lock->unlock_reason : null,
                    'unlocked_by'    => $lock && $lock->unlockedBy ? $lock->unlockedBy->name : null,
                ];
            });

            return response()->json(['success' => true, 'data' => $data]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Buka Kunci Nilai Kelas Tertentu (Unlock)
     * POST /api/admin/grades/locks/{classId}/unlock
     */
    public function unlockGrade(Request $request, $classId)
    {
        $class = CourseClass::with('course')->find($classId);
        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Kelas kuliah tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'unlock_reason' => 'required|string|max:255',
            'unlock_days'   => 'nullable|integer|min:1|max:30', // Buka selama berapa hari
            'unlock_until'  => 'nullable|date|after:now',
        ], [
            'unlock_reason.required' => 'Alasan pembukaan kunci nilai wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            // Hitung tanggal kedaluwarsa unlock
            if ($request->filled('unlock_until')) {
                $unlockUntil = Carbon::parse($request->unlock_until);
            } else {
                $days = (int) $request->input('unlock_days', 3);
                $unlockUntil = Carbon::now()->addDays($days);
            }

            $user = Auth::user();

            $gradeLock = GradeLock::updateOrCreate(
                ['course_class_id' => $classId],
                [
                    'is_locked'           => false,
                    'unlocked_by_user_id' => $user ? $user->id : null,
                    'unlocked_at'         => now(),
                    'unlock_until'        => $unlockUntil,
                    'unlock_reason'       => $request->unlock_reason,
                ]
            );

            AuditLogger::log('UNLOCK_GRADE', 'Grades', [
                'class_id'      => $classId,
                'class_name'    => $class->name,
                'course'        => $class->course->name ?? '-',
                'unlock_reason' => $request->unlock_reason,
                'unlock_until'  => $unlockUntil->toDateTimeString(),
            ], $classId);

            return response()->json([
                'success' => true,
                'message' => "Penguncian nilai kelas '{$class->name}' berhasil dibuka hingga " . $unlockUntil->format('d-m-Y H:i') . ".",
                'data'    => $gradeLock,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Kunci Kembali Nilai Kelas (Relock)
     * POST /api/admin/grades/locks/{classId}/lock
     */
    public function lockGrade(Request $request, $classId)
    {
        $class = CourseClass::find($classId);
        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Kelas kuliah tidak ditemukan.'], 404);
        }

        try {
            $gradeLock = GradeLock::updateOrCreate(
                ['course_class_id' => $classId],
                [
                    'is_locked'     => true,
                    'locked_at'     => now(),
                    'unlock_until'  => null,
                ]
            );

            AuditLogger::log('LOCK_GRADE', 'Grades', ['class_id' => $classId, 'class_name' => $class->name], $classId);

            return response()->json([
                'success' => true,
                'message' => "Nilai untuk kelas '{$class->name}' telah berhasil dikunci kembali.",
                'data'    => $gradeLock,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // 2. PEMBATASAN WAKTU INPUT NILAI DOSEN (Item 23)
    // ========================================================

    /**
     * Daftar Jadwal Batas Waktu Input Nilai
     * GET /api/admin/grade-deadlines
     */
    public function indexDeadlines(Request $request)
    {
        try {
            $query = GradeSubmissionDeadline::with('academicYear');

            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            }

            $deadlines = $query->orderBy('start_date', 'desc')->get();

            $data = $deadlines->map(function ($d) {
                return array_merge($d->toArray(), [
                    'is_open_now' => $d->isOpenNow(),
                ]);
            });

            return response()->json(['success' => true, 'data' => $data]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tambah Batas Waktu Input Nilai
     * POST /api/admin/grade-deadlines
     */
    public function storeDeadline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_component'  => 'required|in:Tugas,Kuis,UTS,UAS,Nilai Akhir',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'is_active'        => 'nullable|boolean',
            'notes'            => 'nullable|string',
        ], [
            'academic_year_id.required' => 'Tahun akademik wajib dipilih.',
            'grade_component.required'  => 'Komponen nilai wajib dipilih (Tugas, Kuis, UTS, UAS, Nilai Akhir).',
            'start_date.required'       => 'Tanggal mulai input nilai wajib diisi.',
            'end_date.required'         => 'Batas akhir input nilai wajib diisi.',
            'end_date.after'            => 'Batas akhir harus setelah tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $deadline = GradeSubmissionDeadline::create($request->all());

            AuditLogger::log('CREATE_GRADE_DEADLINE', 'Grades', [
                'component'  => $deadline->grade_component,
                'start_date' => $deadline->start_date,
                'end_date'   => $deadline->end_date,
            ], $deadline->id);

            return response()->json([
                'success' => true,
                'message' => 'Batas waktu input nilai berhasil ditetapkan!',
                'data'    => $deadline->load('academicYear'),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Batas Waktu Input Nilai
     * PUT /api/admin/grade-deadlines/{id}
     */
    public function updateDeadline(Request $request, $id)
    {
        $deadline = GradeSubmissionDeadline::find($id);
        if (!$deadline) {
            return response()->json(['success' => false, 'message' => 'Jadwal batas waktu tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'grade_component'  => 'required|in:Tugas,Kuis,UTS,UAS,Nilai Akhir',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'is_active'        => 'nullable|boolean',
            'notes'            => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $deadline->update($request->all());
            AuditLogger::log('UPDATE_GRADE_DEADLINE', 'Grades', ['component' => $deadline->grade_component], $deadline->id);

            return response()->json([
                'success' => true,
                'message' => 'Batas waktu input nilai berhasil diperbarui!',
                'data'    => $deadline->load('academicYear'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Batas Waktu Input Nilai
     * DELETE /api/admin/grade-deadlines/{id}
     */
    public function destroyDeadline($id)
    {
        try {
            $deadline = GradeSubmissionDeadline::find($id);
            if (!$deadline) {
                return response()->json(['success' => false, 'message' => 'Jadwal batas waktu tidak ditemukan.'], 404);
            }

            $deadline->delete();
            AuditLogger::log('DELETE_GRADE_DEADLINE', 'Grades', [], $id);

            return response()->json(['success' => true, 'message' => 'Batas waktu input nilai berhasil dihapus.']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Cek apakah saat ini dosen diizinkan menginput nilai untuk kelas tertentu
     * GET /api/admin/grade-deadlines/check-status
     */
    public function checkGradeInputStatus(Request $request)
    {
        try {
            $classId = $request->input('class_id');
            $component = $request->input('component', 'Nilai Akhir');

            $class = $classId ? CourseClass::find($classId) : null;
            $academicYearId = $class ? $class->academic_year_id : ($request->input('academic_year_id') ?: optional(AcademicYear::getActive())->id);

            // 1. Cek apakah ada unlock khusus admin untuk kelas ini
            if ($class) {
                $lock = GradeLock::where('course_class_id', $class->id)->first();
                if ($lock && $lock->isGradeInputAllowed()) {
                    return response()->json([
                        'success'            => true,
                        'is_allowed'         => true,
                        'reason'             => 'Izin Khusus Admin (Kunci Nilai Terbuka)',
                        'unlock_until'       => $lock->unlock_until ? $lock->unlock_until->toIso8601String() : null,
                        'admin_reason'       => $lock->unlock_reason,
                        'source'             => 'admin_unlock',
                    ]);
                }
            }

            // 2. Cek apakah dalam rentang deadline jadwal input nilai
            if ($academicYearId) {
                $deadline = GradeSubmissionDeadline::where('academic_year_id', $academicYearId)
                    ->where('grade_component', $component)
                    ->where('is_active', true)
                    ->first();

                if ($deadline && $deadline->isOpenNow()) {
                    return response()->json([
                        'success'    => true,
                        'is_allowed' => true,
                        'reason'     => "Periode Resmi Penginputan {$component} Sedang Berlangsung",
                        'deadline'   => $deadline->end_date->toIso8601String(),
                        'source'     => 'deadline_period',
                    ]);
                }
            }

            return response()->json([
                'success'    => true,
                'is_allowed' => false,
                'message'    => "Penginputan {$component} saat ini ditutup. Batas waktu telah lewat atau dikunci oleh Admin.",
                'source'     => 'locked_or_expired',
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
