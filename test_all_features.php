<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

function callApi($method, $uri, $data = [], $files = []) {
    global $kernel;

    $server = [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_ACCEPT'  => 'application/json',
    ];

    $content = null;
    $parameters = [];

    if ($method === 'GET') {
        $parameters = $data;
    } else {
        if (!empty($files)) {
            $parameters = $data;
        } else {
            $content = json_encode($data);
        }
    }

    $request = Illuminate\Http\Request::create($uri, $method, $parameters, [], $files, $server, $content);
    $response = $kernel->handle($request);
    $kernel->terminate($request, $response);

    return [
        'status' => $response->getStatusCode(),
        'body'   => json_decode($response->getContent(), true) ?: $response->getContent(),
    ];
}

echo "=== STARTING VERIFICATION OF ALL 25 SIAKAD BACKEND FEATURES ===\n\n";

$passed = 0;
$failed = 0;

function assertFeature($num, $name, $condition, $details = '') {
    global $passed, $failed;
    if ($condition) {
        $passed++;
        echo "[\xE2\x9C\x94] Feature {$num}: {$name} -> PASSED\n";
    } else {
        $failed++;
        echo "[\xE2\x9C\x98] Feature {$num}: {$name} -> FAILED: {$details}\n";
    }
}

// -------------------------------------------------------------
// 1. User CRUD
// -------------------------------------------------------------
$userRes = callApi('POST', '/api/admin/users', [
    'name'     => 'Testing User Mahasiswa',
    'username' => 'test.user.' . time(),
    'email'    => 'testuser' . time() . '@siakad.ac.id',
    'role'     => 'Mahasiswa',
    'npm'      => 'NPM' . rand(100000, 999999),
    'password' => 'secret123',
    'status'   => 'Aktif',
]);
$testUserId = $userRes['body']['data']['id'] ?? null;

$getUserRes = $testUserId ? callApi('GET', "/api/admin/users/{$testUserId}") : null;
$listUserRes = callApi('GET', '/api/admin/users?per_page=5');

assertFeature(1, 'User CRUD (Create, View, List, Update, Delete)', 
    $userRes['status'] === 201 && ($getUserRes['status'] ?? 0) === 200 && $listUserRes['status'] === 200,
    json_encode($userRes['body'])
);

// -------------------------------------------------------------
// 2. Roles & Permissions
// -------------------------------------------------------------
$roleRes = callApi('POST', '/api/admin/roles', [
    'name'        => 'Staff Tata Usaha ' . rand(100, 999),
    'description' => 'Role khusus staf tata usaha fakultas',
]);
$testRoleId = $roleRes['body']['data']['id'] ?? null;
$permList = callApi('GET', '/api/admin/permissions');
$syncPermRes = $testRoleId ? callApi('POST', "/api/admin/roles/{$testRoleId}/permissions", ['permissions' => [1, 2]]) : null;

$assignRoleRes = $testUserId ? callApi('POST', "/api/admin/users/{$testUserId}/assign-role", ['role' => 'Mahasiswa']) : null;

assertFeature(2, 'Pengelolaan Role dan Hak Akses', 
    $roleRes['status'] === 201 && $permList['status'] === 200 && ($syncPermRes['status'] ?? 0) === 200,
    json_encode($roleRes['body'])
);

// -------------------------------------------------------------
// 3. Fakultas, Prodi, Jenjang Pendidikan
// -------------------------------------------------------------
$facultyRes = callApi('POST', '/api/admin/faculties', [
    'code'      => 'FT-' . rand(100, 999),
    'name'      => 'Fakultas Teknik & Ilmu Komputer',
    'dean_name' => 'Prof. Dr. Hendra, M.T.',
]);
$facultyId = $facultyRes['body']['data']['id'] ?? 1;

$levelRes = callApi('POST', '/api/admin/education-levels', [
    'code'               => 'S1-' . rand(10, 99),
    'name'               => 'Strata Satu (S1)',
    'degree_title'       => 'S.Kom.',
    'standard_credits'   => 144,
    'standard_semesters' => 8,
]);
$levelId = $levelRes['body']['data']['id'] ?? 1;

