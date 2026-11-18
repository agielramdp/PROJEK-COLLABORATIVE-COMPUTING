<?php

use App\Http\Controllers\AcademicCalendarController;
use App\Http\Controllers\AcademicReportController;
use App\Http\Controllers\AcademicSettingController;
use App\Http\Controllers\AcademicStructureController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BendaharaDashboardController;
use App\Http\Controllers\ClassAssignmentController;
use App\Http\Controllers\CurriculumCourseController;
use App\Http\Controllers\DosenPengajarDashboardController;
use App\Http\Controllers\DosenWaliDashboardController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\GradeLockController;
use App\Http\Controllers\KrsPeriodController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentStatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SI-AKAD
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. PUBLIC & AUTHENTICATION
// ==========================================
Route::get('/', function () {
    return view('login', [
        'googleConfigured' => !empty(config('services.google.client_id')),
    ]);
})->name('home');

Route::get('/login', function () {
    return view('login', [
        'googleConfigured' => !empty(config('services.google.client_id')),
    ]);
})->name('login');

// Google OAuth 2.0 Web Flow
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('google.callback');

// ==========================================
// 2. ADMIN PORTAL (/admin/...)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pengguna
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/manajemen-pengguna', [AdminUserController::class, 'index'])->name('users.alias');
});

// Admin Route Aliases / Backward Compatibility
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
});
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard');

// ==========================================
// 3. MAHASISWA PORTAL (/mahasiswa/...)
// ==========================================
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Mahasiswa Dashboard
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
});

// Mahasiswa Route Aliases / Backward Compatibility
Route::get('/dashboard-mahasiswa', function () {
    return redirect()->route('mahasiswa.dashboard');
})->name('dashboard.mahasiswa');

