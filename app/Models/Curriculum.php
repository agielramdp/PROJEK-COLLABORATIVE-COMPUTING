<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $fillable = ['study_program_id', 'academic_year_id', 'code', 'name', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function studyProgram() { return $this->belongsTo(StudyProgram::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function courses() { return $this->belongsToMany(Course::class, 'curriculum_courses')->withPivot(['semester', 'is_required']); }
}
