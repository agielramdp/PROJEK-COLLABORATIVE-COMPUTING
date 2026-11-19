<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('student_statuses')) {
            Schema::create('student_statuses', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique();
                $table->string('name', 100);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('student_status_histories')) {
            Schema::create('student_status_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->cascadeOnDelete();
                $table->foreignId('previous_status_id')->nullable()->constrained('student_statuses')->restrictOnDelete();
                $table->foreignId('new_status_id')->constrained('student_statuses')->restrictOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained()->restrictOnDelete();
                $table->unsignedInteger('changed_by')->nullable();
                $table->foreign('changed_by')->references('id')->on('users')->nullOnDelete();
                $table->date('changed_at');
                $table->text('reason')->nullable();
                $table->timestamps();
                $table->index(['student_id', 'changed_at']);
            });
        }

        if (! Schema::hasTable('exam_schedules')) {
            Schema::create('exam_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
                $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
                $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
                $table->foreignId('semester_id')->constrained()->restrictOnDelete();
                $table->string('exam_type', 20);
                $table->date('exam_date');
                $table->time('start_time');
                $table->time('end_time');
                $table->timestamps();
                $table->index(['room_id', 'exam_date', 'start_time', 'end_time']);
                $table->index(['academic_year_id', 'semester_id', 'exam_type']);
            });
        }

        if (! Schema::hasTable('exam_supervisors')) {
            Schema::create('exam_supervisors', function (Blueprint $table) {
                $table->foreignId('exam_schedule_id')->constrained()->cascadeOnDelete();
                $table->foreignId('lecturer_id')->constrained()->restrictOnDelete();
                $table->primary(['exam_schedule_id', 'lecturer_id']);
            });
        }

        if (! Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->text('value')->nullable();
                $table->string('value_type', 20)->default('string');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('grade_submission_deadlines')) {
            Schema::create('grade_submission_deadlines', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
                $table->foreignId('semester_id')->constrained()->restrictOnDelete();
                $table->foreignId('course_id')->nullable()->constrained()->restrictOnDelete();
                $table->foreignId('class_id')->nullable()->constrained('classes')->restrictOnDelete();
                $table->unsignedInteger('unlocked_by')->nullable();
                $table->foreign('unlocked_by')->references('id')->on('users')->nullOnDelete();
                $table->dateTime('deadline_at');
                $table->boolean('is_locked')->default(false);
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->index(['academic_year_id', 'semester_id', 'is_locked'], 'grade_deadline_period_lock_index');
            });
        }

        if (! Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
                $table->string('title', 200);
                $table->text('content');
                $table->dateTime('start_at');
                $table->dateTime('end_at')->nullable();
                $table->string('status', 30)->default('draft');
                $table->string('target_role', 50)->nullable();
                $table->timestamps();
                $table->index(['status', 'start_at', 'end_at']);
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'announcements', 'grade_submission_deadlines', 'system_settings',
            'exam_supervisors', 'exam_schedules', 'student_status_histories',
            'student_statuses',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