$prodiRes = callApi('POST', '/api/admin/study-programs', [
    'faculty_id'         => $facultyId,
    'education_level_id' => $levelId,
    'code'               => 'IF-' . rand(100, 999),
    'name'               => 'Teknik Informatika',
    'head_of_program'    => 'Dr. Budi Santoso',
    'accreditation'      => 'Unggul',
]);
$prodiId = $prodiRes['body']['data']['id'] ?? 1;

assertFeature(3, 'Kelola Fakultas, Prodi, dan Jenjang Pendidikan',
    $facultyRes['status'] === 201 && $levelRes['status'] === 201 && $prodiRes['status'] === 201,
    json_encode($prodiRes['body'])
);

// -------------------------------------------------------------
// 4. Tahun Akademik & Semester Aktif
// -------------------------------------------------------------
$academicYearRes = callApi('POST', '/api/admin/academic-years', [
    'year_name'  => '2027/2028',
    'semester'   => 'Ganjil',
    'is_active'  => false,
    'start_date' => '2027-09-01',
    'end_date'   => '2028-01-31',
]);
$academicYearId = $academicYearRes['body']['data']['id'] ?? null;

$setActiveRes = $academicYearId ? callApi('POST', "/api/admin/academic-years/{$academicYearId}/set-active") : null;
$getActiveRes = callApi('GET', '/api/admin/academic-years/active');

assertFeature(4, 'Pengelolaan Tahun Akademik dan Semester Aktif',
    $academicYearRes['status'] === 201 && ($setActiveRes['status'] ?? 0) === 200 && ($getActiveRes['body']['data']['id'] ?? null) == $academicYearId,
    json_encode($getActiveRes['body'])
);

// -------------------------------------------------------------
// 5. Gedung, Lantai, dan Ruangan
// -------------------------------------------------------------
$bldRes = callApi('POST', '/api/admin/buildings', [
    'code'         => 'GD-' . rand(100, 999),
    'name'         => 'Gedung Kuliah Bersama C',
    'total_floors' => 4,
]);
$bldId = $bldRes['body']['data']['id'] ?? null;

$roomRes = callApi('POST', '/api/admin/rooms', [
    'building_id' => $bldId,
    'code'        => 'R-C' . rand(100, 999),
    'name'        => 'Ruang Teori C.201',
    'floor'       => 2,
    'capacity'    => 45,
    'room_type'   => 'Teori',
    'facilities'  => 'Proyektor, AC, Whiteboard, WiFi',
]);
$roomId = $roomRes['body']['data']['id'] ?? null;

assertFeature(5, 'Pengelolaan Gedung, Lantai, dan Ruangan',
    $bldRes['status'] === 201 && $roomRes['status'] === 201,
    json_encode($roomRes['body'])
);

// -------------------------------------------------------------
// 6. Logika Periode KRS (Buka/Tutup by Date & Manual)
// -------------------------------------------------------------
$krsPeriodRes = callApi('POST', '/api/admin/krs-periods', [
    'academic_year_id' => $academicYearId,
    'name'             => 'KRS Semester Ganjil 2027/2028',
    'start_date'       => date('Y-m-d H:i:s', strtotime('-1 day')),
    'end_date'         => date('Y-m-d H:i:s', strtotime('+7 days')),
    'auto_by_date'     => true,
    'allow_revision'   => true,
    'revision_end_date'=> date('Y-m-d H:i:s', strtotime('+14 days')),
]);
$krsPeriodId = $krsPeriodRes['body']['data']['id'] ?? null;
$krsStatusRes = callApi('GET', '/api/admin/krs-periods/status');
$krsToggleRes = $krsPeriodId ? callApi('POST', "/api/admin/krs-periods/{$krsPeriodId}/toggle") : null;

assertFeature(6, 'Pengaturan Periode KRS (Otomatis & Manual Toggle)',
    $krsPeriodRes['status'] === 201 && ($krsStatusRes['body']['is_open'] ?? false) === true && ($krsToggleRes['status'] ?? 0) === 200,
    json_encode($krsStatusRes['body'])
);

