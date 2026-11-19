<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    protected $fillable = ['code', 'name', 'faculty_id', 'education_level_id', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class);
    }

    public function classes()
    {
        return $this->hasMany(CourseClass::class);
    }
}
