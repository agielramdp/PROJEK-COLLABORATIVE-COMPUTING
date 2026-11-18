<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Throwable;

class AuditLogController extends Controller
{
    /**
     * Tampilkan Daftar Audit Log Aktivitas Admin & Sistem (Item 11)
     * GET /api/admin/audit-logs
     */
    public function index(Request $request)
    {
        try {
            $query = AuditLog::query();

            // Filter Pencarian
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where(function ($q) use ($search) {
                    $q->where('user_name', 'LIKE', "%{$search}%")
                      ->orWhere('action', 'LIKE', "%{$search}%")
                      ->orWhere('module', 'LIKE', "%{$search}%")
                      ->orWhere('ip_address', 'LIKE', "%{$search}%");
                });
            }

            // Filter Modul
            if ($request->filled('module')) {
                $query->where('module', $request->module);
            }

            // Filter Aksi
            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            // Filter User
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter Rentang Tanggal
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            $perPage = (int) $request->input('per_page', 15);
            if ($perPage < 1) $perPage = 15;
            if ($perPage > 100) $perPage = 100;

            $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data'    => $logs->items(),
                'meta'    => [
                    'current_page' => $logs->currentPage(),
                    'last_page'    => $logs->lastPage(),
                    'per_page'     => $logs->perPage(),
                    'total'        => $logs->total(),
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Dapatkan modul-modul yang tercatat di audit log untuk opsi filter
     * GET /api/admin/audit-logs/modules
     */
    public function getModules()
    {
        try {
            $modules = AuditLog::distinct()->pluck('module')->filter()->values();
            $actions = AuditLog::distinct()->pluck('action')->filter()->values();

            return response()->json([
                'success' => true,
                'modules' => $modules,
                'actions' => $actions,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Catat log aktivitas custom secara manual
     * POST /api/admin/audit-logs
     */
    public function store(Request $request)
    {
        $request->validate([
            'action'  => 'required|string|max:100',
            'module'  => 'required|string|max:100',
            'details' => 'nullable|array',
        ]);

        AuditLogger::log(
            $request->action,
            $request->module,
            $request->details,
            $request->record_id
        );

        return response()->json(['success' => true, 'message' => 'Audit log berhasil dicatat.']);
    }
}