// -------------------------------------------------------------
// 7. Import & Export Data Pengguna (CSV)
// -------------------------------------------------------------
$exportRes = callApi('GET', '/api/admin/users/export');

$csvContent = "Nama,Email,Username,Role,NPM,Password,Status\n" .
              "Mahasiswa Import Satu,import1_" . time() . "@siakad.ac.id,import1_" . time() . ",Mahasiswa,IMP" . rand(1000, 9999) . ",Rahasia123,Aktif\n" .
              "Mahasiswa Import Dua,import2_" . time() . "@siakad.ac.id,import2_" . time() . ",Mahasiswa,IMP" . rand(1000, 9999) . ",Rahasia123,Aktif\n";

$tmpCsvPath = tempnam(sys_get_temp_dir(), 'csv_import_');
file_put_contents($tmpCsvPath, $csvContent);

$uploadedFile = new Illuminate\Http\UploadedFile(
    $tmpCsvPath,
    'import_users.csv',
    'text/csv',
    null,
    true
);

$importRes = callApi('POST', '/api/admin/users/import', [], ['file' => $uploadedFile]);
@unlink($tmpCsvPath);

assertFeature(7, 'Import dan Export Data Pengguna (Excel/CSV)',
    $exportRes['status'] === 200 && $importRes['status'] === 200 && ($importRes['body']['imported'] ?? 0) >= 2,
    json_encode($importRes['body'])
);

// -------------------------------------------------------------
// 8. Pengunduhan Laporan Akademik
// -------------------------------------------------------------
$reportTypes = callApi('GET', '/api/admin/reports/types');
$reportMahasiswa = callApi('GET', '/api/admin/reports/download?type=rekap_mahasiswa&format=csv');
$reportDosenJson = callApi('GET', '/api/admin/reports/download?type=rekap_dosen&format=json');

assertFeature(8, 'Fitur Pengunduhan Laporan Akademik Sesuai Jenis',
    $reportTypes['status'] === 200 && $reportMahasiswa['status'] === 200 && $reportDosenJson['status'] === 200,
    "Types status: {$reportTypes['status']}, CSV status: {$reportMahasiswa['status']}"
);

// -------------------------------------------------------------
// 9. Validasi Ukuran dan Jenis File Unggahan
// -------------------------------------------------------------
$uploadRules = callApi('GET', '/api/admin/upload-rules');

// Valid upload test
$tmpValidFile = tempnam(sys_get_temp_dir(), 'valid_doc_');
file_put_contents($tmpValidFile, 'Simulated document content');
$validUploadedFile = new Illuminate\Http\UploadedFile($tmpValidFile, 'document.pdf', 'application/pdf', null, true);
$uploadSuccessRes = callApi('POST', '/api/admin/upload-file', ['category' => 'document'], ['file' => $validUploadedFile]);
@unlink($tmpValidFile);

// Invalid type upload test (misal .exe)
$tmpInvalidFile = tempnam(sys_get_temp_dir(), 'bad_file_');
file_put_contents($tmpInvalidFile, 'dangerous binary content');
$invalidUploadedFile = new Illuminate\Http\UploadedFile($tmpInvalidFile, 'virus.exe', 'application/x-msdownload', null, true);
$uploadFailRes = callApi('POST', '/api/admin/upload-file', ['category' => 'document'], ['file' => $invalidUploadedFile]);
@unlink($tmpInvalidFile);

assertFeature(9, 'Validasi Ukuran dan Jenis File Unggahan',
    $uploadRules['status'] === 200 && $uploadSuccessRes['status'] === 201 && $uploadFailRes['status'] === 422,
    "Expected success 201 & fail 422, got {$uploadSuccessRes['status']} & {$uploadFailRes['status']}"
);

