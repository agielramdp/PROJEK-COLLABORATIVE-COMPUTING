<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Mahasiswa (Primary demo matching Google screen: mahasiswa123@gmail.com)
        User::updateOrCreate(
            ['email' => 'mahasiswa123@gmail.com'],
            [
                'name' => 'Mahasiswa',
                'role' => 'mahasiswa',
                'identity_number' => '2201010048',
                'avatar' => 'M',
                'google_id' => 'google_sub_1092837461',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Mahasiswa Alternatif
        User::updateOrCreate(
            ['email' => 'agiel@student.siakad.ac.id'],
            [
                'name' => 'Agiel Ramadhan Pratama',
                'role' => 'mahasiswa',
                'identity_number' => '2201010142',
                'avatar' => 'AR',
                'google_id' => null,
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Admin
        User::updateOrCreate(
            ['email' => 'admin@siakad.ac.id'],
            [
                'name' => 'Drs. H. Hendra Wijaya, M.Kom',
                'role' => 'admin',
                'identity_number' => '198501012010121001',
                'avatar' => 'HW',
                'google_id' => null,
                'password' => Hash::make('admin123'),
            ]
        );

        // 4. Dosen Pengajar
        User::updateOrCreate(
            ['email' => 'dosen@siakad.ac.id'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.T.',
                'role' => 'dosen_pengajar',
                'identity_number' => '0412088501',
                'avatar' => 'BS',
                'google_id' => null,
                'password' => Hash::make('dosen123'),
            ]
        );

        // 5. Dosen Wali
        User::updateOrCreate(
            ['email' => 'wali@siakad.ac.id'],
            [
                'name' => 'Siti Rahmawati, S.Kom., M.Cs.',
                'role' => 'dosen_wali',
                'identity_number' => '0425097902',
                'avatar' => 'SR',
                'google_id' => null,
                'password' => Hash::make('wali123'),
            ]
        );

        // 6. Bendahara
        User::updateOrCreate(
            ['email' => 'bendahara@siakad.ac.id'],
            [
                'name' => 'Dewi Lestari, S.E., M.Ak.',
                'role' => 'bendahara',
                'identity_number' => 'STAF-091',
                'avatar' => 'DL',
                'google_id' => null,
                'password' => Hash::make('bendahara123'),
            ]
        );
    }
}
