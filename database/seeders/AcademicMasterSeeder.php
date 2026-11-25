<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Curriculum;
use App\Models\EducationLevel;
use App\Models\Faculty;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class AcademicMasterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jenjang Pendidikan
        $levels = [
            ['code' => 'D3', 'name' => 'Diploma III', 'degree_title' => 'A.Md.', 'standard_credits' => 108, 'standard_semesters' => 6],
            ['code' => 'S1', 'name' => 'Sarjana', 'degree_title' => 'S.Kom. / S.E. / S.T.', 'standard_credits' => 144, 'standard_semesters' => 8],
            ['code' => 'S2', 'name' => 'Magister', 'degree_title' => 'M.Kom. / M.M.', 'standard_credits' => 36, 'standard_semesters' => 4],
            ['code' => 'S3', 'name' => 'Doktor', 'degree_title' => 'Dr.', 'standard_credits' => 42, 'standard_semesters' => 6],
        ];

        $levelMap = [];
        foreach ($levels as $lvl) {
            $record = EducationLevel::updateOrCreate(['code' => $lvl['code']], $lvl);
            $levelMap[$lvl['code']] = $record->id;
        }

        // 2. Fakultas (5 Fakultas umum di kampus)
        $faculties = [
            [
                'code' => 'FTI',
                'name' => 'Teknologi & Informatika',
                'dean_name' => 'Prof. Dr. Ir. Hendra Wijaya, M.T.',
                'description' => 'Fakultas yang berfokus pada teknologi informasi, ilmu komputer, dan sistem informasi.',
                'is_active' => true,
            ],
            [
                'code' => 'FEB',
                'name' => 'Ekonomi & Bisnis',
                'dean_name' => 'Dr. Siti Aminah, S.E., M.M.',
                'description' => 'Fakultas yang membidangi manajemen, akuntansi, dan kewirausahaan digital.',
                'is_active' => true,
            ],
            [
                'code' => 'FISH',
                'name' => 'Ilmu Sosial & Humaniora',
                'dean_name' => 'Dr. Bambang Supriyadi, M.Si.',
                'description' => 'Fakultas yang mencakup ilmu komunikasi, media kreatif, dan humaniora.',
                'is_active' => true,
            ],
            [
                'code' => 'FT',
                'name' => 'Teknik',
                'dean_name' => 'Prof. Dr. Ir. Agus Santoso, M.Eng.',
                'description' => 'Fakultas keteknikan sipil, elektro, dan industri.',
                'is_active' => true,
            ],
            [
                'code' => 'FHK',
                'name' => 'Hukum',
                'dean_name' => 'Dr. Ratna Dewi, S.H., M.H.',
                'description' => 'Fakultas ilmu hukum dan kebijakan publik.',
                'is_active' => true,
            ],
        ];

        $facultyMap = [];
        foreach ($faculties as $fac) {
            $record = Faculty::updateOrCreate(['code' => $fac['code']], $fac);
            $facultyMap[$fac['code']] = $record->id;
        }

        // 3. Program Studi (12 Prodi umum di kampus)
        $programs = [
            ['code' => 'IF-01', 'name' => 'Teknik Informatika', 'level' => 'S1', 'faculty' => 'FTI', 'head_of_program' => 'Dr. Ahmad Yani, M.T.', 'accreditation' => 'Unggul'],
            ['code' => 'SI-02', 'name' => 'Sistem Informasi', 'level' => 'S1', 'faculty' => 'FTI', 'head_of_program' => 'Sri Wahyuni, S.Kom., M.Cs.', 'accreditation' => 'Unggul'],
            ['code' => 'MN-03', 'name' => 'Manajemen', 'level' => 'S1', 'faculty' => 'FEB', 'head_of_program' => 'Dr. Rudi Hartono, M.M.', 'accreditation' => 'Baik Sekali'],
            ['code' => 'AK-04', 'name' => 'Akuntansi', 'level' => 'S1', 'faculty' => 'FEB', 'head_of_program' => 'Dewi Sartika, S.E., M.Ak.', 'accreditation' => 'Unggul'],
            ['code' => 'IK-05', 'name' => 'Ilmu Komunikasi', 'level' => 'S1', 'faculty' => 'FISH', 'head_of_program' => 'Dian Lestari, M.Si.', 'accreditation' => 'Baik Sekali'],
            ['code' => 'RPL-06', 'name' => 'Rekayasa Perangkat Lunak', 'level' => 'S1', 'faculty' => 'FTI', 'head_of_program' => 'Dr. Sri Rahmawati, M.Kom.', 'accreditation' => 'Baik Sekali'],
            ['code' => 'BD-07', 'name' => 'Bisnis Digital', 'level' => 'S1', 'faculty' => 'FEB', 'head_of_program' => 'Fajar Pratama, S.E., M.B.A.', 'accreditation' => 'Baik'],
            ['code' => 'DKV-08', 'name' => 'Desain Komunikasi Visual', 'level' => 'S1', 'faculty' => 'FISH', 'head_of_program' => 'Rizky Ramadhan, S.Sn., M.Ds.', 'accreditation' => 'Baik Sekali'],
            ['code' => 'TS-09', 'name' => 'Teknik Sipil', 'level' => 'S1', 'faculty' => 'FT', 'head_of_program' => 'Ir. Eko Prasetyo, M.T.', 'accreditation' => 'Unggul'],
            ['code' => 'TE-10', 'name' => 'Teknik Elektro', 'level' => 'S1', 'faculty' => 'FT', 'head_of_program' => 'Dr. Hendro Gunawan, M.T.', 'accreditation' => 'Baik Sekali'],
            ['code' => 'IH-11', 'name' => 'Ilmu Hukum', 'level' => 'S1', 'faculty' => 'FHK', 'head_of_program' => 'Dr. Maya Puspita, S.H., M.H.', 'accreditation' => 'Unggul'],
            ['code' => 'MI-12', 'name' => 'Manajemen Informatika', 'level' => 'D3', 'faculty' => 'FTI', 'head_of_program' => 'Andi Wijaya, S.Kom., M.T.', 'accreditation' => 'Baik Sekali'],
        ];

        $programMap = [];
        foreach ($programs as $p) {
            $record = StudyProgram::updateOrCreate(
                ['code' => $p['code']],
                [
                    'faculty_id' => $facultyMap[$p['faculty']],
                    'education_level_id' => $levelMap[$p['level']],
                    'name' => $p['name'],
                    'head_of_program' => $p['head_of_program'],
                    'accreditation' => $p['accreditation'],
                    'is_active' => true,
                ]
            );
            $programMap[$p['code']] = $record->id;
        }

        // 4. Contoh Kurikulum & Mata Kuliah Awal
        $kurikulumIF = Curriculum::updateOrCreate(
            ['code' => 'KUR-IF-2025'],
            [
                'study_program_id' => $programMap['IF-01'],
                'name' => 'Kurikulum Merdeka Teknik Informatika 2025',
                'year_start' => 2025,
                'is_active' => true,
                'description' => 'Kurikulum berbasis Outcome-Based Education (OBE)',
            ]
        );

        $courses = [
            ['code' => 'IF101', 'name' => 'Pemrograman Web', 'prodi' => 'IF-01', 'curriculum_id' => $kurikulumIF->id, 'theory' => 2, 'practical' => 1, 'semester' => 3, 'type' => 'Wajib'],
            ['code' => 'SI201', 'name' => 'Basis Data', 'prodi' => 'SI-02', 'curriculum_id' => null, 'theory' => 2, 'practical' => 1, 'semester' => 2, 'type' => 'Wajib'],
            ['code' => 'IF301', 'name' => 'Kecerdasan Buatan', 'prodi' => 'IF-01', 'curriculum_id' => $kurikulumIF->id, 'theory' => 3, 'practical' => 0, 'semester' => 5, 'type' => 'Wajib'],
            ['code' => 'IF204', 'name' => 'Rekayasa Perangkat Lunak', 'prodi' => 'RPL-06', 'curriculum_id' => null, 'theory' => 3, 'practical' => 0, 'semester' => 4, 'type' => 'Wajib'],
            ['code' => 'UM105', 'name' => 'Etika Profesi', 'prodi' => 'SI-02', 'curriculum_id' => null, 'theory' => 2, 'practical' => 0, 'semester' => 6, 'type' => 'Wajib'],
        ];

        foreach ($courses as $c) {
            Course::updateOrCreate(
                ['code' => $c['code']],
                [
                    'name' => $c['name'],
                    'curriculum_id' => $c['curriculum_id'],
                    'study_program_id' => $programMap[$c['prodi']],
                    'credits_theory' => $c['theory'],
                    'credits_practical' => $c['practical'],
                    'total_credits' => $c['theory'] + $c['practical'],
                    'semester' => $c['semester'],
                    'course_type' => $c['type'],
                    'is_active' => true,
                ]
            );
        }
    }
}