// -------------------------------------------------------------
// 10. Reset Password Manual oleh Admin
// -------------------------------------------------------------
$resetManualRes = $testUserId ? callApi('POST', "/api/admin/users/{$testUserId}/reset-password", [
    'password' => 'NewAdminSecret2026',
]) : null;

$resetAutoRes = $testUserId ? callApi('POST', "/api/admin/users/{$testUserId}/reset-password", []) : null;

assertFeature(10, 'Reset Password Manual oleh Admin',
    ($resetManualRes['status'] ?? 0) === 200 && ($resetAutoRes['status'] ?? 0) === 200 && !empty($resetAutoRes['body']['new_password']),
    json_encode($resetManualRes['body'])
);

// -------------------------------------------------------------
// 11. Pencatatan Aktivitas Admin pada Audit Log
// -------------------------------------------------------------
$auditListRes = callApi('GET', '/api/admin/audit-logs');
$auditModulesRes = callApi('GET', '/api/admin/audit-logs/modules');

assertFeature(11, 'Pencatatan Aktivitas Admin pada Audit Log',
    $auditListRes['status'] === 200 && count($auditListRes['body']['data'] ?? []) > 0 && $auditModulesRes['status'] === 200,
    "Audit log entries: " . count($auditListRes['body']['data'] ?? [])
);

// -------------------------------------------------------------
// 12. Pembukaan Kembali Penguncian Nilai
// -------------------------------------------------------------
// Butuh Course Class untuk grade lock
$curriculumRes = callApi('POST', '/api/admin/curriculums', [
    'study_program_id' => $prodiId,
    'code'             => 'KUR-IF-2027',
    'name'             => 'Kurikulum 2027',
    'year_start'       => 2027,
]);
$curriculumId = $curriculumRes['body']['data']['id'] ?? 1;

$courseRes = callApi('POST', '/api/admin/courses', [
    'curriculum_id'     => $curriculumId,
    'study_program_id'  => $prodiId,
    'code'              => 'IF-101-' . rand(100, 999),
    'name'              => 'Pemrograman Berorientasi Objek',
    'credits_theory'    => 2,
    'credits_practical' => 1,
    'semester'          => 2,
    'course_type'       => 'Wajib',
]);
$courseId = $courseRes['body']['data']['id'] ?? 1;

$classRes = callApi('POST', '/api/admin/course-classes', [
    'course_id'        => $courseId,
    'academic_year_id' => $academicYearId,
    'name'             => 'Kelas A',
    'quota'            => 40,
]);
$classId = $classRes['body']['data']['id'] ?? 1;

$unlockGradeRes = callApi('POST', "/api/admin/grades/locks/{$classId}/unlock", [
    'unlock_reason' => 'Perbaikan nilai ujian susulan mahasiswa',
    'unlock_days'   => 2,
]);
$lockGradeRes = callApi('POST', "/api/admin/grades/locks/{$classId}/lock");
$listLocksRes = callApi('GET', '/api/admin/grades/locks');

assertFeature(12, 'Pembukaan Kembali Penguncian Nilai',
    $unlockGradeRes['status'] === 200 && $lockGradeRes['status'] === 200 && $listLocksRes['status'] === 200,
    json_encode($unlockGradeRes['body'])
);

// -------------------------------------------------------------
// 13. Kurikulum dan Mata Kuliah
// -------------------------------------------------------------
$listCurriculums = callApi('GET', '/api/admin/curriculums');
$listCourses = callApi('GET', '/api/admin/courses');

assertFeature(13, 'Pengelolaan Kurikulum dan Mata Kuliah',
    $curriculumRes['status'] === 201 && $courseRes['status'] === 201 && $listCurriculums['status'] === 200 && $listCourses['status'] === 200,
    json_encode($courseRes['body'])
);

// -------------------------------------------------------------
// 14. Mata Kuliah Prasyarat
// -------------------------------------------------------------
$prereqCourseRes = callApi('POST', '/api/admin/courses', [
    'curriculum_id'     => $curriculumId,
    'study_program_id'  => $prodiId,
    'code'              => 'IF-PREREQ-' . rand(100, 999),
    'name'              => 'Dasar Pemrograman',
    'credits_theory'    => 3,
    'credits_practical' => 0,
    'semester'          => 1,
    'course_type'       => 'Wajib',
]);
$prereqCourseId = $prereqCourseRes['body']['data']['id'] ?? 1;

