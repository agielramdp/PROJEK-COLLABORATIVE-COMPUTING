<?php

namespace App\Http\Controllers;

use App\Models\EducationLevel;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AcademicStructureController extends Controller
{
    // ========================================================
    // 1. FAKULTAS
    // ========================================================

    public function indexFaculties()
    {
        try {
            $faculties = Faculty::withCount(['studyPrograms'])->get();
            return response()->json(['success' => true, 'data' => $faculties]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeFaculty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'        => 'required|string|max:30|unique:faculties,code',
            'name'        => 'required|string|max:150',
            'dean_name'   => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $faculty = Faculty::create($request->all());
            AuditLogger::log('CREATE_FACULTY', 'AcademicStructure', ['name' => $faculty->name, 'code' => $faculty->code], $faculty->id);

            return response()->json(['success' => true, 'message' => 'Fakultas berhasil ditambahkan!', 'data' => $faculty], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showFaculty($id)
    {
        try {
            $faculty = Faculty::with(['studyPrograms.educationLevel'])->find($id);
            if (!$faculty) {
                return response()->json(['success' => false, 'message' => 'Fakultas tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $faculty]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateFaculty(Request $request, $id)
    {
        $faculty = Faculty::find($id);
        if (!$faculty) {
            return response()->json(['success' => false, 'message' => 'Fakultas tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code'        => 'required|string|max:30|unique:faculties,code,' . $id,
            'name'        => 'required|string|max:150',
            'dean_name'   => 'nullable|string|max:150',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $faculty->update($request->all());
            AuditLogger::log('UPDATE_FACULTY', 'AcademicStructure', ['name' => $faculty->name], $faculty->id);

            return response()->json(['success' => true, 'message' => 'Fakultas berhasil diperbarui!', 'data' => $faculty]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyFaculty($id)
    {
        try {
            $faculty = Faculty::find($id);
            if (!$faculty) {
                return response()->json(['success' => false, 'message' => 'Fakultas tidak ditemukan.'], 404);
            }

            $name = $faculty->name;
            $faculty->delete();
            AuditLogger::log('DELETE_FACULTY', 'AcademicStructure', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Fakultas '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // 2. JENJANG PENDIDIKAN
    // ========================================================

    public function indexEducationLevels()
    {
        try {
            $levels = EducationLevel::withCount(['studyPrograms'])->get();
            return response()->json(['success' => true, 'data' => $levels]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeEducationLevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'               => 'required|string|max:20|unique:education_levels,code',
            'name'               => 'required|string|max:100',
            'degree_title'       => 'nullable|string|max:50',
            'standard_credits'   => 'nullable|integer',
            'standard_semesters' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $level = EducationLevel::create($request->all());
            AuditLogger::log('CREATE_EDUCATION_LEVEL', 'AcademicStructure', ['name' => $level->name], $level->id);

            return response()->json(['success' => true, 'message' => 'Jenjang pendidikan berhasil ditambahkan!', 'data' => $level], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showEducationLevel($id)
    {
        try {
            $level = EducationLevel::find($id);
            if (!$level) {
                return response()->json(['success' => false, 'message' => 'Jenjang pendidikan tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $level]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateEducationLevel(Request $request, $id)
    {
        $level = EducationLevel::find($id);
        if (!$level) {
            return response()->json(['success' => false, 'message' => 'Jenjang pendidikan tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code'               => 'required|string|max:20|unique:education_levels,code,' . $id,
            'name'               => 'required|string|max:100',
            'degree_title'       => 'nullable|string|max:50',
            'standard_credits'   => 'nullable|integer',
            'standard_semesters' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $level->update($request->all());
            AuditLogger::log('UPDATE_EDUCATION_LEVEL', 'AcademicStructure', ['name' => $level->name], $level->id);

            return response()->json(['success' => true, 'message' => 'Jenjang pendidikan berhasil diperbarui!', 'data' => $level]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyEducationLevel($id)
    {
        try {
            $level = EducationLevel::find($id);
            if (!$level) {
                return response()->json(['success' => false, 'message' => 'Jenjang pendidikan tidak ditemukan.'], 404);
            }

            $name = $level->name;
            $level->delete();
            AuditLogger::log('DELETE_EDUCATION_LEVEL', 'AcademicStructure', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Jenjang pendidikan '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // 3. PROGRAM STUDI
    // ========================================================

    public function indexStudyPrograms(Request $request)
    {
        try {
            $query = StudyProgram::with(['faculty', 'educationLevel']);

            if ($request->filled('faculty_id')) {
                $query->where('faculty_id', $request->faculty_id);
            }
            if ($request->filled('education_level_id')) {
                $query->where('education_level_id', $request->education_level_id);
            }

            $programs = $query->get();
            return response()->json(['success' => true, 'data' => $programs]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeStudyProgram(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faculty_id'         => 'required|exists:faculties,id',
            'education_level_id' => 'required|exists:education_levels,id',
            'code'               => 'required|string|max:30|unique:study_programs,code',
            'name'               => 'required|string|max:150',
            'head_of_program'    => 'nullable|string|max:150',
            'accreditation'      => 'nullable|string|max:30',
            'is_active'          => 'nullable|boolean',
        ], [
            'faculty_id.required'         => 'Fakultas wajib dipilih.',
            'education_level_id.required' => 'Jenjang pendidikan wajib dipilih.',
            'code.required'               => 'Kode program studi wajib diisi.',
            'code.unique'                 => 'Kode program studi sudah terdaftar.',
            'name.required'               => 'Nama program studi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $program = StudyProgram::create($request->all());
            AuditLogger::log('CREATE_STUDY_PROGRAM', 'AcademicStructure', ['name' => $program->name, 'code' => $program->code], $program->id);

            return response()->json([
                'success' => true,
                'message' => 'Program studi berhasil ditambahkan!',
                'data'    => $program->load(['faculty', 'educationLevel']),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function showStudyProgram($id)
    {
        try {
            $program = StudyProgram::with(['faculty', 'educationLevel', 'curriculums', 'courses'])->find($id);
            if (!$program) {
                return response()->json(['success' => false, 'message' => 'Program studi tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $program]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStudyProgram(Request $request, $id)
    {
        $program = StudyProgram::find($id);
        if (!$program) {
            return response()->json(['success' => false, 'message' => 'Program studi tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'faculty_id'         => 'required|exists:faculties,id',
            'education_level_id' => 'required|exists:education_levels,id',
            'code'               => 'required|string|max:30|unique:study_programs,code,' . $id,
            'name'               => 'required|string|max:150',
            'head_of_program'    => 'nullable|string|max:150',
            'accreditation'      => 'nullable|string|max:30',
            'is_active'          => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $program->update($request->all());
            AuditLogger::log('UPDATE_STUDY_PROGRAM', 'AcademicStructure', ['name' => $program->name], $program->id);

            return response()->json([
                'success' => true,
                'message' => 'Program studi berhasil diperbarui!',
                'data'    => $program->load(['faculty', 'educationLevel']),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroyStudyProgram($id)
    {
        try {
            $program = StudyProgram::find($id);
            if (!$program) {
                return response()->json(['success' => false, 'message' => 'Program studi tidak ditemukan.'], 404);
            }

            $name = $program->name;
            $program->delete();
            AuditLogger::log('DELETE_STUDY_PROGRAM', 'AcademicStructure', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Program studi '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
