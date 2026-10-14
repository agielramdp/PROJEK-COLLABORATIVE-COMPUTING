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
        return $this->password_hash;
    }

    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Relasi ke tabel otp_codes di db_sistem_akademik
     */
    public function otpCodes()
    {
        return $this->hasMany(OtpCode::class, 'user_id');
    }
}