// ==========================================
// 4. DOSEN WALI PORTAL (/dosen-wali/...)
// ==========================================
Route::prefix('dosen-wali')->name('dosen-wali.')->group(function () {
    Route::get('/dashboard', [DosenWaliDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/dosen-wali', function () {
    return redirect()->route('dosen-wali.dashboard');
});

// ==========================================
// 5. DOSEN PENGAJAR PORTAL (/dosen-pengajar/...)
// ==========================================
Route::prefix('dosen-pengajar')->name('dosen-pengajar.')->group(function () {
    Route::get('/dashboard', [DosenPengajarDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/dosen-pengajar', function () {
    return redirect()->route('dosen-pengajar.dashboard');
});

// ==========================================
// 6. BENDAHARA PORTAL (/bendahara/...)
// ==========================================
Route::prefix('bendahara')->name('bendahara.')->group(function () {
    Route::get('/dashboard', [BendaharaDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/bendahara', function () {
    return redirect()->route('bendahara.dashboard');
});

// ==========================================
// 7. API ENDPOINTS (/api/...)
// ==========================================
Route::prefix('api')->group(function () {
    // Auth API
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/login-status', [AuthController::class, 'checkLoginStatus'])->name('api.login.status');
    Route::post('/reset-attempts', [AuthController::class, 'resetAttempts'])->name('api.reset.attempts');
    Route::post('/auth/google', [AuthController::class, 'googleLogin'])->name('api.google.login');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.forgot.password');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('api.verify.otp');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('api.reset.password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // Admin API
    Route::prefix('admin')->name('api.admin.')->group(function () {
        // Admin Dashboard Stats
        Route::get('/dashboard/stats', [AdminDashboardController::class, 'apiStats'])->name('dashboard.stats');

        // User Management CRUD (Item 1)
        Route::get('/users', [AdminUserController::class, 'apiIndex'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'apiStore'])->name('users.store');
        Route::get('/users/export', [AdminUserController::class, 'apiExport'])->name('users.export'); // Item 7
        Route::post('/users/import', [AdminUserController::class, 'apiImport'])->name('users.import'); // Item 7
        Route::get('/users/{id}', [AdminUserController::class, 'apiShow'])->name('users.show');
        Route::put('/users/{id}', [AdminUserController::class, 'apiUpdate'])->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'apiDestroy'])->name('users.destroy');
        Route::post('/users/{id}/reset-password', [AdminUserController::class, 'apiResetPasswordManual'])->name('users.reset-password'); // Item 10
        Route::post('/users/{id}/assign-role', [RolePermissionController::class, 'assignUserRole'])->name('users.assign-role'); // Item 2

        // 2. Roles & Permissions (Item 2)
        Route::get('/roles', [RolePermissionController::class, 'indexRoles'])->name('roles.index');
        Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('roles.store');
        Route::get('/roles/{id}', [RolePermissionController::class, 'showRole'])->name('roles.show');
        Route::put('/roles/{id}', [RolePermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{id}', [RolePermissionController::class, 'destroyRole'])->name('roles.destroy');
        Route::get('/permissions', [RolePermissionController::class, 'indexPermissions'])->name('permissions.index');
        Route::post('/roles/{id}/permissions', [RolePermissionController::class, 'syncPermissions'])->name('roles.permissions.sync');

        // 3. Fakultas, Jenjang Pendidikan & Program Studi (Item 3)
        Route::get('/faculties', [AcademicStructureController::class, 'indexFaculties'])->name('faculties.index');
        Route::post('/faculties', [AcademicStructureController::class, 'storeFaculty'])->name('faculties.store');
        Route::get('/faculties/{id}', [AcademicStructureController::class, 'showFaculty'])->name('faculties.show');
        Route::put('/faculties/{id}', [AcademicStructureController::class, 'updateFaculty'])->name('faculties.update');
        Route::delete('/faculties/{id}', [AcademicStructureController::class, 'destroyFaculty'])->name('faculties.destroy');

        Route::get('/education-levels', [AcademicStructureController::class, 'indexEducationLevels'])->name('education-levels.index');
        Route::post('/education-levels', [AcademicStructureController::class, 'storeEducationLevel'])->name('education-levels.store');
        Route::get('/education-levels/{id}', [AcademicStructureController::class, 'showEducationLevel'])->name('education-levels.show');
        Route::put('/education-levels/{id}', [AcademicStructureController::class, 'updateEducationLevel'])->name('education-levels.update');
        Route::delete('/education-levels/{id}', [AcademicStructureController::class, 'destroyEducationLevel'])->name('education-levels.destroy');

        Route::get('/study-programs', [AcademicStructureController::class, 'indexStudyPrograms'])->name('study-programs.index');
        Route::post('/study-programs', [AcademicStructureController::class, 'storeStudyProgram'])->name('study-programs.store');
        Route::get('/study-programs/{id}', [AcademicStructureController::class, 'showStudyProgram'])->name('study-programs.show');
        Route::put('/study-programs/{id}', [AcademicStructureController::class, 'updateStudyProgram'])->name('study-programs.update');
        Route::delete('/study-programs/{id}', [AcademicStructureController::class, 'destroyStudyProgram'])->name('study-programs.destroy');

        // 4. Tahun Akademik & Semester Aktif (Item 4)
        Route::get('/academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
        Route::get('/academic-years/active', [AcademicYearController::class, 'getActive'])->name('academic-years.active');
        Route::post('/academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
        Route::get('/academic-years/{id}', [AcademicYearController::class, 'show'])->name('academic-years.show');
        Route::put('/academic-years/{id}', [AcademicYearController::class, 'update'])->name('academic-years.update');
        Route::delete('/academic-years/{id}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');
        Route::post('/academic-years/{id}/set-active', [AcademicYearController::class, 'setActive'])->name('academic-years.set-active');

        // 5. Gedung, Lantai & Ruangan (Item 5)
        Route::get('/buildings', [FacilityController::class, 'indexBuildings'])->name('buildings.index');
        Route::post('/buildings', [FacilityController::class, 'storeBuilding'])->name('buildings.store');
        Route::get('/buildings/{id}', [FacilityController::class, 'showBuilding'])->name('buildings.show');
        Route::put('/buildings/{id}', [FacilityController::class, 'updateBuilding'])->name('buildings.update');
        Route::delete('/buildings/{id}', [FacilityController::class, 'destroyBuilding'])->name('buildings.destroy');

        Route::get('/rooms', [FacilityController::class, 'indexRooms'])->name('rooms.index');
        Route::post('/rooms', [FacilityController::class, 'storeRoom'])->name('rooms.store');
        Route::get('/rooms/{id}', [FacilityController::class, 'showRoom'])->name('rooms.show');
        Route::put('/rooms/{id}', [FacilityController::class, 'updateRoom'])->name('rooms.update');
        Route::delete('/rooms/{id}', [FacilityController::class, 'destroyRoom'])->name('rooms.destroy');

        // 6. Pengaturan Periode KRS (Item 6)
        Route::get('/krs-periods', [KrsPeriodController::class, 'index'])->name('krs-periods.index');
        Route::get('/krs-periods/status', [KrsPeriodController::class, 'checkStatus'])->name('krs-periods.status');
        Route::post('/krs-periods', [KrsPeriodController::class, 'store'])->name('krs-periods.store');
        Route::get('/krs-periods/{id}', [KrsPeriodController::class, 'show'])->name('krs-periods.show');
        Route::put('/krs-periods/{id}', [KrsPeriodController::class, 'update'])->name('krs-periods.update');
        Route::delete('/krs-periods/{id}', [KrsPeriodController::class, 'destroy'])->name('krs-periods.destroy');
        Route::post('/krs-periods/{id}/toggle', [KrsPeriodController::class, 'toggle'])->name('krs-periods.toggle');

        // 8. Laporan Akademik (Item 8)
        Route::get('/reports/types', [AcademicReportController::class, 'getTypes'])->name('reports.types');
        Route::get('/reports/download', [AcademicReportController::class, 'download'])->name('reports.download');

        // 9. Validasi & Upload File (Item 9)
        Route::get('/upload-rules', [FileUploadController::class, 'getRules'])->name('upload.rules');
        Route::post('/upload-file', [FileUploadController::class, 'upload'])->name('upload.file');

        // 11. Audit Log Aktivitas Admin (Item 11)
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/modules', [AuditLogController::class, 'getModules'])->name('audit-logs.modules');
        Route::post('/audit-logs', [AuditLogController::class, 'store'])->name('audit-logs.store');

        // 12 & 23. Kunci Nilai & Batas Waktu Input Nilai Dosen (Item 12 & 23)
        Route::get('/grades/locks', [GradeLockController::class, 'indexLocks'])->name('grades.locks.index');
        Route::post('/grades/locks/{classId}/unlock', [GradeLockController::class, 'unlockGrade'])->name('grades.locks.unlock');
        Route::post('/grades/locks/{classId}/lock', [GradeLockController::class, 'lockGrade'])->name('grades.locks.lock');
        Route::get('/grade-deadlines', [GradeLockController::class, 'indexDeadlines'])->name('grade-deadlines.index');
        Route::post('/grade-deadlines', [GradeLockController::class, 'storeDeadline'])->name('grade-deadlines.store');
        Route::put('/grade-deadlines/{id}', [GradeLockController::class, 'updateDeadline'])->name('grade-deadlines.update');
        Route::delete('/grade-deadlines/{id}', [GradeLockController::class, 'destroyDeadline'])->name('grade-deadlines.destroy');
        Route::get('/grade-deadlines/check-status', [GradeLockController::class, 'checkGradeInputStatus'])->name('grade-deadlines.check');

        // 13 & 14. Kurikulum, Mata Kuliah & Prasyarat (Item 13 & 14)
        Route::get('/curriculums', [CurriculumCourseController::class, 'indexCurriculums'])->name('curriculums.index');
        Route::post('/curriculums', [CurriculumCourseController::class, 'storeCurriculum'])->name('curriculums.store');
        Route::get('/curriculums/{id}', [CurriculumCourseController::class, 'showCurriculum'])->name('curriculums.show');
        Route::put('/curriculums/{id}', [CurriculumCourseController::class, 'updateCurriculum'])->name('curriculums.update');
        Route::delete('/curriculums/{id}', [CurriculumCourseController::class, 'destroyCurriculum'])->name('curriculums.destroy');

        Route::get('/courses', [CurriculumCourseController::class, 'indexCourses'])->name('courses.index');
        Route::post('/courses', [CurriculumCourseController::class, 'storeCourse'])->name('courses.store');
        Route::get('/courses/{id}', [CurriculumCourseController::class, 'showCourse'])->name('courses.show');
        Route::put('/courses/{id}', [CurriculumCourseController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{id}', [CurriculumCourseController::class, 'destroyCourse'])->name('courses.destroy');
        Route::get('/courses/{courseId}/prerequisites', [CurriculumCourseController::class, 'getPrerequisites'])->name('courses.prerequisites.index');
        Route::post('/courses/{courseId}/prerequisites', [CurriculumCourseController::class, 'addPrerequisite'])->name('courses.prerequisites.store');
        Route::delete('/courses/{courseId}/prerequisites/{prerequisiteId}', [CurriculumCourseController::class, 'removePrerequisite'])->name('courses.prerequisites.destroy');
        Route::post('/courses/check-prerequisites', [CurriculumCourseController::class, 'checkPrerequisites'])->name('courses.check-prerequisites');

        // 15 & 16. Kelas Kuliah, Dosen Pengampu & Dosen Pembimbing Akademik (Item 15 & 16)
        Route::get('/course-classes', [ClassAssignmentController::class, 'indexCourseClasses'])->name('course-classes.index');
        Route::post('/course-classes', [ClassAssignmentController::class, 'storeCourseClass'])->name('course-classes.store');
        Route::get('/course-classes/{id}', [ClassAssignmentController::class, 'showCourseClass'])->name('course-classes.show');
        Route::put('/course-classes/{id}', [ClassAssignmentController::class, 'updateCourseClass'])->name('course-classes.update');
        Route::delete('/course-classes/{id}', [ClassAssignmentController::class, 'destroyCourseClass'])->name('course-classes.destroy');
        Route::get('/course-classes/{classId}/lecturers', [ClassAssignmentController::class, 'getCourseClassLecturers'])->name('course-classes.lecturers.index');
        Route::post('/course-classes/{classId}/lecturers', [ClassAssignmentController::class, 'assignClassLecturer'])->name('course-classes.lecturers.assign');
        Route::delete('/course-classes/{classId}/lecturers/{lecturerId}', [ClassAssignmentController::class, 'removeClassLecturer'])->name('course-classes.lecturers.remove');

        Route::get('/academic-advisors', [ClassAssignmentController::class, 'indexAdvisors'])->name('academic-advisors.index');
        Route::post('/academic-advisors/assign', [ClassAssignmentController::class, 'assignAdvisor'])->name('academic-advisors.assign');
        Route::delete('/academic-advisors/{id}', [ClassAssignmentController::class, 'removeAdvisor'])->name('academic-advisors.remove');

        // 17 & 18. Jadwal Perkuliahan & Cek Bentrok (Item 17 & 18)
        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/{id}', [ScheduleController::class, 'show'])->name('schedules.show');
        Route::put('/schedules/{id}', [ScheduleController::class, 'update'])->name('schedules.update');
        Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::post('/schedules/check-conflict', [ScheduleController::class, 'checkConflict'])->name('schedules.check-conflict');

        // 19. Status Kemahasiswaan (Item 19)
        Route::get('/students/status', [StudentStatusController::class, 'index'])->name('students.status.index');
        Route::post('/students/{id}/status', [StudentStatusController::class, 'changeStatus'])->name('students.status.change');
        Route::get('/students/{id}/status-history', [StudentStatusController::class, 'getHistory'])->name('students.status.history');

        // 20 & 21. Jadwal Ujian UTS/UAS & Pengawas Ujian (Item 20 & 21)
        Route::get('/exam-schedules', [ExamScheduleController::class, 'index'])->name('exam-schedules.index');
        Route::post('/exam-schedules', [ExamScheduleController::class, 'store'])->name('exam-schedules.store');
        Route::get('/exam-schedules/{id}', [ExamScheduleController::class, 'show'])->name('exam-schedules.show');
        Route::put('/exam-schedules/{id}', [ExamScheduleController::class, 'update'])->name('exam-schedules.update');
        Route::delete('/exam-schedules/{id}', [ExamScheduleController::class, 'destroy'])->name('exam-schedules.destroy');
        Route::get('/exam-schedules/{id}/invigilators', [ExamScheduleController::class, 'getInvigilators'])->name('exam-schedules.invigilators.index');
        Route::post('/exam-schedules/{id}/invigilators', [ExamScheduleController::class, 'assignInvigilator'])->name('exam-schedules.invigilators.assign');
        Route::delete('/exam-schedules/{id}/invigilators/{invigilatorId}', [ExamScheduleController::class, 'removeInvigilator'])->name('exam-schedules.invigilators.remove');

        // 22. Parameter Global Akademik (Item 22)
        Route::get('/settings', [AcademicSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [AcademicSettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/{key}', [AcademicSettingController::class, 'show'])->name('settings.show');

        // 24. Tanggal Penting Kalender Akademik (Item 24)
        Route::get('/academic-calendar', [AcademicCalendarController::class, 'index'])->name('academic-calendar.index');
        Route::post('/academic-calendar', [AcademicCalendarController::class, 'store'])->name('academic-calendar.store');
        Route::get('/academic-calendar/{id}', [AcademicCalendarController::class, 'show'])->name('academic-calendar.show');
        Route::put('/academic-calendar/{id}', [AcademicCalendarController::class, 'update'])->name('academic-calendar.update');
        Route::delete('/academic-calendar/{id}', [AcademicCalendarController::class, 'destroy'])->name('academic-calendar.destroy');

        // 25. Pengumuman Dashboard (Item 25)
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/announcements/{id}', [AnnouncementController::class, 'show'])->name('announcements.show');
        Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    // Public / Shared Feeds for User Dashboards (Mahasiswa, Dosen, etc.)
    Route::get('/announcements/feed', [AnnouncementController::class, 'feed'])->name('api.announcements.feed'); // Item 25
    Route::get('/academic-calendar', [AcademicCalendarController::class, 'feed'])->name('api.academic-calendar'); // Item 24
    Route::get('/krs/status', [KrsPeriodController::class, 'checkStatus'])->name('api.krs.status'); // Item 6
});
