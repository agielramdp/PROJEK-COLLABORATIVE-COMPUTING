<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = ['user_id', 'study_program_id', 'faculty_id', 'nidn', 'nip', 'name', 'email', 'phone', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function studyProgram() { return $this->belongsTo(StudyProgram::class); }
    public function faculty() { return $this->belongsTo(Faculty::class); }
    public function classes() { return $this->belongsToMany(CourseClass::class, 'class_lecturers', 'lecturer_id', 'class_id')->withPivot('is_primary'); }
    public function academicAdvisees() { return $this->hasMany(AcademicAdvisor::class); }
}
