<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('academic_years')) {
            Schema::create('academic_years', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->unique();
                $table->string('name', 100);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('semesters')) {
            Schema::create('semesters', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
                $table->string('code', 20);
                $table->string('name', 50);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
                $table->unique(['academic_year_id', 'code']);
            });
        }

        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50)->unique();
                $table->string('slug', 50)->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->string('slug', 100)->unique();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->foreignId('role_id')->constrained()->cascadeOnDelete();
                $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
                $table->primary(['role_id', 'permission_id']);
            });
        }

        if (! Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->unsignedInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                $table->foreignId('role_id')->constrained()->cascadeOnDelete();
                $table->primary(['user_id', 'role_id']);
            });
        }

        if (! Schema::hasTable('lecturers')) {
            Schema::create('lecturers', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable()->unique();
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->foreignId('study_program_id')->nullable()->constrained()->restrictOnDelete();
                $table->foreignId('faculty_id')->nullable()->constrained()->restrictOnDelete();
                $table->string('nidn', 30)->nullable()->unique();
                $table->string('nip', 30)->nullable()->unique();
                $table->string('name', 100);
                $table->string('email', 100)->nullable()->unique();
                $table->string('phone', 30)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable()->unique();
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->foreignId('study_program_id')->constrained()->restrictOnDelete();
                $table->foreignId('academic_year_id')->nullable()->constrained()->restrictOnDelete();
                $table->string('nim', 30)->unique();
                $table->string('name', 100);
                $table->string('email', 100)->nullable()->unique();
                $table->string('phone', 30)->nullable();
                $table->date('birth_date')->nullable();
                $table->string('birth_place', 100)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['study_program_id', 'is_active']);
            });
        }

        if (! Schema::hasTable('buildings')) {
            Schema::create('buildings', function (Blueprint $table) {
                $table->id();
                $table->string('code', 20)->unique();
                $table->string('name', 100);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('floors')) {
            Schema::create('floors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('building_id')->constrained()->restrictOnDelete();
                $table->string('code', 20);
                $table->string('name', 100);
                $table->timestamps();
                $table->unique(['building_id', 'code']);
            });
        }

        if (! Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('floor_id')->constrained()->restrictOnDelete();
                $table->string('code', 30)->unique();
                $table->string('name', 100);
                $table->unsignedInteger('capacity')->default(0);
                $table->string('room_type', 50)->default('classroom');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index(['floor_id', 'is_active']);
            });
        }

        if (! Schema::hasTable('krs_periods')) {
            Schema::create('krs_periods', function (Blueprint $table) {
                $table->id();
                $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
                $table->foreignId('semester_id')->constrained()->restrictOnDelete();
                $table->dateTime('start_at');
                $table->dateTime('end_at');
                $table->boolean('is_open')->default(false);
                $table->text('description')->nullable();
                $table->timestamps();
                $table->unique(['academic_year_id', 'semester_id']);
            });
        }

        if (! Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->string('activity_type', 50);
                $table->string('entity_name', 100);
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->json('before_data')->nullable();
                $table->json('after_data')->nullable();
                $table->timestamp('occurred_at')->useCurrent();
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();
                $table->index(['entity_name', 'entity_id']);
                $table->index(['user_id', 'occurred_at']);
            });
        }

        if (! Schema::hasTable('import_logs')) {
            Schema::create('import_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->string('file_name', 255);
                $table->unsignedInteger('successful_rows')->default(0);
                $table->unsignedInteger('failed_rows')->default(0);
                $table->string('status', 30);
                $table->timestamp('processed_at')->useCurrent();
                $table->text('error_detail')->nullable();
                $table->timestamps();
                $table->index(['status', 'processed_at']);
            });
        }
    }

    public function down(): void
    {
        foreach ([
            'import_logs', 'audit_logs', 'krs_periods', 'rooms', 'floors', 'buildings',
            'students', 'lecturers', 'user_roles', 'role_permissions', 'permissions',
            'roles', 'semesters', 'academic_years',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
