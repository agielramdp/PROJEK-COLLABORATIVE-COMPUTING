<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicAdvisor extends Model
{
    protected $fillable = ['student_id', 'lecturer_id', 'academic_year_id', 'assigned_at', 'status'];

    protected function casts(): array
    {
        return ['assigned_at' => 'date'];
    }

    public function student() { return $this->belongsTo(Student::class); }
    public function lecturer() { return $this->belongsTo(Lecturer::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
}
