<?php

namespace App\Services;

use App\Models\ClassLecturer;
use App\Models\ClassSchedule;
use App\Models\ExamInvigilator;
use App\Models\ExamSchedule;
use App\Models\Room;

class ScheduleConflictService
{
    /**
     * Cek bentrok untuk Jadwal Kuliah (Item 18)
     * Berdasarkan: Ruangan, Dosen Pengampu, dan Kelas pada hari & jam yang sama.
     *
     * @param int|null $courseClassId ID Kelas
     * @param int|null $roomId ID Ruangan
     * @param string $dayOfWeek Hari (Senin - Minggu)
     * @param string $startTime Format 'HH:MM' atau 'HH:MM:SS'
     * @param string $endTime Format 'HH:MM' atau 'HH:MM:SS'
     * @param int|null $academicYearId ID Tahun Akademik (opsional)
     * @param int|null $ignoreScheduleId ID Jadwal yang sedang diedit (diabaikan dari cek)
     * @return array [ 'has_conflict' => bool, 'conflicts' => array ]
     */
    public static function checkClassScheduleConflict(
        ?int $courseClassId,
        ?int $roomId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        ?int $academicYearId = null,
        ?int $ignoreScheduleId = null
    ): array {
        $conflicts = [];

        // Pastikan format jam seragam
        $start = date('H:i:s', strtotime($startTime));
        $end   = date('H:i:s', strtotime($endTime));

        if ($start >= $end) {
            $conflicts[] = [
                'type'    => 'invalid_time',
                'message' => 'Jam selesai kuliah harus lebih besar dari jam mulai.',
            ];
            return ['has_conflict' => true, 'conflicts' => $conflicts];
        }

        // 1. Cek Bentrok Ruangan (Ruangan sama, hari sama, jam beririsan)
        if ($roomId) {
            $roomQuery = ClassSchedule::with(['courseClass.course', 'room'])
                ->where('room_id', $roomId)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                });

            if ($ignoreScheduleId) {
                $roomQuery->where('id', '!=', $ignoreScheduleId);
            }
            if ($academicYearId) {
                $roomQuery->where('academic_year_id', $academicYearId);
            }