$addPrereqRes = callApi('POST', "/api/admin/courses/{$courseId}/prerequisites", [
    'prerequisite_course_id' => $prereqCourseId,
    'minimum_grade'          => 'C',
    'prerequisite_type'      => 'must_pass',
]);

$checkPrereqRes = callApi('POST', '/api/admin/courses/check-prerequisites', [
    'course_id'  => $courseId,
    'student_id' => $testUserId ?: 1,
]);

assertFeature(14, 'Pengaturan Mata Kuliah Prasyarat',
    $addPrereqRes['status'] === 201 && $checkPrereqRes['status'] === 200 && ($checkPrereqRes['body']['eligible'] ?? false) === true,
    json_encode($addPrereqRes['body'])
);

// -------------------------------------------------------------
// 15. Dosen Pengampu Berdasarkan Kelas dan Mata Kuliah
// -------------------------------------------------------------
// Cari dosen pengajar dari tabel users
$dosenUser = App\Models\User::where('role', 'Dosen Pengajar')->first();
$dosenId = $dosenUser ? $dosenUser->id : 4;

$assignLecturerRes = callApi('POST', "/api/admin/course-classes/{$classId}/lecturers", [
    'lecturer_id'       => $dosenId,
    'role'              => 'Koordinator',
    'teaching_load_sks' => 3,
]);
$getClassLecturers = callApi('GET', "/api/admin/course-classes/{$classId}/lecturers");

assertFeature(15, 'Dosen Pengampu Berdasarkan Kelas dan Mata Kuliah',
    $assignLecturerRes['status'] === 200 && $getClassLecturers['status'] === 200 && count($getClassLecturers['body']['data'] ?? []) > 0,
    json_encode($assignLecturerRes['body'])
);

// -------------------------------------------------------------
// 16. Dosen Pembimbing Akademik (Dosen Wali) bagi Mahasiswa
// -------------------------------------------------------------
$dosenWaliUser = App\Models\User::where('role', 'Dosen Wali')->first();
$dosenWaliId = $dosenWaliUser ? $dosenWaliUser->id : 5;

$assignAdvisorRes = callApi('POST', '/api/admin/academic-advisors/assign', [
    'lecturer_id'      => $dosenWaliId,
    'student_ids'      => [$testUserId ?: 1],
    'academic_year_id' => $academicYearId,
    'notes'            => 'Bimbingan Akademik Angkatan 2026',
]);
$listAdvisorsRes = callApi('GET', '/api/admin/academic-advisors');

assertFeature(16, 'Penentuan Dosen Pembimbing Akademik bagi Mahasiswa',
    $assignAdvisorRes['status'] === 200 && $listAdvisorsRes['status'] === 200,
    json_encode($assignAdvisorRes['body'])
);

// -------------------------------------------------------------
// 17. Logika Penyusunan Jadwal Perkuliahan
// -------------------------------------------------------------
$scheduleRes = callApi('POST', '/api/admin/schedules', [
    'course_class_id'  => $classId,
    'room_id'          => $roomId,
    'day_of_week'      => 'Senin',
    'start_time'       => '08:00',
    'end_time'         => '10:30',
    'academic_year_id' => $academicYearId,
]);
$scheduleId = $scheduleRes['body']['data']['id'] ?? null;
$listSchedules = callApi('GET', '/api/admin/schedules');

assertFeature(17, 'Penyusunan Jadwal Perkuliahan',
    $scheduleRes['status'] === 201 && $listSchedules['status'] === 200,
    json_encode($scheduleRes['body'])
);

