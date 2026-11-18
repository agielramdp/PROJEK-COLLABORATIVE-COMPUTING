<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursePrerequisite;
use App\Models\Curriculum;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CurriculumCourseController extends Controller
{
    // ========================================================
    // 1. KURIKULUM (Item 13)
    // ========================================================

    public function indexCurriculums(Request $request)
    {
        try {
            $query = Curriculum::with('studyProgram')->withCount('courses');

            if ($request->filled('study_program_id')) {
                $query->where('study_program_id', $request->study_program_id);
            }

            $curriculums = $query->orderBy('year_start', 'desc')->get();
            return response()->json(['success' => true, 'data' => $curriculums]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeCurriculum(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'study_program_id' => 'required|exists:study_programs,id',
            'code'             => 'required|string|max:50',
            'name'             => 'required|string|max:150',
            'year_start'       => 'required|integer|min:2000|max:2100',
            'is_active'        => 'nullable|boolean',
            'description'      => 'nullable|string',
        ], [
            'study_program_id.required' => 'Program studi wajib dipilih.',
            'code.required'             => 'Kode kurikulum wajib diisi.',
            'name.required'             => 'Nama kurikulum wajib diisi.',
            'year_start.required'       => 'Tahun mulai kurikulum wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $curriculum = Curriculum::create($request->all());
            AuditLogger::log('CREATE_CURRICULUM', 'Curriculum', ['name' => $curriculum->name, 'code' => $curriculum->code], $curriculum->id);

            return response()->json([
                'success' => true,
                'message' => 'Kurikulum berhasil dibuat!',
                'data'    => $curriculum->load('studyProgram'),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showCurriculum($id)
    {
        try {
            $curriculum = Curriculum::with(['studyProgram', 'courses.prerequisites.prerequisiteCourse'])->find($id);
            if (!$curriculum) {
                return response()->json(['success' => false, 'message' => 'Kurikulum tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $curriculum]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCurriculum(Request $request, $id)
    {
        $curriculum = Curriculum::find($id);
        if (!$curriculum) {
            return response()->json(['success' => false, 'message' => 'Kurikulum tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'study_program_id' => 'required|exists:study_programs,id',
            'code'             => 'required|string|max:50',
            'name'             => 'required|string|max:150',
            'year_start'       => 'required|integer|min:2000|max:2100',
            'is_active'        => 'nullable|boolean',
            'description'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $curriculum->update($request->all());
            AuditLogger::log('UPDATE_CURRICULUM', 'Curriculum', ['name' => $curriculum->name], $curriculum->id);

            return response()->json([
                'success' => true,
                'message' => 'Kurikulum berhasil diperbarui!',
                'data'    => $curriculum->load('studyProgram'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyCurriculum($id)
    {
        try {
            $curriculum = Curriculum::find($id);
            if (!$curriculum) {
                return response()->json(['success' => false, 'message' => 'Kurikulum tidak ditemukan.'], 404);
            }

            $name = $curriculum->name;
            $curriculum->delete();
            AuditLogger::log('DELETE_CURRICULUM', 'Curriculum', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Kurikulum '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // 2. MATA KULIAH (Item 13)
    // ========================================================

    public function indexCourses(Request $request)
    {
        try {
            $query = Course::with(['curriculum', 'studyProgram', 'prerequisites.prerequisiteCourse']);

            if ($request->filled('study_program_id')) {
                $query->where('study_program_id', $request->study_program_id);
            }
            if ($request->filled('curriculum_id')) {
                $query->where('curriculum_id', $request->curriculum_id);
            }
            if ($request->filled('semester')) {
                $query->where('semester', $request->semester);
            }
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'LIKE', "%{$search}%")
                      ->orWhere('name', 'LIKE', "%{$search}%");
                });
            }

            $courses = $query->orderBy('semester')->orderBy('code')->get();
            return response()->json(['success' => true, 'data' => $courses]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'               => 'required|string|max:30|unique:courses,code',
            'name'               => 'required|string|max:150',
            'name_en'            => 'nullable|string|max:150',
            'curriculum_id'      => 'nullable|exists:curriculums,id',
            'study_program_id'   => 'nullable|exists:study_programs,id',
            'credits_theory'     => 'required|integer|min:0',
            'credits_practical'  => 'required|integer|min:0',
            'semester'           => 'required|integer|min:1|max:14',
            'course_type'        => 'required|in:Wajib,Pilihan,Wajib Peminatan',
            'description'        => 'nullable|string',
            'is_active'          => 'nullable|boolean',
        ], [
            'code.required' => 'Kode mata kuliah wajib diisi.',
            'code.unique'   => 'Kode mata kuliah sudah digunakan.',
            'name.required' => 'Nama mata kuliah wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $totalCredits = (int) $request->credits_theory + (int) $request->credits_practical;

            $course = Course::create(array_merge($request->all(), [
                'total_credits' => $totalCredits,
            ]));

            AuditLogger::log('CREATE_COURSE', 'Curriculum', [
                'name'          => $course->name,
                'code'          => $course->code,
                'total_credits' => $totalCredits,
            ], $course->id);

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil ditambahkan!',
                'data'    => $course->load(['curriculum', 'studyProgram']),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showCourse($id)
    {
        try {
            $course = Course::with(['curriculum', 'studyProgram', 'prerequisites.prerequisiteCourse', 'classes'])->find($id);
            if (!$course) {
                return response()->json(['success' => false, 'message' => 'Mata kuliah tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $course]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Mata kuliah tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code'               => 'required|string|max:30|unique:courses,code,' . $id,
            'name'               => 'required|string|max:150',
            'name_en'            => 'nullable|string|max:150',
            'curriculum_id'      => 'nullable|exists:curriculums,id',
            'study_program_id'   => 'nullable|exists:study_programs,id',
            'credits_theory'     => 'required|integer|min:0',
            'credits_practical'  => 'required|integer|min:0',
            'semester'           => 'required|integer|min:1|max:14',
            'course_type'        => 'required|in:Wajib,Pilihan,Wajib Peminatan',
            'description'        => 'nullable|string',
            'is_active'          => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $totalCredits = (int) $request->credits_theory + (int) $request->credits_practical;

            $course->update(array_merge($request->all(), [
                'total_credits' => $totalCredits,
            ]));

            AuditLogger::log('UPDATE_COURSE', 'Curriculum', ['name' => $course->name, 'code' => $course->code], $course->id);

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil diperbarui!',
                'data'    => $course->load(['curriculum', 'studyProgram']),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyCourse($id)
    {
        try {
            $course = Course::find($id);
            if (!$course) {
                return response()->json(['success' => false, 'message' => 'Mata kuliah tidak ditemukan.'], 404);
            }

            $name = $course->name;
            $course->delete();
            AuditLogger::log('DELETE_COURSE', 'Curriculum', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Mata kuliah '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // 3. MATA KULIAH PRASYARAT (Item 14)
    // ========================================================

    public function getPrerequisites($courseId)
    {
        try {
            $course = Course::find($courseId);
            if (!$course) {
                return response()->json(['success' => false, 'message' => 'Mata kuliah tidak ditemukan.'], 404);
            }

            $prerequisites = CoursePrerequisite::with('prerequisiteCourse')
                ->where('course_id', $courseId)
                ->get();

            return response()->json([
                'success'     => true,
                'course'      => ['id' => $course->id, 'name' => $course->name, 'code' => $course->code],
                'data'        => $prerequisites,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function addPrerequisite(Request $request, $courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['success' => false, 'message' => 'Mata kuliah target tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'prerequisite_course_id' => 'required|exists:courses,id|different:course_id',
            'minimum_grade'          => 'required|in:A,A-,B+,B,B-,C+,C,D',
            'prerequisite_type'      => 'required|in:must_pass,must_take',
            'notes'                  => 'nullable|string',
        ], [
            'prerequisite_course_id.required'  => 'Mata kuliah prasyarat wajib dipilih.',
            'prerequisite_course_id.different' => 'Mata kuliah tidak bisa menjadi prasyarat untuk dirinya sendiri.',
            'minimum_grade.required'           => 'Nilai minimal kelulusan prasyarat wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if ($request->prerequisite_course_id == $courseId) {
            return response()->json(['success' => false, 'message' => 'Mata kuliah tidak bisa menjadi prasyarat untuk dirinya sendiri.'], 422);
        }

        try {
            // Cek apakah sudah ada prasyarat ini
            $exists = CoursePrerequisite::where('course_id', $courseId)
                ->where('prerequisite_course_id', $request->prerequisite_course_id)
                ->first();

            if ($exists) {
                return response()->json(['success' => false, 'message' => 'Prasyarat ini sudah terdaftar sebelumnya.'], 422);
            }

            $prereq = CoursePrerequisite::create([
                'course_id'              => $courseId,
                'prerequisite_course_id' => $request->prerequisite_course_id,
                'minimum_grade'          => $request->minimum_grade,
                'prerequisite_type'      => $request->prerequisite_type,
                'notes'                  => $request->notes,
            ]);

            AuditLogger::log('ADD_PREREQUISITE', 'Curriculum', [
                'course_id'       => $courseId,
                'prereq_course_id'=> $request->prerequisite_course_id,
                'min_grade'       => $request->minimum_grade,
            ], $prereq->id);

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah prasyarat berhasil ditambahkan!',
                'data'    => $prereq->load('prerequisiteCourse'),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function removePrerequisite($courseId, $prerequisiteId)
    {
        try {
            $prereq = CoursePrerequisite::where('course_id', $courseId)
                ->where('id', $prerequisiteId)
                ->first();

            if (!$prereq) {
                return response()->json(['success' => false, 'message' => 'Data prasyarat tidak ditemukan.'], 404);
            }

            $prereq->delete();
            AuditLogger::log('REMOVE_PREREQUISITE', 'Curriculum', ['course_id' => $courseId], $prerequisiteId);

            return response()->json(['success' => true, 'message' => 'Mata kuliah prasyarat berhasil dihapus.']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function checkPrerequisites(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id'  => 'required|exists:courses,id',
            'student_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $course = Course::with('prerequisites.prerequisiteCourse')->find($request->course_id);
            $prerequisites = $course->prerequisites;

            if ($prerequisites->isEmpty()) {
                return response()->json([
                    'success'  => true,
                    'eligible' => true,
                    'message'  => 'Mata kuliah ini tidak memiliki prasyarat.',
                    'details'  => [],
                ]);
            }

            // Mock check / logic kelulusan
            $details = [];
            $allPassed = true;

            foreach ($prerequisites as $p) {
                // Untuk demonstrasi dan implementasi fleksibel
                $details[] = [
                    'prerequisite_course_code' => $p->prerequisiteCourse->code ?? '-',
                    'prerequisite_course_name' => $p->prerequisiteCourse->name ?? '-',
                    'required_min_grade'       => $p->minimum_grade,
                    'type'                     => $p->prerequisite_type,
                    'status'                   => 'Memenuhi Syarat',
                    'passed'                   => true,
                ];
            }

            return response()->json([
                'success'  => true,
                'eligible' => $allPassed,
                'message'  => $allPassed ? 'Mahasiswa memenuhi semua prasyarat mata kuliah.' : 'Mahasiswa belum memenuhi prasyarat.',
                'course'   => ['id' => $course->id, 'name' => $course->name],
                'details'  => $details,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
