<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLock extends Model
{
    use HasFactory;

    protected $table = 'grade_locks';

    protected $fillable = [
        'course_class_id',
        'is_locked',
        'locked_at',
        'unlocked_by_user_id',
        'unlocked_at',
        'unlock_until',
        'unlock_reason',
    ];

    protected $casts = [
        'is_locked'   => 'boolean',
        'locked_at'   => 'datetime',
        'unlocked_at' => 'datetime',
        'unlock_until'=> 'datetime',
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function unlockedBy()
    {
        return $this->belongsTo(User::class, 'unlocked_by_user_id');
    }

    /**
     * Cek apakah saat ini kelas terbuka untuk input nilai oleh dosen
     */
    public function isGradeInputAllowed(): bool
    {
        // Jika status tidak terkunci
        if (!$this->is_locked) {
            return true;
        }

        // Jika ada unlock khusus admin dan masa berlaku belum lewat
        if ($this->unlock_until && Carbon::now()->lessThanOrEqualTo($this->unlock_until)) {
            return true;
        }

        return false;
    }
}