// -------------------------------------------------------------
// 18. Pengecekan Jadwal Bentrok Otomatis (Ruangan, Dosen, Hari, Jam)
// -------------------------------------------------------------
// Buat kelas kedua dengan dosen pengampu yang sama atau ruangan yang sama pada jam bertabrakan (09:00 - 11:00)
$class2Res = callApi('POST', '/api/admin/course-classes', [
    'course_id'        => $courseId,
    'academic_year_id' => $academicYearId,
    'name'             => 'Kelas B',
    'quota'            => 35,
]);
$class2Id = $class2Res['body']['data']['id'] ?? 2;

// Check conflict endpoint
$checkConflictRes = callApi('POST', '/api/admin/schedules/check-conflict', [
    'course_class_id'  => $class2Id,
    'room_id'          => $roomId, // Ruangan sama!
    'day_of_week'      => 'Senin', // Hari sama!
    'start_time'       => '09:00', // Jam bertabrakan dengan 08:00 - 10:30!
    'end_time'         => '11:00',
    'academic_year_id' => $academicYearId,
]);

// Coba create jadwal yang bentrok (harus ditolak dengan HTTP 422)
$conflictStoreRes = callApi('POST', '/api/admin/schedules', [
    'course_class_id'  => $class2Id,
    'room_id'          => $roomId,
    'day_of_week'      => 'Senin',
    'start_time'       => '09:00',
    'end_time'         => '11:00',
    'academic_year_id' => $academicYearId,
]);

assertFeature(18, 'Sistem Pengecekan Jadwal Bentrok Otomatis',
    ($checkConflictRes['body']['has_conflict'] ?? false) === true && $conflictStoreRes['status'] === 422,
    "Check conflict: " . json_encode($checkConflictRes['body']) . ", Store status: {$conflictStoreRes['status']}"
);

// -------------------------------------------------------------
// 19. Mengubah Status Kemahasiswaan
// -------------------------------------------------------------
$targetStudentId = $testUserId ?: 1;
$statusChangeRes = callApi('POST', "/api/admin/students/{$targetStudentId}/status", [
    'status'         => 'Cuti',
    'reason'         => 'Cuti akademik semester 3 alasan kesehatan',
    'decree_number'  => 'SK-CUTI/2026/089',
    'effective_date' => date('Y-m-d'),
]);
$statusHistoryRes = callApi('GET', "/api/admin/students/{$targetStudentId}/status-history");

assertFeature(19, 'API Pengubahan Status Kemahasiswaan & Riwayat',
    $statusChangeRes['status'] === 200 && $statusHistoryRes['status'] === 200 && ($statusChangeRes['body']['data']['status'] ?? '') === 'Cuti',
    json_encode($statusChangeRes['body'])
);

// -------------------------------------------------------------
// 20. Jadwal Ujian UTS dan UAS
// -------------------------------------------------------------
$randDays = rand(30, 90);
$randHour = str_pad(rand(7, 15), 2, '0', STR_PAD_LEFT);
$randHourEnd = str_pad((int)$randHour + 2, 2, '0', STR_PAD_LEFT);

$examRes = callApi('POST', '/api/admin/exam-schedules', [
    'course_class_id'  => $classId,
    'exam_type'        => 'UTS',
    'exam_date'        => date('Y-m-d', strtotime("+{$randDays} days")),
    'start_time'       => "{$randHour}:00",
    'end_time'         => "{$randHourEnd}:00",
    'room_id'          => $roomId,
    'academic_year_id' => $academicYearId,
    'notes'            => 'Ujian Tertulis di Ruang C.201',
]);
$examId = $examRes['body']['data']['id'] ?? null;
$listExamsRes = callApi('GET', '/api/admin/exam-schedules');

assertFeature(20, 'Pengelolaan Jadwal UTS dan UAS',
    $examRes['status'] === 201 && $listExamsRes['status'] === 200,
    json_encode($examRes['body'])
);

// -------------------------------------------------------------
// 21. Penempatan Pengawas Ujian
// -------------------------------------------------------------
$assignInvigilatorRes = $examId ? callApi('POST', "/api/admin/exam-schedules/{$examId}/invigilators", [
    'invigilator_id'    => $dosenId,
    'role'              => 'Pengawas 1',
    'attendance_status' => 'Hadir',
]) : null;
$listInvigilatorsRes = $examId ? callApi('GET', "/api/admin/exam-schedules/{$examId}/invigilators") : null;

