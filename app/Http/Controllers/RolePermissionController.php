<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class RolePermissionController extends Controller
{
    /**
     * Tampilkan semua role beserta jumlah pengguna & permission
     * GET /api/admin/roles
     */
    public function indexRoles()
    {
        try {
            $roles = Role::with(['permissions'])->withCount(['users'])->get();

            return response()->json([
                'success' => true,
                'data'    => $roles,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat roles: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tambah Role Baru
     * POST /api/admin/roles
     */
    public function storeRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:100|unique:roles,name',
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ], [
            'name.required' => 'Nama role wajib diisi.',
            'name.unique'   => 'Nama role sudah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $role = Role::create([
                'name'        => trim($request->name),
                'slug'        => Str::slug($request->name, '_'),
                'description' => $request->description,
                'is_system'   => false,
            ]);

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            AuditLogger::log('CREATE_ROLE', 'Roles', [
                'name' => $role->name,
                'slug' => $role->slug,
            ], $role->id);

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dibuat!',
                'data'    => $role->load('permissions'),
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat role: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Detail Role
     * GET /api/admin/roles/{id}
     */
    public function showRole($id)
    {
        try {
            $role = Role::with('permissions')->find($id);
            if (!$role) {
                return response()->json(['success' => false, 'message' => 'Role tidak ditemukan.'], 404);
            }

            return response()->json(['success' => true, 'data' => $role]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update Role
     * PUT /api/admin/roles/{id}
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:100|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $role->name = trim($request->name);
            if (!$role->is_system) {
                $role->slug = Str::slug($request->name, '_');
            }
            $role->description = $request->description;
            $role->save();

            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }

            AuditLogger::log('UPDATE_ROLE', 'Roles', [
                'name' => $role->name,
            ], $role->id);

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil diperbarui!',
                'data'    => $role->load('permissions'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Hapus Role
     * DELETE /api/admin/roles/{id}
     */
    public function destroyRole($id)
    {
        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json(['success' => false, 'message' => 'Role tidak ditemukan.'], 404);
            }

            if ($role->is_system) {
                return response()->json(['success' => false, 'message' => 'Role sistem bawaan tidak dapat dihapus.'], 403);
            }

            $roleName = $role->name;
            $role->delete();

            AuditLogger::log('DELETE_ROLE', 'Roles', ['name' => $roleName], $id);

            return response()->json(['success' => true, 'message' => "Role '{$roleName}' berhasil dihapus."]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Daftar Semua Hak Akses / Permissions
     * GET /api/admin/permissions
     */
    public function indexPermissions()
    {
        try {
            $permissions = Permission::all()->groupBy('group');

            return response()->json([
                'success' => true,
                'data'    => $permissions,
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Simpan / Sync Permission untuk Suatu Role
     * POST /api/admin/roles/{id}/permissions
     */
    public function syncPermissions(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['success' => false, 'message' => 'Role tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'permissions'   => 'present|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $role->permissions()->sync($request->permissions);

            AuditLogger::log('SYNC_PERMISSIONS', 'Roles', [
                'role_id'          => $role->id,
                'role_name'        => $role->name,
                'permission_count' => count($request->permissions),
            ], $role->id);

            return response()->json([
                'success' => true,
                'message' => "Hak akses untuk role '{$role->name}' berhasil diperbarui!",
                'data'    => $role->load('permissions'),
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Tetapkan Role ke Pengguna
     * POST /api/admin/users/{id}/assign-role
     */
    public function assignUserRole(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Pengguna tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $oldRole = $user->role;
            $newRole = trim($request->role);

            $user->role = $newRole;
            $user->save();

            // Sync ke user_has_roles jika tabel role ada
            $roleRecord = Role::where('name', $newRole)->orWhere('slug', Str::slug($newRole, '_'))->first();
            if ($roleRecord) {
                $user->assignedRoles()->sync([$roleRecord->id]);
            }

            AuditLogger::log('ASSIGN_ROLE', 'Roles', [
                'target_user_id' => $user->id,
                'old_role'       => $oldRole,
                'new_role'       => $newRole,
            ], $user->id);

            return response()->json([
                'success' => true,
                'message' => "Role pengguna '{$user->name}' berhasil diubah menjadi '{$newRole}'.",
                'data'    => [
                    'id'   => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                ],
            ]);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
