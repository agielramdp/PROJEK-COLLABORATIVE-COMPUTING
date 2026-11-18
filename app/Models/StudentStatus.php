<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentStatus extends Model
{
    use HasFactory;

    protected $table = 'student_statuses';

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'status',
        'reason',
        'decree_number',
        'updated_by_user_id',
        'effective_date',
    ];

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }
}
