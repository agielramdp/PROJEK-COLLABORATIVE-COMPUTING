<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\StudentStatus;
use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SiakadMasterSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Admin', 'Dosen', 'Mahasiswa', 'Bendahara', 'Dosen Wali'];

        foreach ($roles as $roleName) {
            Role::updateOrCreate(
                ['slug' => str($roleName)->slug()->toString()],
                ['name' => $roleName]
            );
        }

        $permissions = [
            'users.manage' => 'Manage Users',
            'students.manage' => 'Manage Students',
            'lecturers.manage' => 'Manage Lecturers',
            'academics.manage' => 'Manage Academic Data',
            'grades.manage' => 'Manage Grades',
            'announcements.manage' => 'Manage Announcements',
        ];

        foreach ($permissions as $slug => $name) {
            Permission::updateOrCreate(['slug' => $slug], ['name' => $name]);
        }

        $admin = Role::where('slug', 'admin')->first();
        if ($admin) {
            $admin->permissions()->sync(
                Permission::whereIn('slug', array_keys($permissions))->pluck('id')->all()
            );
        }

        foreach ([['active', 'Aktif'], ['leave', 'Cuti'], ['inactive', 'Nonaktif'], ['graduated', 'Lulus']] as [$code, $name]) {
            StudentStatus::updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'is_active' => true]
            );
        }

        foreach ([
            'passing_grade' => ['0.0', 'decimal', 'Minimum passing grade'],
            'minimum_attendance_percentage' => ['75', 'integer', 'Minimum attendance percentage'],
        ] as $key => [$value, $valueType, $description]) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'value_type' => $valueType, 'description' => $description]
            );
        }
    }
}
