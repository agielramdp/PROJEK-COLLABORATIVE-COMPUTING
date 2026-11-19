<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('curriculums')) {
            Schema::create('curriculums', function (Blueprint $table) {
                $table->id();
                $table->foreignId('study_program_id')->constrained()->restrictOnDelete();
                $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
                $table->string('code', 30)->unique();
                $table->string('name', 100);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['study_program_id', 'is_active']);
            });
        }

        if (! Schema::hasTable('courses')) {
            Schema::create('courses', function (Blueprint $table) {
                $table->id();
                $table->string('code', 30)->unique();
                $table->string('name', 150);
                $table->unsignedTinyInteger('credits');
                $table->unsignedTinyInteger('semester')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('curriculum_courses')) {
            Schema::create('curriculum_courses', function (Blueprint $table) {
                $table->unsignedBigInteger('curriculum_id');
                $table->foreign('curriculum_id')->references('id')->on('curriculums')->cascadeOnDelete();
                $table->foreignId('course_id')->constrained()->restrictOnDelete();
                $table->unsignedTinyInteger('semester')->nullable();
                $table->boolean('is_required')->default(true);
                $table->primary(['curriculum_id', 'course_id']);
            });
        }

        if (! Schema::hasTable('course_prerequisites')) {
            Schema::create('course_prerequisites', function (Blueprint $table) {
                $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
                $table->foreignId('prerequisite_course_id')->constrained('courses')->restrictOnDelete();
                $table->primary(['course_id', 'prerequisite_course_id']);
            });
        }

        if (! Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('course_id')->constrained()->restrictOnDelete();
                $table->unsignedBigInteger('curriculum_id')->nullable();
                $table->foreign('curriculum_id')->references('id')->on('curriculums')->restrictOnDelete();
                $table->foreignId('study_program_id')->constrained()->restrictOnDelete();
                $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
                $table->foreignId('semester_id')->constrained()->restrictOnDelete();
                $table->string('code', 30);
                $table->string('name', 100);
                $table->unsignedInteger('capacity')->default(0);
                $table->string('status', 30)->default('active');
                $table->timestamps();
                $table->unique(['academic_year_id', 'semester_id', 'course_id', 'code']);
                $table->index(['academic_year_id', 'semester_id', 'status']);
            });
        }

        if (! Schema::hasTable('class_lecturers')) {
            Schema::create('class_lecturers', function (Blueprint $table) {
                $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
                $table->foreignId('lecturer_id')->constrained('lecturers')->restrictOnDelete();
                $table->boolean('is_primary')->default(false);
                $table->primary(['class_id', 'lecturer_id']);
            });
        }

        if (! Schema::hasTable('academic_advisors')) {
            Schema::create('academic_advisors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained()->restrictOnDelete();
                $table->foreignId('lecturer_id')->constrained()->restrictOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained()->restrictOnDelete();
                $table->date('assigned_at');
                $table->string('status', 30)->default('active');
                $table->timestamps();
                $table->unique(['student_id', 'lecturer_id', 'academic_year_id']);
                $table->index(['lecturer_id', 'status']);
            });
        }

        if (! Schema::hasTable('class_schedules')) {
            Schema::create('class_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
                $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->restrictOnDelete();
                $table->foreignId('room_id')->constrained('rooms')->restrictOnDelete();
                $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
                $table->foreignId('semester_id')->constrained()->restrictOnDelete();
                $table->string('day', 20);
                $table->time('start_time');
                $table->time('end_time');
                $table->timestamps();
                $table->index(['day', 'start_time', 'end_time']);
                $table->index(['room_id', 'day', 'start_time', 'end_time']);
                $table->index(['lecturer_id', 'day', 'start_time', 'end_time']);
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'class_schedules', 'academic_advisors', 'class_lecturers', 'classes',
            'course_prerequisites', 'curriculum_courses', 'courses', 'curriculums',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
