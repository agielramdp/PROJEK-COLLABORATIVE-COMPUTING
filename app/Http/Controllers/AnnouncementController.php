<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class AnnouncementController extends Controller
{
    /**
     * Daftar Semua Pengumuman (Admin) (Item 25)
     * GET /api/admin/announcements
     */
    public function index(Request $request)
    {
        try {
            $query = Announcement::with('author');

            if ($request->filled('target_role') && $request->target_role !== 'Semua') {
                $query->where('target_role', $request->target_role);
            }
            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }
            if ($request->filled('search')) {
                $search = trim($request->search);
                $query->where('title', 'LIKE', "%{$search}%");
            }

            $announcements = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data'    => $announcements,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Feed Pengumuman untuk Dashboard Pengguna (Item 25)
     * GET /api/announcements/feed
     */
    public function feed(Request $request)
    {
        try {
            $user = Auth::user();
            $role = $request->input('role', $user ? $user->role : 'Semua');

            $announcements = Announcement::activeForRole($role)
                ->with('author')
                ->take(10)
                ->get();

            return response()->json([
                'success' => true,
                'role'    => $role,
                'data'    => $announcements,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Buat Pengumuman Baru (Item 25)
     * POST /api/admin/announcements
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'           => 'required|string|max:200',
            'content'         => 'required|string',
            'target_role'     => 'required|in:Semua,Mahasiswa,Dosen Pengajar,Dosen Wali,Bendahara,Admin',
            'priority'        => 'required|in:Biasa,Penting,Mendesak',
            'is_published'    => 'nullable|boolean',
            'published_at'    => 'nullable|date',
            'expires_at'      => 'nullable|date|after_or_equal:published_at',
            'attachment_path' => 'nullable|string|max:255',
        ], [
            'title.required'       => 'Judul pengumuman wajib diisi.',
            'content.required'     => 'Isi konten pengumuman wajib diisi.',
            'target_role.required' => 'Sasaran role pengumuman wajib dipilih.',
            'priority.required'    => 'Prioritas pengumuman wajib dipilih (Biasa, Penting, Mendesak).',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::user();

            $announcement = Announcement::create([
                'title'           => trim($request->title),
                'slug'            => Str::slug($request->title) . '-' . time(),
                'content'         => $request->content,
                'target_role'     => $request->target_role,
                'priority'        => $request->priority,
                'author_id'       => $user ? $user->id : null,
                'is_published'    => $request->boolean('is_published', true),
                'published_at'    => $request->published_at ? Carbon::parse($request->published_at) : now(),
                'expires_at'      => $request->expires_at ? Carbon::parse($request->expires_at) : null,
                'attachment_path' => $request->attachment_path,
            ]);

            AuditLogger::log('CREATE_ANNOUNCEMENT', 'Announcements', [
                'title'       => $announcement->title,
                'target_role' => $announcement->target_role,
                'priority'    => $announcement->priority,
            ], $announcement->id);

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil dipublikasikan!',
                'data'    => $announcement->load('author'),
            ], 201);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Detail Pengumuman
     * GET /api/admin/announcements/{id}
     */
    public function show($id)
    {
        try {
            $announcement = Announcement::with('author')->find($id);
            if (!$announcement) {
                return response()->json(['success' => false, 'message' => 'Pengumuman tidak ditemukan.'], 404);
            }
            return response()->json(['success' => true, 'data' => $announcement]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Pengumuman
     * PUT /api/admin/announcements/{id}
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::find($id);
        if (!$announcement) {
            return response()->json(['success' => false, 'message' => 'Pengumuman tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'           => 'required|string|max:200',
            'content'         => 'required|string',
            'target_role'     => 'required|in:Semua,Mahasiswa,Dosen Pengajar,Dosen Wali,Bendahara,Admin',
            'priority'        => 'required|in:Biasa,Penting,Mendesak',
            'is_published'    => 'nullable|boolean',
            'published_at'    => 'nullable|date',
            'expires_at'      => 'nullable|date|after_or_equal:published_at',
            'attachment_path' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first(), 'errors' => $validator->errors()], 422);
        }

        try {
            $announcement->update($request->all());

            AuditLogger::log('UPDATE_ANNOUNCEMENT', 'Announcements', ['title' => $announcement->title], $announcement->id);

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil diperbarui!',
                'data'    => $announcement->load('author'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Pengumuman
     * DELETE /api/admin/announcements/{id}
     */
    public function destroy($id)
    {
        try {
            $announcement = Announcement::find($id);
            if (!$announcement) {
                return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
            }

            $title = $announcement->title;
            $announcement->delete();
            AuditLogger::log('DELETE_ANNOUNCEMENT', 'Announcements', ['title' => $title], $id);

            return response()->json(['success' => true, 'message' => "Pengumuman '{$title}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
