<?php

namespace App\Http\Controllers;

use App\Models\AcademicAdvisor;
use App\Models\AcademicYear;
use App\Models\ClassLecturer;
use App\Models\CourseClass;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ClassAssignmentController extends Controller
{
    // ========================================================
    // 1. KELAS MATA KULIAH & DOSEN PENGAMPU (Item 15)
    // ========================================================

    public function indexCourseClasses(Request $request)
    {
        try {
            $query = CourseClass::with(['course.studyProgram', 'academicYear', 'classLecturers.lecturer']);

            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            }
            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            $classes = $query->orderBy('course_id')->orderBy('name')->get();
            return response()->json(['success' => true, 'data' => $classes]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeCourseClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id'        => 'required|exists:courses,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'required|string|max:50', // Misal: Kelas A, IF-1A
            'quota'            => 'required|integer|min:1',
            'is_active'        => 'nullable|boolean',
        ], [
            'course_id.required'        => 'Mata kuliah wajib dipilih.',
            'academic_year_id.required' => 'Tahun akademik wajib dipilih.',
            'name.required'             => 'Nama kelas wajib diisi (contoh: Kelas A).',
            'quota.required'            => 'Kuota mahasiswa wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $courseClass = CourseClass::create($request->all());
            AuditLogger::log('CREATE_COURSE_CLASS', 'Classes', ['name' => $courseClass->name], $courseClass->id);

            return response()->json([
                'success' => true,
                'message' => 'Kelas perkuliahan berhasil dibuat!',
                'data'    => $courseClass->load(['course', 'academicYear']),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showCourseClass($id)
    {
        try {
            $courseClass = CourseClass::with(['course', 'academicYear', 'classLecturers.lecturer', 'schedules.room'])->find($id);
            if (!$courseClass) {
                return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $courseClass]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCourseClass(Request $request, $id)
    {
        $courseClass = CourseClass::find($id);
        if (!$courseClass) {
            return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'course_id'        => 'required|exists:courses,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'required|string|max:50',
            'quota'            => 'required|integer|min:1',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $courseClass->update($request->all());
            AuditLogger::log('UPDATE_COURSE_CLASS', 'Classes', ['name' => $courseClass->name], $courseClass->id);

            return response()->json([
                'success' => true,
                'message' => 'Kelas perkuliahan berhasil diperbarui!',
                'data'    => $courseClass->load(['course', 'academicYear']),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyCourseClass($id)
    {
        try {
            $courseClass = CourseClass::find($id);
            if (!$courseClass) {
                return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
            }

            $name = $courseClass->name;
            $courseClass->delete();
            AuditLogger::log('DELETE_COURSE_CLASS', 'Classes', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Kelas '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Dapatkan daftar dosen pengampu kelas tertentu
     * GET /api/admin/course-classes/{classId}/lecturers
     */
    public function getCourseClassLecturers($classId)
    {
        try {
            $class = CourseClass::with(['classLecturers.lecturer', 'course'])->find($classId);
            if (!$class) {
                return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
            }

            return response()->json([
                'success'    => true,
                'class_name' => $class->name,
                'course'     => $class->course->name ?? '-',
                'data'       => $class->classLecturers,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tetapkan Dosen Pengampu untuk Kelas Kuliah (Item 15)
     * POST /api/admin/course-classes/{classId}/lecturers
     */
    public function assignClassLecturer(Request $request, $classId)
    {
        $class = CourseClass::with('course')->find($classId);
        if (!$class) {
            return response()->json(['success' => false, 'message' => 'Kelas tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'lecturer_id'       => 'required|exists:users,id',
            'role'              => 'required|in:Koordinator,Anggota,Praktika',
            'teaching_load_sks' => 'nullable|integer|min:1',
        ], [
            'lecturer_id.required' => 'Dosen wajib dipilih.',
            'role.required'        => 'Peran dosen pengampu wajib dipilih (Koordinator, Anggota, Praktika).',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $lecturer = User::find($request->lecturer_id);
            if (!in_array($lecturer->role, ['Dosen Pengajar', 'Dosen Wali', 'Admin'])) {
                return response()->json(['success' => false, 'message' => 'Pengguna yang dipilih bukan berstatus Dosen.'], 422);
            }

            $sks = $request->teaching_load_sks ?? ($class->course->total_credits ?? 3);

            $assignment = ClassLecturer::updateOrCreate(
                [
                    'course_class_id' => $classId,
                    'lecturer_id'     => $request->lecturer_id,
                ],
                [
                    'role'              => $request->role,
                    'teaching_load_sks' => $sks,
                ]
            );

            AuditLogger::log('ASSIGN_CLASS_LECTURER', 'Classes', [
                'class_name'    => $class->name,
                'lecturer_name' => $lecturer->name,
                'role'          => $request->role,
                'sks'           => $sks,
            ], $assignment->id);

            return response()->json([
                'success' => true,
                'message' => "Dosen '{$lecturer->name}' berhasil ditugaskan sebagai {$request->role} untuk {$class->name}.",
                'data'    => $assignment->load('lecturer'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Dosen Pengampu dari Kelas
     * DELETE /api/admin/course-classes/{classId}/lecturers/{lecturerId}
     */
    public function removeClassLecturer($classId, $lecturerId)
    {
        try {
            $assignment = ClassLecturer::where('course_class_id', $classId)
                ->where('lecturer_id', $lecturerId)
                ->first();

            if (!$assignment) {
                return response()->json(['success' => false, 'message' => 'Penugasan dosen tidak ditemukan.'], 404);
            }

            $assignment->delete();
            AuditLogger::log('REMOVE_CLASS_LECTURER', 'Classes', ['class_id' => $classId, 'lecturer_id' => $lecturerId]);

            return response()->json(['success' => true, 'message' => 'Dosen pengampu berhasil dihapus dari kelas.']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // 2. DOSEN PEMBIMBING AKADEMIK (DOSEN WALI) (Item 16)
    // ========================================================

    /**
     * Daftar Dosen Pembimbing Akademik dan Mahasiswa Bimbingannya
     * GET /api/admin/academic-advisors
     */
    public function indexAdvisors(Request $request)
    {
        try {
            $query = AcademicAdvisor::with(['lecturer', 'student', 'academicYear']);

            if ($request->filled('lecturer_id')) {
                $query->where('lecturer_id', $request->lecturer_id);
            }
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            }

            $advisors = $query->get();
            return response()->json(['success' => true, 'data' => $advisors]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tetapkan Dosen Pembimbing Akademik bagi Mahasiswa (Single / Bulk) (Item 16)
     * POST /api/admin/academic-advisors/assign
     */
    public function assignAdvisor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lecturer_id'      => 'required|exists:users,id',
            'student_ids'      => 'required|array|min:1',
            'student_ids.*'    => 'integer|exists:users,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'notes'            => 'nullable|string',
        ], [
            'lecturer_id.required' => 'Dosen Pembimbing Akademik wajib dipilih.',
            'student_ids.required' => 'Daftar mahasiswa wajib dipilih minimal 1.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $lecturer = User::find($request->lecturer_id);
            $academicYearId = $request->academic_year_id ?: optional(AcademicYear::getActive())->id;

            DB::beginTransaction();

            $assignedCount = 0;
            foreach ($request->student_ids as $studentId) {
                // Nonaktifkan pembimbing sebelumnya jika ada
                AcademicAdvisor::where('student_id', $studentId)->update(['is_active' => false]);

                // Buat pembimbing baru
                AcademicAdvisor::create([
                    'lecturer_id'      => $lecturer->id,
                    'student_id'       => $studentId,
                    'academic_year_id' => $academicYearId,
                    'assigned_date'    => now(),
                    'is_active'        => true,
                    'notes'            => $request->notes,
                ]);

                $assignedCount++;
            }

            DB::commit();

            AuditLogger::log('ASSIGN_ACADEMIC_ADVISOR', 'Advisors', [
                'lecturer_name' => $lecturer->name,
                'student_count' => $assignedCount,
            ], $lecturer->id);

            return response()->json([
                'success' => true,
                'message' => "Berhasil menetapkan '{$lecturer->name}' sebagai Dosen Pembimbing Akademik untuk {$assignedCount} mahasiswa.",
                'assigned_count' => $assignedCount,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Penetapan Dosen Pembimbing Akademik
     * DELETE /api/admin/academic-advisors/{id}
     */
    public function removeAdvisor($id)
    {
        try {
            $advisor = AcademicAdvisor::find($id);
            if (!$advisor) {
                return response()->json(['success' => false, 'message' => 'Data bimbingan akademik tidak ditemukan.'], 404);
            }

            $advisor->delete();
            AuditLogger::log('DELETE_ACADEMIC_ADVISOR', 'Advisors', [], $id);

            return response()->json(['success' => true, 'message' => 'Penetapan Dosen Pembimbing Akademik berhasil dihapus.']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
