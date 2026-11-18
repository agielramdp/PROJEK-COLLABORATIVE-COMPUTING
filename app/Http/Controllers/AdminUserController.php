<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AdminUserController extends Controller
{
    /**
     * Tampilkan halaman utama Manajemen Pengguna (Blade View)
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Endpoint API: Daftar pengguna dengan pencarian, filter role, dan pagination
     * GET /api/admin/users
     */
    public function apiIndex(Request $request)
    {
        try {
            $search  = trim($request->input('search', ''));
            $role    = trim($request->input('role', ''));
            $perPage = (int) $request->input('per_page', 5);
            if ($perPage < 1) $perPage = 5;
            if ($perPage > 100) $perPage = 100;

            $query = User::query();

            // Filter Pencarian (Nama, Username, Email, NPM/NIP)
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('username', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('npm', 'LIKE', "%{$search}%");
                });
            }

            // Filter Role
            if (!empty($role) && strtolower($role) !== 'semua' && strtolower($role) !== 'all') {
                $query->where('role', $role);
            }

            // Order by ID descending (terbaru di atas)
            $query->orderBy('id', 'asc');

            $paginated = $query->paginate($perPage);

            // Format items agar sesuai dengan kebutuhan UI
            $items = collect($paginated->items())->map(function ($user, $index) use ($paginated) {
                return [
                    'id'              => $user->id,
                    'row_number'      => ($paginated->currentPage() - 1) * $paginated->perPage() + $index + 1,
                    'name'            => $user->name,
                    'username'        => $user->username ?? strtolower(str_replace(' ', '.', $user->name)),
                    'email'           => $user->email,
                    'role'            => $user->role ?? 'Mahasiswa',
                    'npm'             => $user->npm ?? '-',
                    'identity_number' => $user->npm ?? $user->identity_number ?? '-',
                    'status'          => $user->status ?? 'Aktif',
                    'created_at'      => $user->created_at ? $user->created_at->format('Y-m-d H:i') : null,
                ];
            });

            return response()->json([
                'success' => true,
                'source'  => 'database',
                'data'    => $items,
                'meta'    => [
                    'current_page' => $paginated->currentPage(),
                    'last_page'    => $paginated->lastPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                    'from'         => $paginated->firstItem() ?? 0,
                    'to'           => $paginated->lastItem() ?? 0,
                ],
            ]);
        } catch (Throwable $e) {
            Log::warning('AdminUserController@apiIndex fallback: ' . $e->getMessage());

            // Kembalikan data mock terstruktur jika koneksi database sedang tidak aktif
            return $this->getMockUsersResponse($request);
        }
    }

    /**
     * Endpoint API: Tambah akun pengguna baru
     * POST /api/admin/users
     */
    public function apiStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'email'    => 'required|email|max:255',
            'role'     => 'required|string',
            'npm'      => 'nullable|string|max:50',
            'password' => 'required|string|min:6',
            'status'   => 'nullable|string',
        ], [
            'name.required'     => 'Nama lengkap pengguna wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'role.required'     => 'Role pengguna wajib dipilih.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            // Cek duplikasi email atau username
            $exists = User::where('email', $request->email)
                          ->orWhere('username', $request->username)
                          ->first();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau Username sudah terdaftar di sistem.',
                ], 422);
            }

            $user = new User();
            $user->name          = trim($request->name);
            $user->username      = trim($request->username);
            $user->email         = strtolower(trim($request->email));
            $user->role          = trim($request->role);
            $user->npm           = trim($request->npm ?? '');
            $user->password_hash = Hash::make($request->password);

            if (Schema::hasColumn('users', 'status')) {
                $user->status = $request->status ?? 'Aktif';
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Akun pengguna berhasil ditambahkan!',
                'data'    => [
                    'id'       => $user->id,
                    'name'     => $user->name,
                    'username' => $user->username,
                    'email'    => $user->email,
                    'role'     => $user->role,
                    'npm'      => $user->npm,
                    'status'   => $user->status ?? 'Aktif',
                ],
            ], 201);
        } catch (Throwable $e) {
            Log::error('AdminUserController@apiStore error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan ke database: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint API: Detail pengguna berdasarkan ID
     * GET /api/admin/users/{id}
     */
    public function apiShow($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => [
                    'id'              => $user->id,
                    'name'            => $user->name,
                    'username'        => $user->username,
                    'email'           => $user->email,
                    'role'            => $user->role,
                    'npm'             => $user->npm,
                    'identity_number' => $user->npm,
                    'status'          => $user->status ?? 'Aktif',
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint API: Update akun pengguna
     * PUT /api/admin/users/{id}
     */
    public function apiUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:100',
            'email'    => 'required|email|max:255',
            'role'     => 'required|string',
            'npm'      => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6',
            'status'   => 'nullable|string',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'role.required'     => 'Role wajib dipilih.',
            'password.min'      => 'Kata sandi baru minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.',
                ], 404);
            }

            // Validasi keunikan email & username untuk user lain
            $duplicate = User::where(function ($q) use ($request) {
                $q->where('email', $request->email)
                  ->orWhere('username', $request->username);
            })->where('id', '!=', $id)->first();

            if ($duplicate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau Username sudah digunakan oleh pengguna lain.',
                ], 422);
            }

            $user->name     = trim($request->name);
            $user->username = trim($request->username);
            $user->email    = strtolower(trim($request->email));
            $user->role     = trim($request->role);
            $user->npm      = trim($request->npm ?? '');

            if (!empty($request->password)) {
                $user->password_hash = Hash::make($request->password);
            }

            if (Schema::hasColumn('users', 'status')) {
                $user->status = $request->status ?? 'Aktif';
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Data pengguna berhasil diperbarui!',
                'data'    => [
                    'id'       => $user->id,
                    'name'     => $user->name,
                    'username' => $user->username,
                    'email'    => $user->email,
                    'role'     => $user->role,
                    'npm'      => $user->npm,
                    'status'   => $user->status ?? 'Aktif',
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pengguna: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint API: Hapus akun pengguna
     * DELETE /api/admin/users/{id}
     */
    public function apiDestroy($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.',
                ], 404);
            }

            $userName = $user->name;
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "Akun pengguna '{$userName}' berhasil dihapus.",
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pengguna: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Fallback mock response jika database belum aktif atau terjadi gangguan koneksi
     */
    private function getMockUsersResponse(Request $request)
    {
        $mock = [
            [
                'id' => 1,
                'name' => 'Admin Akademik',
                'username' => 'Admin.akademik',
                'email' => 'admin@siakad.ac.id',
                'role' => 'Admin',
                'npm' => '198501012010121001',
                'identity_number' => '198501012010121001',
                'status' => 'Aktif',
            ],
            [
                'id' => 2,
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'username' => 'budi.santoso',
                'email' => 'dosen@siakad.ac.id',
                'role' => 'Dosen Pengajar',
                'npm' => '0412088501',
                'identity_number' => '0412088501',
                'status' => 'Aktif',
            ],
            [
                'id' => 3,
                'name' => 'Siti Rahmawati, S.Kom., M.Cs.',
                'username' => 'siti.rahmawati',
                'email' => 'wali@siakad.ac.id',
                'role' => 'Dosen Wali',
                'npm' => '0425097902',
                'identity_number' => '0425097902',
                'status' => 'Aktif',
            ],
            [
                'id' => 4,
                'name' => 'Dewi Lestari, S.E., M.Ak.',
                'username' => 'dewi.lestari',
                'email' => 'bendahara@siakad.ac.id',
                'role' => 'Bendahara',
                'npm' => 'STAF-091',
                'identity_number' => 'STAF-091',
                'status' => 'Aktif',
            ],
            [
                'id' => 5,
                'name' => 'Agiel Ramadhan Pratama',
                'username' => 'agiel.ramadhan',
                'email' => 'agiel@student.siakad.ac.id',
                'role' => 'Mahasiswa',
                'npm' => '2201010142',
                'identity_number' => '2201010142',
                'status' => 'Aktif',
            ],
            [
                'id' => 6,
                'name' => 'Rian Hidayat',
                'username' => 'rian.hidayat',
                'email' => 'rian@student.siakad.ac.id',
                'role' => 'Mahasiswa',
                'npm' => '2201010048',
                'identity_number' => '2201010048',
                'status' => 'Nonaktif',
            ],
        ];

        $search  = strtolower(trim($request->input('search', '')));
        $role    = strtolower(trim($request->input('role', '')));
        $page    = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 5);

        $filtered = array_filter($mock, function ($u) use ($search, $role) {
            $matchSearch = true;
            if ($search !== '') {
                $matchSearch = str_contains(strtolower($u['name']), $search) ||
                               str_contains(strtolower($u['username']), $search) ||
                               str_contains(strtolower($u['email']), $search) ||
                               str_contains(strtolower($u['npm']), $search);
            }

            $matchRole = true;
            if ($role !== '' && $role !== 'semua' && $role !== 'all') {
                $matchRole = strtolower($u['role']) === $role;
            }

            return $matchSearch && $matchRole;
        });

        $filtered = array_values($filtered);
        $total    = count($filtered);
        $offset   = ($page - 1) * $perPage;
        $sliced   = array_slice($filtered, $offset, $perPage);

        $items = [];
        foreach ($sliced as $idx => $item) {
            $item['row_number'] = $offset + $idx + 1;
            $items[] = $item;
        }

        $lastPage = (int) ceil($total / $perPage);
        if ($lastPage < 1) $lastPage = 1;

        return response()->json([
            'success' => true,
            'source'  => 'mock_fallback',
            'data'    => $items,
            'meta'    => [
                'current_page' => $page,
                'last_page'    => $lastPage,
                'per_page'     => $perPage,
                'total'        => $total,
                'from'         => $total > 0 ? $offset + 1 : 0,
                'to'           => min($offset + $perPage, $total),
            ],
        ]);
    }
}
