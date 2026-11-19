<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassSchedule;
use App\Models\CourseClass;
use App\Models\ExamSchedule;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Throwable;

class AcademicReportController extends Controller
{
    /**
     * Daftar Jenis Laporan Akademik yang Tersedia
     * GET /api/admin/reports/types
     */
    public function getTypes()
    {
        $types = [
            [
                'id'          => 'rekap_mahasiswa',
                'name'        => 'Laporan Rekapitulasi Data Mahasiswa',
                'description' => 'Mencakup data mahasiswa, NPM, program studi, status akademik, dan kontak.',
                'formats'     => ['csv', 'json'],
            ],
            [
                'id'          => 'rekap_dosen',
                'name'        => 'Laporan Rekapitulasi Dosen & Beban Mengajar',
                'description' => 'Mencakup daftar dosen pengajar dan wali, jumlah kelas diampu, dan total SKS.',
                'formats'     => ['csv', 'json'],
            ],
            [
                'id'          => 'rekap_jadwal',
                'name'        => 'Laporan Jadwal Perkuliahan',
                'description' => 'Jadwal perkuliahan lengkap per semester, hari, ruangan, jam, dan dosen pengampu.',
                'formats'     => ['csv', 'json'],
            ],
            [
                'id'          => 'rekap_ujian',
                'name'        => 'Laporan Jadwal Ujian UTS/UAS & Pengawas',
                'description' => 'Jadwal ujian terorganisir per tanggal, jam, ruangan, dan daftar pengawas ujian.',
                'formats'     => ['csv', 'json'],
            ],
            [
                'id'          => 'rekap_kelas',
                'name'        => 'Laporan Rekapitulasi Kelas & Kuota',
                'description' => 'Daftar seluruh kelas kuliah yang dibuka, kuota terisi, dan status kelas.',
                'formats'     => ['csv', 'json'],
            ],
        ];

        return response()->json([
            'success' => true,
            'data'    => $types,
        ]);
    }

    /**
     * Download Laporan Akademik Sesuai Jenis
     * GET /api/admin/reports/download?type=...&format=...
     */
    public function download(Request $request)
    {
        $type   = $request->input('type', 'rekap_mahasiswa');
        $format = strtolower($request->input('format', 'csv'));

        AuditLogger::log('DOWNLOAD_REPORT', 'Reports', [
            'report_type' => $type,
            'format'      => $format,
        ]);

        return match ($type) {
            'rekap_mahasiswa' => $this->exportMahasiswaReport($format),
            'rekap_dosen'     => $this->exportDosenReport($format),
            'rekap_jadwal'    => $this->exportJadwalReport($format, $request),
            'rekap_ujian'     => $this->exportUjianReport($format, $request),
            'rekap_kelas'     => $this->exportKelasReport($format, $request),
            default           => response()->json(['success' => false, 'message' => "Jenis laporan '{$type}' tidak dikenali."], 404),
        };
    }

