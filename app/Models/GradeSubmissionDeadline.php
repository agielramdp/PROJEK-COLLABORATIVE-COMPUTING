<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeSubmissionDeadline extends Model
{
    use HasFactory;

    protected $table = 'grade_submission_deadlines';

    protected $fillable = [
        'academic_year_id',
        'grade_component',
        'start_date',
        'end_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    /**
     * Cek apakah saat ini dalam batas waktu penginputan nilai
     */
    public function isOpenNow(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return Carbon::now()->between($this->start_date, $this->end_date);
    }
}
