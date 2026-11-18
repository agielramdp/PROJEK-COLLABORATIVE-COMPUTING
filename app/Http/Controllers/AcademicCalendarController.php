<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendarEvent;
use App\Models\AcademicYear;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AcademicCalendarController extends Controller
{
    /**
     * Daftar Tanggal Penting Kalender Akademik (Admin) (Item 24)
     * GET /api/admin/academic-calendar
     */
    public function index(Request $request)
    {
        try {
            $query = AcademicCalendarEvent::with('academicYear');

            if ($request->filled('academic_year_id')) {
                $query->where('academic_year_id', $request->academic_year_id);
            }
            if ($request->filled('event_type')) {
                $query->where('event_type', $request->event_type);
            }

            $events = $query->orderBy('start_date', 'asc')->get();

            return response()->json([
                'success' => true,
                'data'    => $events,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Feed Agenda Kalender Akademik untuk Dashboard Mahasiswa / Publik
     * GET /api/academic-calendar
     */
    public function feed(Request $request)
    {
        try {
            $activeYear = AcademicYear::getActive();

            $events = AcademicCalendarEvent::where('is_public', true)
                ->when($activeYear, fn($q) => $q->where(function ($sub) use ($activeYear) {
                    $sub->whereNull('academic_year_id')->orWhere('academic_year_id', $activeYear->id);
                }))
                ->orderBy('start_date', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data'    => $events,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tambah Tanggal Penting Baru (Item 24)
     * POST /api/admin/academic-calendar
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:200',
            'event_type'       => 'required|in:perkuliahan,uts,uas,input_nilai,krs,registrasi,libur,wisuda,lainnya',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'description'      => 'nullable|string',
            'is_public'        => 'nullable|boolean',
        ], [
            'title.required'          => 'Judul agenda akademik wajib diisi.',
            'event_type.required'     => 'Kategori agenda wajib dipilih (perkuliahan, uts, uas, input_nilai, krs, dll).',
            'start_date.required'     => 'Tanggal mulai agenda wajib diisi.',
            'end_date.required'       => 'Tanggal selesai agenda wajib diisi.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $academicYearId = $request->academic_year_id ?: optional(AcademicYear::getActive())->id;

            $event = AcademicCalendarEvent::create([
                'title'            => trim($request->title),
                'event_type'       => $request->event_type,
                'start_date'       => $request->start_date,
                'end_date'         => $request->end_date,
                'academic_year_id' => $academicYearId,
                'description'      => $request->description,
                'is_public'        => $request->boolean('is_public', true),
            ]);

            AuditLogger::log('CREATE_CALENDAR_EVENT', 'Calendar', [
                'title'      => $event->title,
                'event_type' => $event->event_type,
                'dates'      => "{$event->start_date} s/d {$event->end_date}",
            ], $event->id);

            return response()->json([
                'success' => true,
                'message' => 'Agenda tanggal penting berhasil ditambahkan!',
                'data'    => $event->load('academicYear'),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Detail Agenda Kalender
     * GET /api/admin/academic-calendar/{id}
     */
    public function show($id)
    {
        try {
            $event = AcademicCalendarEvent::with('academicYear')->find($id);
            if (!$event) {
                return response()->json(['success' => false, 'message' => 'Agenda tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $event]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Agenda Kalender
     * PUT /api/admin/academic-calendar/{id}
     */
    public function update(Request $request, $id)
    {
        $event = AcademicCalendarEvent::find($id);
        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Agenda tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:200',
            'event_type'       => 'required|in:perkuliahan,uts,uas,input_nilai,krs,registrasi,libur,wisuda,lainnya',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'description'      => 'nullable|string',
            'is_public'        => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $event->update($request->all());

            AuditLogger::log('UPDATE_CALENDAR_EVENT', 'Calendar', ['title' => $event->title], $event->id);

            return response()->json([
                'success' => true,
                'message' => 'Agenda tanggal penting berhasil diperbarui!',
                'data'    => $event->load('academicYear'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Agenda Kalender
     * DELETE /api/admin/academic-calendar/{id}
     */
    public function destroy($id)
    {
        try {
            $event = AcademicCalendarEvent::find($id);
            if (!$event) {
                return response()->json(['success' => false, 'message' => 'Agenda tidak ditemukan.'], 404);
            }

            $title = $event->title;
            $event->delete();
            AuditLogger::log('DELETE_CALENDAR_EVENT', 'Calendar', ['title' => $title], $id);

            return response()->json(['success' => true, 'message' => "Agenda '{$title}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
