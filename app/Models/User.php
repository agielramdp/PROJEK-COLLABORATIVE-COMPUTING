<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable in db_sistem_akademik.users
     */
    protected $fillable = [
        'npm',
        'name',
        'email',
        'username',
        'password_hash',
        'role',
        'google_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
        ];
    }

    /**
     * Override to use password_hash column in db_sistem_akademik
     */
    public function getAuthPassword()
    {
        return (string) ($this->password_hash ?? $this->password ?? '');
    }

    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Pastikan getRememberToken selalu mengembalikan string (mencegah TypeError hash_equals pada PHP 8.2+)
     */
    public function getRememberToken()
    {
        return (string) ($this->attributes[$this->getRememberTokenName()] ?? '');
    }

    /**
     * Relasi ke tabel otp_codes di db_sistem_akademik
     */
    public function otpCodes()
    {
        return $this->hasMany(OtpCode::class, 'user_id');
    }

    /**
     * Relasi ke tabel roles melalui user_has_roles
     */
    public function assignedRoles()
    {
        return $this->belongsToMany(Role::class, 'user_has_roles', 'user_id', 'role_id');
    }

    /**
     * Cek apakah user memiliki role tertentu (baik via kolom 'role' maupun pivot)
     */
    public function hasRole(string $roleName): bool
    {
        if (strcasecmp($this->role ?? '', $roleName) === 0) {
            return true;
        }

        return $this->assignedRoles()->where(function ($q) use ($roleName) {
            $q->where('name', $roleName)->orWhere('slug', strtolower(str_replace(' ', '_', $roleName)));
        })->exists();
    }

    /**
     * Relasi status kemahasiswaan
     */
    public function studentStatuses()
    {
        return $this->hasMany(StudentStatus::class, 'student_id');
    }

    /**
     * Relasi dosen pembimbing akademik (untuk mahasiswa)
     */
    public function academicAdvisors()
    {
        return $this->hasMany(AcademicAdvisor::class, 'student_id');
    }

    /**
     * Relasi mahasiswa bimbingan (untuk dosen wali)
     */
    public function adviseeStudents()
    {
        return $this->hasMany(AcademicAdvisor::class, 'lecturer_id');
    }

    /**
     * Relasi kelas yang diampu (untuk dosen)
     */
    public function teachingClasses()
    {
        return $this->hasMany(ClassLecturer::class, 'lecturer_id');
    }
}
