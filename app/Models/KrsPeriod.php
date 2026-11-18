<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KrsPeriod extends Model
{
    use HasFactory;

    protected $table = 'krs_periods';

    protected $fillable = [
        'academic_year_id',
        'study_program_id',
        'name',
        'start_date',
        'end_date',
        'is_open',
        'auto_by_date',
        'allow_revision',
        'revision_end_date',
        'notes',
    ];

    protected $casts = [
        'start_date'        => 'datetime',
        'end_date'          => 'datetime',
        'revision_end_date' => 'datetime',
        'is_open'           => 'boolean',
        'auto_by_date'      => 'boolean',
        'allow_revision'    => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }

    /**
     * Cek apakah periode KRS saat ini sedang aktif / terbuka
     */
    public function isOpenNow(): bool
    {
        $now = Carbon::now();

        // Jika mode otomatis berdasarkan tanggal
        if ($this->auto_by_date) {
            return $now->between($this->start_date, $this->end_date);
        }

        // Jika mode manual
        return (bool) $this->is_open;
    }

    /**
     * Cek apakah periode revisi KRS (PRS) saat ini sedang aktif
     */
    public function isRevisionOpenNow(): bool
    {
        if (!$this->allow_revision || !$this->revision_end_date) {
            return false;
        }

        $now = Carbon::now();
        return $now->between($this->end_date, $this->revision_end_date);
    }
}