            $roomConflicts = $roomQuery->get();
            foreach ($roomConflicts as $c) {
                $roomName = $c->room->name ?? "Ruang #{$roomId}";
                $courseName = $c->courseClass->course->name ?? 'Mata Kuliah';
                $className = $c->courseClass->name ?? '';
                $timeSlot = "{$c->start_time} - {$c->end_time}";

                $conflicts[] = [
                    'type'     => 'room_conflict',
                    'message'  => "Bentrok Ruangan: {$roomName} sudah digunakan oleh kelas {$courseName} ({$className}) pada hari {$dayOfWeek} jam {$timeSlot}.",
                    'schedule' => $c,
                ];
            }
        }

        // 2. Cek Bentrok Dosen Pengampu (Dosen sama, hari sama, jam beririsan)
        if ($courseClassId) {
            $lecturerIds = ClassLecturer::where('course_class_id', $courseClassId)->pluck('lecturer_id')->toArray();

            if (!empty($lecturerIds)) {
                $lecturerConflictSchedules = ClassSchedule::with(['courseClass.course', 'courseClass.classLecturers.lecturer'])
                    ->where('day_of_week', $dayOfWeek)
                    ->where('is_active', true)
                    ->where(function ($q) use ($start, $end) {
                        $q->where('start_time', '<', $end)
                          ->where('end_time', '>', $start);
                    })
                    ->whereHas('courseClass.classLecturers', function ($q) use ($lecturerIds) {
                        $q->whereIn('lecturer_id', $lecturerIds);
                    });

                if ($ignoreScheduleId) {
                    $lecturerConflictSchedules->where('id', '!=', $ignoreScheduleId);
                }
                if ($academicYearId) {
                    $lecturerConflictSchedules->where('academic_year_id', $academicYearId);
                }

                foreach ($lecturerConflictSchedules->get() as $c) {
                    // Temukan nama dosen yang bentrok
                    $collidingLecturers = $c->courseClass->classLecturers
                        ->filter(fn($cl) => in_array($cl->lecturer_id, $lecturerIds))
                        ->map(fn($cl) => $cl->lecturer->name ?? 'Dosen')
                        ->implode(', ');

                    $courseName = $c->courseClass->course->name ?? 'Mata Kuliah';
                    $className = $c->courseClass->name ?? '';
                    $timeSlot = "{$c->start_time} - {$c->end_time}";

                    $conflicts[] = [
                        'type'     => 'lecturer_conflict',
                        'message'  => "Bentrok Dosen: Dosen {$collidingLecturers} sedang mengajar kelas {$courseName} ({$className}) pada hari {$dayOfWeek} jam {$timeSlot}.",
                        'schedule' => $c,
                    ];
                }
            }

            // 3. Cek Bentrok Kelas itu sendiri (Kelas sama, hari sama, jam beririsan)
            $classScheduleQuery = ClassSchedule::with(['room'])
                ->where('course_class_id', $courseClassId)
                ->where('day_of_week', $dayOfWeek)
                ->where('is_active', true)
                ->where(function ($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                      ->where('end_time', '>', $start);
                });

            if ($ignoreScheduleId) {
                $classScheduleQuery->where('id', '!=', $ignoreScheduleId);
            }

            foreach ($classScheduleQuery->get() as $c) {
                $timeSlot = "{$c->start_time} - {$c->end_time}";
                $conflicts[] = [
                    'type'     => 'class_conflict',
                    'message'  => "Bentrok Kelas: Kelas ini sudah memiliki jadwal pada hari {$dayOfWeek} jam {$timeSlot}.",
                    'schedule' => $c,
                ];
            }
        }

        return [
            'has_conflict' => !empty($conflicts),
            'conflicts'    => $conflicts,
        ];
    }

    /**
     * Cek bentrok untuk Jadwal Ujian UTS/UAS (Item 20 & 21)
     */
    public static function checkExamScheduleConflict(
        int $roomId,
        string $examDate,
        string $startTime,
        string $endTime,
        ?int $ignoreExamId = null
    ): array {
        $conflicts = [];
        $start = date('H:i:s', strtotime($startTime));
        $end   = date('H:i:s', strtotime($endTime));

        if ($start >= $end) {
            $conflicts[] = [
                'type'    => 'invalid_time',
                'message' => 'Jam selesai ujian harus lebih besar dari jam mulai.',
            ];
            return ['has_conflict' => true, 'conflicts' => $conflicts];
        }

        $query = ExamSchedule::with(['courseClass.course', 'room'])
            ->where('room_id', $roomId)
            ->where('exam_date', $examDate)
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                  ->where('end_time', '>', $start);
            });

        if ($ignoreExamId) {
            $query->where('id', '!=', $ignoreExamId);
        }

        foreach ($query->get() as $c) {
            $roomName = $c->room->name ?? "Ruang #{$roomId}";
            $courseName = $c->courseClass->course->name ?? 'Mata Kuliah';
            $timeSlot = "{$c->start_time} - {$c->end_time}";

            $conflicts[] = [
                'type'     => 'room_conflict',
                'message'  => "Bentrok Ruang Ujian: {$roomName} sudah dijadwalkan untuk {$c->exam_type} {$courseName} pada {$examDate} jam {$timeSlot}.",
                'schedule' => $c,
            ];
        }

        return [
            'has_conflict' => !empty($conflicts),
            'conflicts'    => $conflicts,
        ];
    }

    /**
     * Cek bentrok pengawas ujian (Item 21)
     */
    public static function checkInvigilatorConflict(
        int $invigilatorId,
        int $examScheduleId
    ): array {
        $exam = ExamSchedule::find($examScheduleId);
        if (!$exam) {
            return ['has_conflict' => false, 'conflicts' => []];
        }

        $conflicts = [];

        // Cari jadwal ujian lain pada tanggal yang sama dan jam yang beririsan di mana dosen ini sudah menjadi pengawas
        $existing = ExamInvigilator::where('invigilator_id', $invigilatorId)
            ->where('exam_schedule_id', '!=', $examScheduleId)
            ->whereHas('examSchedule', function ($q) use ($exam) {
                $q->where('exam_date', $exam->exam_date)
                  ->where('start_time', '<', $exam->end_time)
                  ->where('end_time', '>', $exam->start_time);
            })
            ->with(['examSchedule.courseClass.course', 'examSchedule.room'])
            ->get();

        foreach ($existing as $item) {
            $otherExam = $item->examSchedule;
            $courseName = $otherExam->courseClass->course->name ?? 'Ujian';
            $roomName = $otherExam->room->name ?? 'Ruangan';
            $timeSlot = "{$otherExam->start_time} - {$otherExam->end_time}";

            $conflicts[] = [
                'type'    => 'invigilator_conflict',
                'message' => "Bentrok Pengawas: Dosen sudah ditugaskan mengawas {$otherExam->exam_type} {$courseName} di {$roomName} pada {$exam->exam_date} jam {$timeSlot}.",
            ];
        }

        return [
            'has_conflict' => !empty($conflicts),
            'conflicts'    => $conflicts,
        ];
    }
}
