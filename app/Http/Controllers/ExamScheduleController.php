<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ExamInvigilator;
use App\Models\ExamSchedule;
use App\Models\User;
use App\Services\AuditLogger;
use App\Services\ScheduleConflictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ExamScheduleController extends Controller
{
    // ========================================================
    // 1. JADWAL UTS & UAS (Item 20)
    // ========================================================

    public function index(Request $request)
    {
        try {
            $query = ExamSchedule::with([
                'courseClass.course',
                'room.building',
                'academicYear',
                'invigilators.invigilator',
            ]);

            if ($request->filled('exam_type')) {
                $query->where('exam_type', $request->exam_type);
            }
            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            }
            if ($request->filled('exam_date')) {
                $query->where('exam_date', $request->exam_date);
            }

            $exams = $query->orderBy('exam_date')->orderBy('start_time')->get();

            return response()->json(['success' => true, 'data' => $exams]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_class_id'  => 'required|exists:course_classes,id',
            'exam_type'        => 'required|in:UTS,UAS',
            'exam_date'        => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room_id'          => 'required|exists:rooms,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'notes'            => 'nullable|string',
        ], [
            'course_class_id.required' => 'Kelas kuliah wajib dipilih.',
            'exam_type.required'       => 'Jenis ujian wajib dipilih (UTS atau UAS).',
            'exam_date.required'       => 'Tanggal ujian wajib diisi.',
            'start_time.required'      => 'Jam mulai ujian wajib diisi.',
            'end_time.required'        => 'Jam selesai ujian wajib diisi.',
            'end_time.after'           => 'Jam selesai ujian harus setelah jam mulai.',
            'room_id.required'         => 'Ruangan ujian wajib dipilih.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $academicYearId = $request->academic_year_id ?: optional(AcademicYear::getActive())->id;

            // Cek bentrok ruangan ujian
            $conflict = ScheduleConflictService::checkExamScheduleConflict(
                (int) $request->room_id,
                $request->exam_date,
                $request->start_time,
                $request->end_time
            );

            if ($conflict['has_conflict']) {
                return response()->json([
                    'success'   => false,
                    'message'   => $conflict['conflicts'][0]['message'] ?? 'Ruangan ujian bentrok dengan ujian lain.',
                    'conflicts' => $conflict['conflicts'],
                ], 422);
            }

            $exam = ExamSchedule::create([
                'course_class_id'  => $request->course_class_id,
                'exam_type'        => $request->exam_type,
                'exam_date'        => $request->exam_date,
                'start_time'       => $request->start_time,
                'end_time'         => $request->end_time,
                'room_id'          => $request->room_id,
                'academic_year_id' => $academicYearId,
                'notes'            => $request->notes,
            ]);

            AuditLogger::log('CREATE_EXAM_SCHEDULE', 'Exams', [
                'type' => $exam->exam_type,
                'date' => $exam->exam_date,
                'time' => "{$exam->start_time} - {$exam->end_time}",
            ], $exam->id);

            return response()->json([
                'success' => true,
                'message' => "Jadwal {$exam->exam_type} berhasil disusun!",
                'data'    => $exam->load(['courseClass.course', 'room.building']),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $exam = ExamSchedule::with(['courseClass.course', 'room.building', 'academicYear', 'invigilators.invigilator'])->find($id);
            if (!$exam) {
                return response()->json(['success' => false, 'message' => 'Jadwal ujian tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $exam]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $exam = ExamSchedule::find($id);
        if (!$exam) {
            return response()->json(['success' => false, 'message' => 'Jadwal ujian tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'course_class_id'  => 'required|exists:course_classes,id',
            'exam_type'        => 'required|in:UTS,UAS',
            'exam_date'        => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room_id'          => 'required|exists:rooms,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'notes'            => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $academicYearId = $request->academic_year_id ?: $exam->academic_year_id;

            // Cek bentrok dengan mengabaikan ujian yang sedang diedit
            $conflict = ScheduleConflictService::checkExamScheduleConflict(
                (int) $request->room_id,
                $request->exam_date,
                $request->start_time,
                $request->end_time,
                $exam->id
            );

            if ($conflict['has_conflict']) {
                return response()->json([
                    'success'   => false,
                    'message'   => $conflict['conflicts'][0]['message'] ?? 'Ruangan ujian bentrok dengan ujian lain.',
                    'conflicts' => $conflict['conflicts'],
                ], 422);
            }

            $exam->update([
                'course_class_id'  => $request->course_class_id,
                'exam_type'        => $request->exam_type,
                'exam_date'        => $request->exam_date,
                'start_time'       => $request->start_time,
                'end_time'         => $request->end_time,
                'room_id'          => $request->room_id,
                'academic_year_id' => $academicYearId,
                'notes'            => $request->notes,
            ]);

            AuditLogger::log('UPDATE_EXAM_SCHEDULE', 'Exams', ['type' => $exam->exam_type], $exam->id);

            return response()->json([
                'success' => true,
                'message' => "Jadwal {$exam->exam_type} berhasil diperbarui!",
                'data'    => $exam->load(['courseClass.course', 'room.building']),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $exam = ExamSchedule::find($id);
            if (!$exam) {
                return response()->json(['success' => false, 'message' => 'Jadwal ujian tidak ditemukan.'], 404);
            }

            $type = $exam->exam_type;
            $exam->delete();
            AuditLogger::log('DELETE_EXAM_SCHEDULE', 'Exams', ['type' => $type], $id);

            return response()->json(['success' => true, 'message' => "Jadwal {$type} berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ========================================================
    // 2. PENEMPATAN PENGAWAS UJIAN (Item 21)
    // ========================================================

    public function getInvigilators($examId)
    {
        try {
            $exam = ExamSchedule::with(['courseClass.course', 'room'])->find($examId);
            if (!$exam) {
                return response()->json(['success' => false, 'message' => 'Jadwal ujian tidak ditemukan.'], 404);
            }

            $invigilators = ExamInvigilator::with('invigilator')
                ->where('exam_schedule_id', $examId)
                ->get();

            return response()->json([
                'success'       => true,
                'exam'          => [
                    'id'        => $exam->id,
                    'type'      => $exam->exam_type,
                    'course'    => $exam->courseClass->course->name ?? '-',
                    'date'      => $exam->exam_date ? $exam->exam_date->format('Y-m-d') : '-',
                    'time'      => "{$exam->start_time} - {$exam->end_time}",
                    'room'      => $exam->room->name ?? '-',
                ],
                'data'          => $invigilators,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function assignInvigilator(Request $request, $examId)
    {
        $exam = ExamSchedule::with('courseClass.course')->find($examId);
        if (!$exam) {
            return response()->json(['success' => false, 'message' => 'Jadwal ujian tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'invigilator_id'    => 'required|exists:users,id',
            'role'              => 'required|in:Pengawas 1,Pengawas 2,Cadangan',
            'attendance_status' => 'nullable|in:Hadir,Izin,Alpa',
            'notes'             => 'nullable|string',
        ], [
            'invigilator_id.required' => 'Pengawas ujian (dosen/staf) wajib dipilih.',
            'role.required'           => 'Peran pengawas wajib dipilih (Pengawas 1, Pengawas 2, Cadangan).',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $user = User::find($request->invigilator_id);

            // Cek bentrok penugasan pengawas pada waktu yang sama
            $conflict = ScheduleConflictService::checkInvigilatorConflict((int) $request->invigilator_id, (int) $examId);
            if ($conflict['has_conflict']) {
                return response()->json([
                    'success'   => false,
                    'message'   => $conflict['conflicts'][0]['message'] ?? 'Dosen sudah ditugaskan mengawas pada jam yang bersamaan.',
                    'conflicts' => $conflict['conflicts'],
                ], 422);
            }

            $invigilator = ExamInvigilator::updateOrCreate(
                [
                    'exam_schedule_id' => $examId,
                    'invigilator_id'   => $request->invigilator_id,
                ],
                [
                    'role'              => $request->role,
                    'attendance_status' => $request->attendance_status ?? 'Hadir',
                    'notes'             => $request->notes,
                ]
            );

            AuditLogger::log('ASSIGN_EXAM_INVIGILATOR', 'Exams', [
                'exam_type'        => $exam->exam_type,
                'course'           => $exam->courseClass->course->name ?? '-',
                'invigilator_name' => $user->name,
                'role'             => $request->role,
            ], $invigilator->id);

            return response()->json([
                'success' => true,
                'message' => "Pengawas '{$user->name}' berhasil ditempatkan sebagai {$request->role}.",
                'data'    => $invigilator->load('invigilator'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function removeInvigilator($examId, $invigilatorId)
    {
        try {
            $record = ExamInvigilator::where('exam_schedule_id', $examId)
                ->where('invigilator_id', $invigilatorId)
                ->first();

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Penempatan pengawas tidak ditemukan.'], 404);
            }

            $record->delete();
            AuditLogger::log('REMOVE_EXAM_INVIGILATOR', 'Exams', ['exam_id' => $examId, 'invigilator_id' => $invigilatorId]);

            return response()->json(['success' => true, 'message' => 'Pengawas ujian berhasil dihapus dari jadwal.']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
