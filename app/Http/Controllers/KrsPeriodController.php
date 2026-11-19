<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\KrsPeriod;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class KrsPeriodController extends Controller
{
    /**
     * Daftar Semua Periode KRS
     * GET /api/admin/krs-periods
     */
    public function index(Request $request)
    {
        try {
            $query = KrsPeriod::with(['academicYear', 'studyProgram']);

            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            }

            $periods = $query->orderBy('start_date', 'desc')->get();

            // Tambahkan flag dinamis status real-time
            $data = $periods->map(function ($period) {
                return array_merge($period->toArray(), [
                    'is_open_now'          => $period->isOpenNow(),
                    'is_revision_open_now' => $period->isRevisionOpenNow(),
                    'status_label'         => $period->isOpenNow() ? 'Sedang Dibuka' : 'Ditutup',
                ]);
            });

            return response()->json(['success' => true, 'data' => $data]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Cek status apakah KRS saat ini SEDANG BUKA atau DITUTUP
     * GET /api/admin/krs-periods/status
     */
    public function checkStatus(Request $request)
    {
        try {
            $studyProgramId = $request->input('study_program_id');
            $activeYear = AcademicYear::getActive();

            if (!$activeYear) {
                return response()->json([
                    'success'     => true,
                    'is_open'     => false,
                    'message'     => 'Tidak ada tahun akademik yang sedang aktif saat ini.',
                    'period'      => null,
                ]);
            }

            // Cari periode KRS aktif untuk tahun akademik saat ini
            $periods = KrsPeriod::where('academic_year_id', $activeYear->id)
                ->when($studyProgramId, function ($q) use ($studyProgramId) {
                    $q->where(function ($sub) use ($studyProgramId) {
                        $sub->whereNull('study_program_id')
                            ->orWhere('study_program_id', $studyProgramId);
                    });
                })
                ->get();

            $activePeriod = null;
            $isRevision = false;

            foreach ($periods as $p) {
                if ($p->isOpenNow()) {
                    $activePeriod = $p;
                    break;
                }
                if ($p->isRevisionOpenNow()) {
                    $activePeriod = $p;
                    $isRevision = true;
                    break;
                }
            }

            $isOpen = ($activePeriod !== null);

            return response()->json([
                'success'           => true,
                'is_open'           => $isOpen,
                'is_revision'       => $isRevision,
                'academic_year'     => "{$activeYear->year_name} ({$activeYear->semester})",
                'message'           => $isOpen ? 'Periode pengisian KRS saat ini sedang dibuka.' : 'Periode pengisian KRS saat ini ditutup.',
                'period'            => $activePeriod,
                'server_time'       => Carbon::now()->toIso8601String(),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tambah Periode KRS Baru
     * POST /api/admin/krs-periods
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id'  => 'required|exists:academic_years,id',
            'study_program_id'  => 'nullable|exists:study_programs,id',
            'name'              => 'required|string|max:150',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after:start_date',
            'is_open'           => 'nullable|boolean',
            'auto_by_date'      => 'nullable|boolean',
            'allow_revision'    => 'nullable|boolean',
            'revision_end_date' => 'nullable|date|after:end_date',
            'notes'             => 'nullable|string',
        ], [
            'academic_year_id.required' => 'Tahun akademik wajib dipilih.',
            'name.required'             => 'Nama periode KRS wajib diisi.',
            'start_date.required'       => 'Tanggal mulai KRS wajib diisi.',
            'end_date.required'         => 'Tanggal selesai KRS wajib diisi.',
            'end_date.after'            => 'Tanggal selesai harus setelah tanggal mulai.',
            'revision_end_date.after'   => 'Tanggal selesai revisi harus setelah tanggal selesai KRS.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $period = KrsPeriod::create($request->all());

            AuditLogger::log('CREATE_KRS_PERIOD', 'KRS', [
                'name'       => $period->name,
                'start_date' => $period->start_date,
                'end_date'   => $period->end_date,
            ], $period->id);

            return response()->json([
                'success' => true,
                'message' => 'Periode KRS berhasil dibuat!',
                'data'    => $period->load(['academicYear', 'studyProgram']),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Detail Periode KRS
     * GET /api/admin/krs-periods/{id}
     */
    public function show($id)
    {
        try {
            $period = KrsPeriod::with(['academicYear', 'studyProgram'])->find($id);
            if (!$period) {
                return response()->json(['success' => false, 'message' => 'Periode KRS tidak ditemukan.'], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => array_merge($period->toArray(), [
                    'is_open_now'          => $period->isOpenNow(),
                    'is_revision_open_now' => $period->isRevisionOpenNow(),
                ]),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Periode KRS
     * PUT /api/admin/krs-periods/{id}
     */
    public function update(Request $request, $id)
    {
        $period = KrsPeriod::find($id);
        if (!$period) {
            return response()->json(['success' => false, 'message' => 'Periode KRS tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'academic_year_id'  => 'required|exists:academic_years,id',
            'study_program_id'  => 'nullable|exists:study_programs,id',
            'name'              => 'required|string|max:150',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after:start_date',
            'is_open'           => 'nullable|boolean',
            'auto_by_date'      => 'nullable|boolean',
            'allow_revision'    => 'nullable|boolean',
            'revision_end_date' => 'nullable|date|after:end_date',
            'notes'             => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $period->update($request->all());

            AuditLogger::log('UPDATE_KRS_PERIOD', 'KRS', [
                'name'       => $period->name,
                'start_date' => $period->start_date,
                'end_date'   => $period->end_date,
            ], $period->id);

            return response()->json([
                'success' => true,
                'message' => 'Periode KRS berhasil diperbarui!',
                'data'    => $period->load(['academicYear', 'studyProgram']),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Buka / Tutup Manual Periode KRS (Toggle)
     * POST /api/admin/krs-periods/{id}/toggle
     */
    public function toggle($id)
    {
        try {
            $period = KrsPeriod::find($id);
            if (!$period) {
                return response()->json(['success' => false, 'message' => 'Periode KRS tidak ditemukan.'], 404);
            }

            // Jika awalnya auto_by_date, kita toggle override manual
            $period->is_open = !$period->is_open;
            $period->auto_by_date = false; // Matikan auto sementara saat di-override manual
            $period->save();

            $statusText = $period->is_open ? 'dibuka' : 'ditutup';

            AuditLogger::log('TOGGLE_KRS_PERIOD', 'KRS', [
                'period_name' => $period->name,
                'is_open'     => $period->is_open,
            ], $period->id);

            return response()->json([
                'success' => true,
                'message' => "Periode KRS '{$period->name}' telah berhasil diubah menjadi {$statusText} secara manual.",
                'data'    => $period,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Periode KRS
     * DELETE /api/admin/krs-periods/{id}
     */
    public function destroy($id)
    {
        try {
            $period = KrsPeriod::find($id);
            if (!$period) {
                return response()->json(['success' => false, 'message' => 'Periode KRS tidak ditemukan.'], 404);
            }

            $name = $period->name;
            $period->delete();

            AuditLogger::log('DELETE_KRS_PERIOD', 'KRS', ['name' => $name], $id);

            return response()->json(['success' => true, 'message' => "Periode KRS '{$name}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
