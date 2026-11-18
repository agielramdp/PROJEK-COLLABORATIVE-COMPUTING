<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicAdvisor extends Model
{
    use HasFactory;

    protected $table = 'academic_advisors';

    protected $fillable = [
        'lecturer_id',
        'student_id',
        'academic_year_id',
        'assigned_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'is_active'     => 'boolean',
    ];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}
