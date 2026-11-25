<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendarEvent;
use App\Models\AuditLog;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman utama Dashboard Admin
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Tampilkan halaman Master Data Akademik
     */
    public function masterData()
    {
        return view('admin.master-data.index');
    }

    /**
     * Tampilkan halaman Jadwal Kuliah & Ujian
     */
    public function schedules()
    {
        return view('admin.schedules.index');
    }

    /**
     * Endpoint API: Statistik dan Ringkasan Aktivitas Dashboard Admin (100% Database)
     */
    public function apiStats()
    {
        try {
            $totalMahasiswa = User::where('role', 'Mahasiswa')->count();
            $totalDosen     = User::whereIn('role', ['Dosen', 'Dosen Pengajar', 'Dosen Wali'])->count();
            $programStudi   = StudyProgram::count();
            $mataKuliah     = Course::count();
            $totalFakultas  = Faculty::count();
            $totalJadwal    = ClassSchedule::count();

            // Tren 6 bulan terakhir berdasarkan created_at user Mahasiswa
            $monthlyLabels = [];
            $monthlyCounts = [];
            $now = Carbon::now();
            for ($i = 5; $i >= 0; $i--) {
                $date = $now->copy()->subMonths($i);
                $monthlyLabels[] = $date->translatedFormat('M');
                $monthlyCounts[] = User::where('role', 'Mahasiswa')
                    ->whereYear('created_at', '<=', $date->year)
                    ->where(function ($q) use ($date) {
                        $q->whereYear('created_at', '<', $date->year)
                          ->orWhereMonth('created_at', '<=', $date->month);
                    })
                    ->count();
            }

            // Distribusi Program Studi per Fakultas
            $faculties = Faculty::withCount('studyPrograms')->get();
            $distribution = $faculties->map(function ($fac) use ($programStudi) {
                $count = (int) $fac->study_programs_count;
                $pct = $programStudi > 0 ? round(($count / $programStudi) * 100) : 0;
                return [
                    'label'      => $fac->name,
                    'count'      => $count,
                    'percentage' => $pct,
                ];
            })->values();

            // Aktivitas terbaru dari audit_logs
            $recentActivities = AuditLog::orderBy('created_at', 'desc')
                ->take(4)
                ->get()
                ->map(function ($log) {
                    return [
                        'id'         => $log->id,
                        'action'     => $log->action,
                        'module'     => $log->module,
                        'user_name'  => $log->user_name ?: 'System',
                        'details'    => $log->details,
                        'created_at' => $log->created_at ? $log->created_at->format('d M Y') : '-',
                        'time'       => $log->created_at ? $log->created_at->format('H:i') : '-',
                    ];
                });

            // Kalender Akademik dari academic_calendar_events
            $calendarEvents = AcademicCalendarEvent::orderBy('start_date', 'asc')
                ->take(4)
                ->get()
                ->map(function ($ev) {
                    return [
                        'id'         => $ev->id,
                        'title'      => $ev->title,
                        'event_type' => $ev->event_type ?? 'Akademik',
                        'day'        => $ev->start_date ? $ev->start_date->format('d') : '-',
                        'month'      => $ev->start_date ? $ev->start_date->format('M') : '-',
                        'range'      => $ev->start_date
                            ? ($ev->end_date && $ev->end_date->ne($ev->start_date)
                                ? $ev->start_date->format('d M Y') . ' - ' . $ev->end_date->format('d M Y')
                                : $ev->start_date->format('d M Y'))
                            : '-',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'total_mahasiswa'      => $totalMahasiswa,
                    'total_dosen'          => $totalDosen,
                    'program_studi'        => $programStudi,
                    'mata_kuliah'          => $mataKuliah,
                    'total_fakultas'       => $totalFakultas,
                    'total_jadwal'         => $totalJadwal,
                    'monthly_students'     => [
                        'labels' => $monthlyLabels,
                        'counts' => $monthlyCounts,
                    ],
                    'faculty_distribution' => $distribution,
                    'recent_activities'    => $recentActivities,
                    'calendar_events'      => $calendarEvents,
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat statistik dashboard: ' . $e->getMessage(),
            ], 500);
        }
    }
}