    /**
     * 1. Export Rekap Mahasiswa
     */
    protected function exportMahasiswaReport(string $format)
    {
        $students = User::where('role', 'Mahasiswa')->orderBy('id', 'asc')->get();

        if ($format === 'json') {
            return response()->json(['success' => true, 'total' => $students->count(), 'data' => $students]);
        }

        $fileName = 'laporan_rekap_mahasiswa_' . date('Ymd_His') . '.csv';

        return response()->stream(function () use ($students) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($f, ['ID', 'NPM', 'Nama Mahasiswa', 'Username', 'Email', 'Status', 'Tanggal Registrasi']);

            foreach ($students as $s) {
                fputcsv($f, [
                    $s->id,
                    $s->npm ?? '-',
                    $s->name,
                    $s->username ?? '-',
                    $s->email ?? '-',
                    $s->status ?? 'Aktif',
                    $s->created_at ? $s->created_at->format('Y-m-d H:i') : '-',
                ]);
            }
            fclose($f);
        }, 200, $this->csvHeaders($fileName));
    }

    /**
     * 2. Export Rekap Dosen
     */
    protected function exportDosenReport(string $format)
    {
        $lecturers = User::whereIn('role', ['Dosen Pengajar', 'Dosen Wali'])
            ->with(['teachingClasses.courseClass.course', 'adviseeStudents'])
            ->orderBy('name', 'asc')
            ->get();

        if ($format === 'json') {
            return response()->json(['success' => true, 'total' => $lecturers->count(), 'data' => $lecturers]);
        }

        $fileName = 'laporan_rekap_dosen_' . date('Ymd_His') . '.csv';

        return response()->stream(function () use ($lecturers) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($f, ['ID', 'NIP / NIDN', 'Nama Dosen', 'Email', 'Role', 'Jumlah Kelas Diampu', 'Total SKS Beban', 'Jumlah Mahasiswa Bimbingan']);

            foreach ($lecturers as $l) {
                $teachingClassesCount = $l->teachingClasses->count();
                $totalSks = $l->teachingClasses->sum('teaching_load_sks');
                $adviseesCount = $l->adviseeStudents->count();

                fputcsv($f, [
                    $l->id,
                    $l->npm ?? '-',
                    $l->name,
                    $l->email ?? '-',
                    $l->role,
                    $teachingClassesCount,
                    $totalSks,
                    $adviseesCount,
                ]);
            }
            fclose($f);
        }, 200, $this->csvHeaders($fileName));
    }

    /**
     * 3. Export Rekap Jadwal Kuliah
     */
    protected function exportJadwalReport(string $format, Request $request)
    {
        $query = ClassSchedule::with(['courseClass.course', 'courseClass.classLecturers.lecturer', 'room.building', 'academicYear']);

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        if ($format === 'json') {
            return response()->json(['success' => true, 'total' => $schedules->count(), 'data' => $schedules]);
        }

        $fileName = 'laporan_jadwal_perkuliahan_' . date('Ymd_His') . '.csv';

        return response()->stream(function () use ($schedules) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($f, ['ID', 'Hari', 'Jam Mulai', 'Jam Selesai', 'Kode MK', 'Mata Kuliah', 'Kelas', 'SKS', 'Ruangan', 'Gedung', 'Dosen Pengampu']);

            foreach ($schedules as $s) {
                $course = $s->courseClass->course ?? null;
                $lecturers = $s->courseClass->classLecturers->map(fn($cl) => $cl->lecturer->name ?? '')->filter()->implode(', ');

                fputcsv($f, [
                    $s->id,
                    $s->day_of_week,
                    $s->start_time,
                    $s->end_time,
                    $course ? $course->code : '-',
                    $course ? $course->name : '-',
                    $s->courseClass->name ?? '-',
                    $course ? $course->total_credits : '-',
                    $s->room->name ?? '-',
                    $s->room->building->name ?? '-',
                    $lecturers ?: 'Belum ditentukan',
                ]);
            }
            fclose($f);
        }, 200, $this->csvHeaders($fileName));
    }

    /**
     * 4. Export Rekap Ujian
     */
    protected function exportUjianReport(string $format, Request $request)
    {
        $query = ExamSchedule::with(['courseClass.course', 'room.building', 'invigilators.invigilator']);

        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        $exams = $query->orderBy('exam_date')->orderBy('start_time')->get();

        if ($format === 'json') {
            return response()->json(['success' => true, 'total' => $exams->count(), 'data' => $exams]);
        }

        $fileName = 'laporan_jadwal_ujian_' . date('Ymd_His') . '.csv';

        return response()->stream(function () use ($exams) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($f, ['ID', 'Jenis Ujian', 'Tanggal Ujian', 'Jam Mulai', 'Jam Selesai', 'Mata Kuliah', 'Kelas', 'Ruangan', 'Pengawas Ujian']);

            foreach ($exams as $e) {
                $course = $e->courseClass->course ?? null;
                $invigilators = $e->invigilators->map(fn($inv) => ($inv->invigilator->name ?? 'Pengawas') . " ({$inv->role})")->implode(', ');

                fputcsv($f, [
                    $e->id,
                    $e->exam_type,
                    $e->exam_date ? $e->exam_date->format('Y-m-d') : '-',
                    $e->start_time,
                    $e->end_time,
                    $course ? $course->name : '-',
                    $e->courseClass->name ?? '-',
                    $e->room->name ?? '-',
                    $invigilators ?: 'Belum ditempatkan',
                ]);
            }
            fclose($f);
        }, 200, $this->csvHeaders($fileName));
    }

    /**
     * 5. Export Rekap Kelas
     */
    protected function exportKelasReport(string $format, Request $request)
    {
        $classes = CourseClass::with(['course', 'academicYear', 'classLecturers.lecturer'])->get();

        if ($format === 'json') {
            return response()->json(['success' => true, 'total' => $classes->count(), 'data' => $classes]);
        }

        $fileName = 'laporan_rekap_kelas_' . date('Ymd_His') . '.csv';

        return response()->stream(function () use ($classes) {
            $f = fopen('php://output', 'w');
            fprintf($f, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($f, ['ID', 'Tahun Akademik', 'Kode MK', 'Nama Mata Kuliah', 'Nama Kelas', 'SKS', 'Kuota', 'Terisi', 'Status']);

            foreach ($classes as $c) {
                fputcsv($f, [
                    $c->id,
                    $c->academicYear ? "{$c->academicYear->year_name} ({$c->academicYear->semester})" : '-',
                    $c->course ? $c->course->code : '-',
                    $c->course ? $c->course->name : '-',
                    $c->name,
                    $c->course ? $c->course->total_credits : '-',
                    $c->quota,
                    $c->current_enrolled,
                    $c->is_active ? 'Aktif' : 'Non-Aktif',
                ]);
            }
            fclose($f);
        }, 200, $this->csvHeaders($fileName));
    }

    protected function csvHeaders(string $fileName): array
    {
        return [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];
    }
}
