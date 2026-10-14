<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('mahasiswa')->after('name');
            $table->string('identity_number')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('identity_number');
            $table->string('google_id')->nullable()->after('avatar');
            $table->string('otp_code')->nullable()->after('google_id');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'identity_number',
                'avatar',
                'google_id',
                'otp_code',
                'otp_expires_at',
            ]);
        });
    }
};
