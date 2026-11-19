<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'study_program_id', 'academic_year_id', 'nim', 'name', 'email', 'phone', 'birth_date', 'birth_place', 'is_active'];

    protected function casts(): array
    {
        return ['birth_date' => 'date', 'is_active' => 'boolean'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function studyProgram() { return $this->belongsTo(StudyProgram::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function advisors() { return $this->hasMany(AcademicAdvisor::class); }
    public function statusHistories() { return $this->hasMany(StudentStatusHistory::class); }
}