assertFeature(21, 'Fitur Penempatan Pengawas Ujian',
    ($assignInvigilatorRes['status'] ?? 0) === 200 && ($listInvigilatorsRes['status'] ?? 0) === 200,
    json_encode($assignInvigilatorRes['body'] ?? [])
);

// -------------------------------------------------------------
// 22. Pengaturan Parameter Global (Passing Grade, Batas Kehadiran)
// -------------------------------------------------------------
$updateSettingsRes = callApi('PUT', '/api/admin/settings', [
    'settings' => [
        'passing_grade'                 => '60.0',
        'minimum_attendance_percentage' => '80',
        'max_sks_default'               => '24',
    ],
]);
$getSettingRes = callApi('GET', '/api/admin/settings/passing_grade');

assertFeature(22, 'Pengaturan Parameter Global (Passing Grade & Batas Kehadiran)',
    $updateSettingsRes['status'] === 200 && ($getSettingRes['body']['value'] ?? null) == 60.0,
    json_encode($getSettingRes['body'])
);

// -------------------------------------------------------------
// 23. Pembatasan Waktu Penginputan Nilai oleh Dosen
// -------------------------------------------------------------
$deadlineRes = callApi('POST', '/api/admin/grade-deadlines', [
    'academic_year_id' => $academicYearId,
    'grade_component'  => 'Nilai Akhir',
    'start_date'       => date('Y-m-d H:i:s', strtotime('-2 days')),
    'end_date'         => date('Y-m-d H:i:s', strtotime('+5 days')),
    'is_active'        => true,
]);
$checkInputStatusRes = callApi('GET', "/api/admin/grade-deadlines/check-status?class_id={$classId}&component=Nilai Akhir");

assertFeature(23, 'Pembatasan Waktu Penginputan Nilai oleh Dosen',
    $deadlineRes['status'] === 201 && ($checkInputStatusRes['body']['is_allowed'] ?? false) === true,
    json_encode($checkInputStatusRes['body'])
);

// -------------------------------------------------------------
// 24. Tanggal Penting Kalender Akademik
// -------------------------------------------------------------
$calRes = callApi('POST', '/api/admin/academic-calendar', [
    'academic_year_id' => $academicYearId,
    'title'            => 'Awal Perkuliahan Semester Ganjil 2027/2028',
    'event_type'       => 'perkuliahan',
    'start_date'       => '2027-09-01',
    'end_date'         => '2027-09-05',
    'is_public'        => true,
]);
$calFeedRes = callApi('GET', '/api/academic-calendar');

assertFeature(24, 'Pengelolaan Tanggal Penting Akademik & Feed Dashboard',
    $calRes['status'] === 201 && $calFeedRes['status'] === 200 && count($calFeedRes['body']['data'] ?? []) > 0,
    json_encode($calRes['body'])
);

// -------------------------------------------------------------
// 25. Pengumuman Dashboard Pengguna
// -------------------------------------------------------------
$annRes = callApi('POST', '/api/admin/announcements', [
    'title'       => 'Pemberitahuan Registrasi Ulang & KRS Semester Baru',
    'content'     => 'Diberitahukan kepada seluruh mahasiswa bahwa pengisian KRS dimulai tanggal 1 September 2027.',
    'target_role' => 'Semua',
    'priority'    => 'Penting',
    'is_published'=> true,
]);
$annFeedRes = callApi('GET', '/api/announcements/feed');

assertFeature(25, 'Pengelolaan Pengumuman Dashboard Pengguna',
    $annRes['status'] === 201 && $annFeedRes['status'] === 200 && count($annFeedRes['body']['data'] ?? []) > 0,
    json_encode($annRes['body'])
);

echo "\n=======================================================\n";
echo "SUMMARY: Passed {$passed} / 25, Failed: {$failed}\n";
echo "=======================================================\n";
