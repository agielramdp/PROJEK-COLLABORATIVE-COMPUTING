<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    use HasFactory;

    protected $table = 'otp_codes';

    public $timestamps = false; // Table only has created_at

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
        'is_used',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_used'    => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Relationship: An OTP code belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if OTP is still valid (not used and not expired)
     */
    public function isValid(): bool
    {
        return !$this->is_used && now()->lt($this->expires_at);
    }
}
