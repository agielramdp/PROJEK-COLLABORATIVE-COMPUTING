<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the db_sistem_akademik database with default roles.
     */
    public function run(): void
    {
        // 1. Mahasiswa (Primary demo matching Google: mahasiswa123@gmail.com)
        User::updateOrCreate(
            ['email' => 'mahasiswa123@gmail.com'],
            [
                'npm'           => '2201010048',
                'name'          => 'Mahasiswa',
                'username'      => 'mahasiswa',
                'role'          => 'Mahasiswa',
                'google_id'     => 'google_sub_1092837461',
                'password_hash' => Hash::make('password123'),
            ]
        );

        // 2. Mahasiswa Alternatif
        User::updateOrCreate(
            ['email' => 'agiel@student.siakad.ac.id'],
            [
                'npm'           => '2201010142',
                'name'          => 'Agiel Ramadhan Pratama',
                'username'      => 'agiel',
                'role'          => 'Mahasiswa',
                'google_id'     => null,
                'password_hash' => Hash::make('password123'),
            ]
        );

        // 3. Admin
        User::updateOrCreate(
            ['email' => 'admin@siakad.ac.id'],
            [
                'npm'           => '198501012010121001',
                'name'          => 'Drs. H. Hendra Wijaya, M.Kom',
                'username'      => 'admin',
                'role'          => 'Admin',
                'google_id'     => null,
                'password_hash' => Hash::make('admin123'),
            ]
        );

        // 4. Dosen Pengajar
        User::updateOrCreate(
            ['email' => 'dosen@siakad.ac.id'],
            [
                'npm'           => '0412088501',
                'name'          => 'Dr. Ir. Budi Santoso, M.T.',
                'username'      => 'dosen',
                'role'          => 'Dosen Pengajar',
                'google_id'     => null,
                'password_hash' => Hash::make('dosen123'),
            ]
        );

        // 5. Dosen Wali
        User::updateOrCreate(
            ['email' => 'wali@siakad.ac.id'],
            [
                'npm'           => '0425097902',
                'name'          => 'Siti Rahmawati, S.Kom., M.Cs.',
                'username'      => 'dosenwali',
                'role'          => 'Dosen Wali',
                'google_id'     => null,
                'password_hash' => Hash::make('wali123'),
            ]
        );

        // 6. Bendahara
        User::updateOrCreate(
            ['email' => 'bendahara@siakad.ac.id'],
            [
                'npm'           => 'STAF-091',
                'name'          => 'Dewi Lestari, S.E., M.Ak.',
                'username'      => 'bendahara',
                'role'          => 'Bendahara',
                'google_id'     => null,
                'password_hash' => Hash::make('bendahara123'),
            ]
        );
    }
}
