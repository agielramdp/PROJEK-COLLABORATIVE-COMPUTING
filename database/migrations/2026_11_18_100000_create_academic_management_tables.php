<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 0. Update users table if status column is missing
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('status', 30)->default('Aktif')->after('role');
            });
        }

        // 1. Roles Table
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->text('description')->nullable();
                $table->boolean('is_system')->default(false);
                $table->timestamps();
            });
        }

        // 2. Permissions Table
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('slug', 150)->unique();
                $table->string('group', 100)->default('general');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 3. Role Has Permissions Table
        if (!Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');
                $table->primary(['role_id', 'permission_id']);
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            });
        }

        // 4. User Has Roles Table
        if (!Schema::hasTable('user_has_roles')) {
            Schema::create('user_has_roles', function (Blueprint $table) {
                $table->unsignedInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->primary(['user_id', 'role_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }

        // 5. Faculties (Fakultas) Table
        if (!Schema::hasTable('faculties')) {
            Schema::create('faculties', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique();
                $table->string('name', 150);
                $table->string('dean_name', 150)->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 6. Education Levels (Jenjang Pendidikan) Table
        if (!Schema::hasTable('education_levels')) {
            Schema::create('education_levels', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->unique(); // D3, D4, S1, S2, S3
                $table->string('name', 100);          // Sarjana (S1)
                $table->string('degree_title', 50)->nullable(); // S.Kom.
                $table->integer('standard_credits')->default(144);
                $table->integer('standard_semesters')->default(8);
                $table->timestamps();
            });
        }

        // 7. Study Programs (Program Studi) Table
        if (!Schema::hasTable('study_programs')) {
            Schema::create('study_programs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('faculty_id');
                $table->unsignedBigInteger('education_level_id');
                $table->string('code', 30)->unique();
                $table->string('name', 150);
                $table->string('head_of_program', 150)->nullable();
                $table->string('accreditation', 30)->default('Baik');
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade');
                $table->foreign('education_level_id')->references('id')->on('education_levels')->onDelete('cascade');
            });
        }

        // 8. Academic Years (Tahun Akademik & Semester) Table
        if (!Schema::hasTable('academic_years')) {
            Schema::create('academic_years', function (Blueprint $table) {
                $table->id();
                $table->string('year_name', 30); // 2026/2027
                $table->enum('semester', ['Ganjil', 'Genap', 'Antara'])->default('Ganjil');
                $table->boolean('is_active')->default(false);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 9. Buildings (Gedung) Table
        if (!Schema::hasTable('buildings')) {
            Schema::create('buildings', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique();
                $table->string('name', 150);
                $table->integer('total_floors')->default(1);
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 10. Rooms (Ruangan & Lantai) Table
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('building_id');
                $table->string('code', 50)->unique();
                $table->string('name', 150);
                $table->integer('floor')->default(1);
                $table->integer('capacity')->default(40);
                $table->enum('room_type', ['Teori', 'Laboratorium', 'Auditorium', 'Seminar', 'Lainnya'])->default('Teori');
                $table->text('facilities')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            });
        }

        // 11. KRS Periods (Periode KRS) Table
        if (!Schema::hasTable('krs_periods')) {
            Schema::create('krs_periods', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('academic_year_id');
                $table->unsignedBigInteger('study_program_id')->nullable();
                $table->string('name', 150);
                $table->dateTime('start_date');
                $table->dateTime('end_date');
                $table->boolean('is_open')->default(false);
                $table->boolean('auto_by_date')->default(true);
                $table->boolean('allow_revision')->default(false);
                $table->dateTime('revision_end_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
                $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('set null');
            });
        }

        // 12. Audit Logs Table
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable();
                $table->string('user_name', 150)->nullable();
                $table->string('user_role', 50)->nullable();
                $table->string('action', 100);
                $table->string('module', 100);
                $table->string('record_id', 100)->nullable();
                $table->json('details')->nullable();
                $table->string('ip_address', 50)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->index(['action', 'module']);
                $table->index('user_id');
            });
        }

        // 13. Curriculums (Kurikulum) Table
        if (!Schema::hasTable('curriculums')) {
            Schema::create('curriculums', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('study_program_id');
                $table->string('code', 50);
                $table->string('name', 150);
                $table->integer('year_start')->default(2024);
                $table->boolean('is_active')->default(true);
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('cascade');
            });
        }

        // 14. Courses (Mata Kuliah) Table
        if (!Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('curriculum_id')->nullable();
                $table->unsignedBigInteger('study_program_id')->nullable();
                $table->string('code', 30);
                $table->string('name', 150);
                $table->string('name_en', 150)->nullable();
                $table->integer('credits_theory')->default(2);
                $table->integer('credits_practical')->default(0);
                $table->integer('total_credits')->default(2);
                $table->integer('semester')->default(1);
                $table->enum('course_type', ['Wajib', 'Pilihan', 'Wajib Peminatan'])->default('Wajib');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('set null');
                $table->foreign('study_program_id')->references('id')->on('study_programs')->onDelete('set null');
            });
        }

        // 15. Course Prerequisites Table
        if (!Schema::hasTable('course_prerequisites')) {
            Schema::create('course_prerequisites', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->unsignedBigInteger('prerequisite_course_id');
                $table->string('minimum_grade', 5)->default('D');
                $table->enum('prerequisite_type', ['must_pass', 'must_take'])->default('must_pass');
                $table->string('notes')->nullable();
                $table->timestamps();

                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                $table->foreign('prerequisite_course_id')->references('id')->on('courses')->onDelete('cascade');
            });
        }

        // 16. Course Classes (Kelas Mata Kuliah) Table
        if (!Schema::hasTable('course_classes')) {
            Schema::create('course_classes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_id');
                $table->unsignedBigInteger('academic_year_id');
                $table->string('name', 50); // Kelas A, Kelas B
                $table->integer('quota')->default(40);
                $table->integer('current_enrolled')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            });
        }

        // 17. Class Lecturers (Dosen Pengampu Kelas) Table
        if (!Schema::hasTable('class_lecturers')) {
            Schema::create('class_lecturers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_class_id');
                $table->unsignedInteger('lecturer_id');
                $table->enum('role', ['Koordinator', 'Anggota', 'Praktika'])->default('Koordinator');
                $table->integer('teaching_load_sks')->default(3);
                $table->timestamps();

                $table->foreign('course_class_id')->references('id')->on('course_classes')->onDelete('cascade');
                $table->foreign('lecturer_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 18. Academic Advisors (Dosen Pembimbing Akademik / Wali) Table
        if (!Schema::hasTable('academic_advisors')) {
            Schema::create('academic_advisors', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('lecturer_id');
                $table->unsignedInteger('student_id');
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->date('assigned_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->string('notes')->nullable();
                $table->timestamps();

                $table->foreign('lecturer_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('set null');
            });
        }

        // 19. Class Schedules (Jadwal Perkuliahan) Table
        if (!Schema::hasTable('class_schedules')) {
            Schema::create('class_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_class_id');
                $table->unsignedBigInteger('room_id');
                $table->enum('day_of_week', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
                $table->time('start_time');
                $table->time('end_time');
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('course_class_id')->references('id')->on('course_classes')->onDelete('cascade');
                $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('set null');
            });
        }

        // 20. Student Statuses (Status Kemahasiswaan & Riwayat) Table
        if (!Schema::hasTable('student_statuses')) {
            Schema::create('student_statuses', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('student_id');
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->enum('status', ['Aktif', 'Cuti', 'Drop Out', 'Lulus', 'Non-Aktif', 'Mangkir', 'Meninggal'])->default('Aktif');
                $table->text('reason')->nullable();
                $table->string('decree_number', 100)->nullable();
                $table->unsignedInteger('updated_by_user_id')->nullable();
                $table->date('effective_date')->nullable();
                $table->timestamps();

                $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('set null');
            });
        }

        // 21. Exam Schedules (Jadwal UTS & UAS) Table
        if (!Schema::hasTable('exam_schedules')) {
            Schema::create('exam_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_class_id');
                $table->enum('exam_type', ['UTS', 'UAS']);
                $table->date('exam_date');
                $table->time('start_time');
                $table->time('end_time');
                $table->unsignedBigInteger('room_id');
                $table->unsignedBigInteger('academic_year_id');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('course_class_id')->references('id')->on('course_classes')->onDelete('cascade');
                $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            });
        }

        // 22. Exam Invigilators (Pengawas Ujian) Table
        if (!Schema::hasTable('exam_invigilators')) {
            Schema::create('exam_invigilators', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('exam_schedule_id');
                $table->unsignedInteger('invigilator_id');
                $table->enum('role', ['Pengawas 1', 'Pengawas 2', 'Cadangan'])->default('Pengawas 1');
                $table->enum('attendance_status', ['Hadir', 'Izin', 'Alpa'])->default('Hadir');
                $table->string('notes')->nullable();
                $table->timestamps();

                $table->foreign('exam_schedule_id')->references('id')->on('exam_schedules')->onDelete('cascade');
                $table->foreign('invigilator_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 23. Academic Settings (Parameter Global SIAKAD) Table
        if (!Schema::hasTable('academic_settings')) {
            Schema::create('academic_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->text('value');
                $table->string('data_type', 30)->default('string'); // number, string, boolean, json
                $table->string('group', 50)->default('academic');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 24. Grade Submission Deadlines Table
        if (!Schema::hasTable('grade_submission_deadlines')) {
            Schema::create('grade_submission_deadlines', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('academic_year_id');
                $table->enum('grade_component', ['Tugas', 'Kuis', 'UTS', 'UAS', 'Nilai Akhir'])->default('Nilai Akhir');
                $table->dateTime('start_date');
                $table->dateTime('end_date');
                $table->boolean('is_active')->default(true);
                $table->string('notes')->nullable();
                $table->timestamps();

                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
            });
        }

        // 25. Grade Locks (Pembukaan Kembali Penguncian Nilai) Table
        if (!Schema::hasTable('grade_locks')) {
            Schema::create('grade_locks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('course_class_id')->unique();
                $table->boolean('is_locked')->default(true);
                $table->dateTime('locked_at')->nullable();
                $table->unsignedInteger('unlocked_by_user_id')->nullable();
                $table->dateTime('unlocked_at')->nullable();
                $table->dateTime('unlock_until')->nullable();
                $table->text('unlock_reason')->nullable();
                $table->timestamps();

                $table->foreign('course_class_id')->references('id')->on('course_classes')->onDelete('cascade');
            });
        }

        // 26. Academic Calendar Events (Tanggal Penting Akademik) Table
        if (!Schema::hasTable('academic_calendar_events')) {
            Schema::create('academic_calendar_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('academic_year_id')->nullable();
                $table->string('title', 200);
                $table->enum('event_type', ['perkuliahan', 'uts', 'uas', 'input_nilai', 'krs', 'registrasi', 'libur', 'wisuda', 'lainnya'])->default('perkuliahan');
                $table->date('start_date');
                $table->date('end_date');
                $table->text('description')->nullable();
                $table->boolean('is_public')->default(true);
                $table->timestamps();

                $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('set null');
            });
        }

        // 27. Announcements (Pengumuman Dashboard) Table
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title', 200);
                $table->string('slug', 255)->nullable();
                $table->longText('content');
                $table->enum('target_role', ['Semua', 'Mahasiswa', 'Dosen Pengajar', 'Dosen Wali', 'Bendahara', 'Admin'])->default('Semua');
                $table->enum('priority', ['Biasa', 'Penting', 'Mendesak'])->default('Biasa');
                $table->unsignedInteger('author_id')->nullable();
                $table->boolean('is_published')->default(true);
                $table->dateTime('published_at')->nullable();
                $table->dateTime('expires_at')->nullable();
                $table->string('attachment_path')->nullable();
                $table->timestamps();

                $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 28. Seed Default Data (Roles, Permissions, Global Settings, Academic Year)
        $this->seedInitialData();
    }

    /**
     * Seed initial configuration and foundational data.
     */
    protected function seedInitialData(): void
    {
        // Default Roles
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator Sistem Akademik', 'is_system' => true],
            ['name' => 'Dosen Pengajar', 'slug' => 'dosen_pengajar', 'description' => 'Dosen Pengampu Mata Kuliah', 'is_system' => true],
            ['name' => 'Dosen Wali', 'slug' => 'dosen_wali', 'description' => 'Dosen Pembimbing Akademik Mahasiswa', 'is_system' => true],
            ['name' => 'Bendahara', 'slug' => 'bendahara', 'description' => 'Pengelola Keuangan dan Tagihan', 'is_system' => true],
            ['name' => 'Mahasiswa', 'slug' => 'mahasiswa', 'description' => 'Mahasiswa Terdaftar', 'is_system' => true],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $r['slug']],
                array_merge($r, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Default Permissions
        $permissions = [
            // User Management
            ['name' => 'Lihat Pengguna', 'slug' => 'user.view', 'group' => 'Pengguna'],
            ['name' => 'Tambah Pengguna', 'slug' => 'user.create', 'group' => 'Pengguna'],
            ['name' => 'Ubah Pengguna', 'slug' => 'user.edit', 'group' => 'Pengguna'],
            ['name' => 'Hapus Pengguna', 'slug' => 'user.delete', 'group' => 'Pengguna'],
            ['name' => 'Reset Password Pengguna', 'slug' => 'user.reset_password', 'group' => 'Pengguna'],
            ['name' => 'Import Export Pengguna', 'slug' => 'user.import_export', 'group' => 'Pengguna'],

            // Roles & Permissions
            ['name' => 'Kelola Role & Hak Akses', 'slug' => 'role.manage', 'group' => 'Role & Izin'],

            // Academic Structure
            ['name' => 'Kelola Fakultas & Prodi', 'slug' => 'academic.structure', 'group' => 'Struktur Akademik'],
            ['name' => 'Kelola Kurikulum & MK', 'slug' => 'academic.curriculum', 'group' => 'Kurikulum'],
            ['name' => 'Kelola Gedung & Ruangan', 'slug' => 'facility.manage', 'group' => 'Fasilitas'],

            // Schedule & KRS
            ['name' => 'Kelola Jadwal Kuliah', 'slug' => 'schedule.manage', 'group' => 'Jadwal'],
            ['name' => 'Kelola Periode KRS', 'slug' => 'krs.manage', 'group' => 'KRS'],
            ['name' => 'Kelola Jadwal Ujian', 'slug' => 'exam.manage', 'group' => 'Ujian'],
            ['name' => 'Kelola Pengawas Ujian', 'slug' => 'invigilator.manage', 'group' => 'Ujian'],

            // Grades & Settings
            ['name' => 'Buka Kunci Nilai', 'slug' => 'grade.unlock', 'group' => 'Nilai'],
            ['name' => 'Kelola Parameter Global', 'slug' => 'setting.manage', 'group' => 'Pengaturan'],
            ['name' => 'Unduh Laporan Akademik', 'slug' => 'report.download', 'group' => 'Laporan'],
            ['name' => 'Kelola Pengumuman', 'slug' => 'announcement.manage', 'group' => 'Pengumuman'],
            ['name' => 'Lihat Audit Log', 'slug' => 'audit.view', 'group' => 'Audit'],
        ];

        foreach ($permissions as $p) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $p['slug']],
                array_merge($p, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Assign All Permissions to Admin Role
        $adminRole = DB::table('roles')->where('slug', 'admin')->first();
        if ($adminRole) {
            $allPermIds = DB::table('permissions')->pluck('id');
            foreach ($allPermIds as $pId) {
                DB::table('role_has_permissions')->updateOrInsert([
                    'role_id' => $adminRole->id,
                    'permission_id' => $pId,
                ]);
            }
        }

        // Global Academic Settings (Item 22)
        $defaultSettings = [
            [
                'key' => 'passing_grade',
                'value' => '55.0',
                'data_type' => 'number',
                'group' => 'grading',
                'description' => 'Nilai minimal kelulusan mata kuliah (Passing Grade)'
            ],
            [
                'key' => 'minimum_attendance_percentage',
                'value' => '75',
                'data_type' => 'number',
                'group' => 'attendance',
                'description' => 'Persentase minimal kehadiran mahasiswa untuk dapat mengikuti ujian (%)'
            ],
            [
                'key' => 'max_sks_default',
                'value' => '24',
                'data_type' => 'number',
                'group' => 'krs',
                'description' => 'Batas maksimal pengambilan SKS default per semester'
            ],
            [
                'key' => 'max_file_upload_size_mb',
                'value' => '5',
                'data_type' => 'number',
                'group' => 'upload',
                'description' => 'Ukuran maksimal file unggahan umum pengguna (MB)'
            ],
            [
                'key' => 'allowed_file_types',
                'value' => 'pdf,jpg,jpeg,png,docx,xlsx,csv',
                'data_type' => 'string',
                'group' => 'upload',
                'description' => 'Daftar ekstensi file yang diizinkan untuk diunggah pengguna'
            ],
            [
                'key' => 'institution_name',
                'value' => 'Universitas Sistem Informasi Akademik',
                'data_type' => 'string',
                'group' => 'general',
                'description' => 'Nama institusi perguruan tinggi'
            ],
        ];

        foreach ($defaultSettings as $s) {
            DB::table('academic_settings')->updateOrInsert(
                ['key' => $s['key']],
                array_merge($s, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Default Active Academic Year (Item 4)
        if (DB::table('academic_years')->count() === 0) {
            DB::table('academic_years')->insert([
                'year_name' => '2026/2027',
                'semester' => 'Ganjil',
                'is_active' => true,
                'start_date' => '2026-09-01',
                'end_date' => '2027-01-31',
                'description' => 'Tahun Akademik 2026/2027 Semester Ganjil',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('academic_calendar_events');
        Schema::dropIfExists('grade_locks');
        Schema::dropIfExists('grade_submission_deadlines');
        Schema::dropIfExists('academic_settings');
        Schema::dropIfExists('exam_invigilators');
        Schema::dropIfExists('exam_schedules');
        Schema::dropIfExists('student_statuses');
        Schema::dropIfExists('class_schedules');
        Schema::dropIfExists('academic_advisors');
        Schema::dropIfExists('class_lecturers');
        Schema::dropIfExists('course_classes');
        Schema::dropIfExists('course_prerequisites');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('curriculums');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('krs_periods');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('academic_years');
        Schema::dropIfExists('study_programs');
        Schema::dropIfExists('education_levels');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('user_has_roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
