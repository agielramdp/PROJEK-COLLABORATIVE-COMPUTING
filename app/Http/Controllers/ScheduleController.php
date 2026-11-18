<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassSchedule;
use App\Services\AuditLogger;
use App\Services\ScheduleConflictService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ScheduleController extends Controller
{
    /**
     * Daftar Jadwal Perkuliahan (Item 17)
     * GET /api/admin/schedules
     */
    public function index(Request $request)
    {
        try {
            $query = ClassSchedule::with([
                'courseClass.course.studyProgram',
                'courseClass.classLecturers.lecturer',
                'room.building',
                'academicYear',
            ]);

            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            }
            if ($request->filled('day_of_week')) {
                $query->where('day_of_week', $request->day_of_week);
            }
            if ($request->filled('room_id')) {
                $query->where('room_id', $request->room_id);
            }
            if ($request->filled('course_class_id')) {
                $query->where('course_class_id', $request->course_class_id);
            }

            $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

            return response()->json([
                'success' => true,
                'data'    => $schedules,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint Khusus Pengecekan Jadwal Bentrok Otomatis (Item 18)
     * POST /api/admin/schedules/check-conflict
     */
    public function checkConflict(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_class_id'    => 'required|exists:course_classes,id',
            'room_id'            => 'required|exists:rooms,id',
            'day_of_week'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time'         => 'required|date_format:H:i',
            'end_time'           => 'required|date_format:H:i|after:start_time',
            'academic_year_id'   => 'nullable|exists:academic_years,id',
            'ignore_schedule_id' => 'nullable|integer',
        ], [
            'day_of_week.required' => 'Hari perkuliahan wajib dipilih.',
            'start_time.required'  => 'Jam mulai perkuliahan wajib diisi.',
            'end_time.required'    => 'Jam selesai perkuliahan wajib diisi.',
            'end_time.after'       => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $conflictResult = ScheduleConflictService::checkClassScheduleConflict(
                (int) $request->course_class_id,
                (int) $request->room_id,
                $request->day_of_week,
                $request->start_time,
                $request->end_time,
                $request->academic_year_id ? (int) $request->academic_year_id : null,
                $request->ignore_schedule_id ? (int) $request->ignore_schedule_id : null
            );

            return response()->json([
                'success'      => true,
                'has_conflict' => $conflictResult['has_conflict'],
                'message'      => $conflictResult['has_conflict'] ? 'Terdeteksi jadwal bentrok!' : 'Jadwal aman! Tidak ada bentrok ruangan atau dosen.',
                'conflicts'    => $conflictResult['conflicts'],
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Buat Jadwal Perkuliahan Baru dengan Auto Conflict Check (Item 17 & 18)
     * POST /api/admin/schedules
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_class_id'  => 'required|exists:course_classes,id',
            'room_id'          => 'required|exists:rooms,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'is_active'        => 'nullable|boolean',
        ], [
            'course_class_id.required' => 'Kelas kuliah wajib dipilih.',
            'room_id.required'         => 'Ruangan wajib dipilih.',
            'day_of_week.required'     => 'Hari kuliah wajib dipilih.',
            'start_time.required'      => 'Jam mulai perkuliahan wajib diisi.',
            'end_time.required'        => 'Jam selesai perkuliahan wajib diisi.',
            'end_time.after'           => 'Jam selesai kuliah harus setelah jam mulai.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $academicYearId = $request->academic_year_id ?: optional(AcademicYear::getActive())->id;

            // 1. Jalankan Pengecekan Bentrok Otomatis (Item 18)
            $conflictResult = ScheduleConflictService::checkClassScheduleConflict(
                (int) $request->course_class_id,
                (int) $request->room_id,
                $request->day_of_week,
                $request->start_time,
                $request->end_time,
                $academicYearId
            );

            if ($conflictResult['has_conflict']) {
                $firstConflictMsg = $conflictResult['conflicts'][0]['message'] ?? 'Jadwal bertabrakan dengan jadwal lain.';
                return response()->json([
                    'success'      => false,
                    'message'      => $firstConflictMsg,
                    'has_conflict' => true,
                    'conflicts'    => $conflictResult['conflicts'],
                ], 422);
            }

            // 2. Simpan Jadwal Perkuliahan jika tidak ada bentrok
            $schedule = ClassSchedule::create([
                'course_class_id'  => $request->course_class_id,
                'room_id'          => $request->room_id,
                'day_of_week'      => $request->day_of_week,
                'start_time'       => $request->start_time,
                'end_time'         => $request->end_time,
                'academic_year_id' => $academicYearId,
                'is_active'        => $request->boolean('is_active', true),
            ]);

            AuditLogger::log('CREATE_SCHEDULE', 'Schedule', [
                'class_id' => $schedule->course_class_id,
                'room_id'  => $schedule->room_id,
                'day'      => $schedule->day_of_week,
                'time'     => "{$schedule->start_time} - {$schedule->end_time}",
            ], $schedule->id);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal perkuliahan berhasil disusun tanpa bentrok!',
                'data'    => $schedule->load(['courseClass.course', 'room.building']),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Detail Jadwal Perkuliahan
     * GET /api/admin/schedules/{id}
     */
    public function show($id)
    {
        try {
            $schedule = ClassSchedule::with(['courseClass.course', 'courseClass.classLecturers.lecturer', 'room.building', 'academicYear'])->find($id);
            if (!$schedule) {
                return response()->json(['success' => false, 'message' => 'Jadwal perkuliahan tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $schedule]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Jadwal Perkuliahan dengan Auto Conflict Check (Item 17 & 18)
     * PUT /api/admin/schedules/{id}
     */
    public function update(Request $request, $id)
    {
        $schedule = ClassSchedule::find($id);
        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Jadwal perkuliahan tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'course_class_id'  => 'required|exists:course_classes,id',
            'room_id'          => 'required|exists:rooms,id',
            'day_of_week'      => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $academicYearId = $request->academic_year_id ?: $schedule->academic_year_id;

            // Pengecekan bentrok jadwal otomatis dengan mengabaikan id jadwal yang sedang diedit
            $conflictResult = ScheduleConflictService::checkClassScheduleConflict(
                (int) $request->course_class_id,
                (int) $request->room_id,
                $request->day_of_week,
                $request->start_time,
                $request->end_time,
                $academicYearId,
                $schedule->id
            );

            if ($conflictResult['has_conflict']) {
                $firstConflictMsg = $conflictResult['conflicts'][0]['message'] ?? 'Jadwal bertabrakan dengan jadwal lain.';
                return response()->json([
                    'success'      => false,
                    'message'      => $firstConflictMsg,
                    'has_conflict' => true,
                    'conflicts'    => $conflictResult['conflicts'],
                ], 422);
            }

            $schedule->update([
                'course_class_id'  => $request->course_class_id,
                'room_id'          => $request->room_id,
                'day_of_week'      => $request->day_of_week,
                'start_time'       => $request->start_time,
                'end_time'         => $request->end_time,
                'academic_year_id' => $academicYearId,
                'is_active'        => $request->boolean('is_active', true),
            ]);

            AuditLogger::log('UPDATE_SCHEDULE', 'Schedule', [
                'class_id' => $schedule->course_class_id,
                'room_id'  => $schedule->room_id,
                'day'      => $schedule->day_of_week,
            ], $schedule->id);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal perkuliahan berhasil diperbarui tanpa bentrok!',
                'data'    => $schedule->load(['courseClass.course', 'room.building']),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Jadwal Perkuliahan
     * DELETE /api/admin/schedules/{id}
     */
    public function destroy($id)
    {
        try {
            $schedule = ClassSchedule::find($id);
            if (!$schedule) {
                return response()->json(['success' => false, 'message' => 'Jadwal perkuliahan tidak ditemukan.'], 404);
            }

            $schedule->delete();
            AuditLogger::log('DELETE_SCHEDULE', 'Schedule', [], $id);

            return response()->json(['success' => true, 'message' => 'Jadwal perkuliahan berhasil dihapus.']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
