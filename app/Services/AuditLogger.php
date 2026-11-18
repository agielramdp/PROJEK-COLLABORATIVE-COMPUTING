<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Throwable;

class AuditLogger
{
    /**
     * Catat aktivitas ke audit log
     *
     * @param string $action Nama aksi (misal: 'CREATE_USER', 'RESET_PASSWORD', 'UNLOCK_GRADE')
     * @param string $module Nama modul (misal: 'Users', 'Roles', 'KRS', 'Grades')
     * @param array|null $details Rincian data dalam format array/json
     * @param string|int|null $recordId ID record terkait
     * @param int|null $userId User ID yang melakukan aksi (opsional, default: current authenticated user)
     */
    public static function log(string $action, string $module, ?array $details = null, $recordId = null, $userId = null): void
    {
        try {
            $user = Auth::user();

            $logUserId = $userId ?? ($user ? $user->id : null);
            $logUserName = $user ? $user->name : 'Sistem/Admin';
            $logUserRole = $user ? ($user->role ?? 'Admin') : 'Admin';

            AuditLog::create([
                'user_id'    => $logUserId,
                'user_name'  => $logUserName,
                'user_role'  => $logUserRole,
                'action'     => strtoupper($action),
                'module'     => $module,
                'record_id'  => $recordId ? (string) $recordId : null,
                'details'    => $details,
                'ip_address' => Request::ip() ?? '127.0.0.1',
                'user_agent' => Request::userAgent() ?? 'CLI/Web',
                'created_at' => now(),
            ]);
        } catch (Throwable $e) {
            // Catat kegagalan ke system log tanpa menghentikan request utama
            \Illuminate\Support\Facades\Log::warning("Failed to write audit log: {$e->getMessage()}");
        }
    }
}
