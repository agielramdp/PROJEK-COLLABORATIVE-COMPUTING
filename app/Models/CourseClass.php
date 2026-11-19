<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['course_id', 'curriculum_id', 'study_program_id', 'academic_year_id', 'semester_id', 'code', 'name', 'capacity', 'status'];

    protected function casts(): array
    {
        return ['capacity' => 'integer'];
    }

    public function course() { return $this->belongsTo(Course::class); }
    public function curriculum() { return $this->belongsTo(Curriculum::class); }
    public function studyProgram() { return $this->belongsTo(StudyProgram::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function lecturers() { return $this->belongsToMany(Lecturer::class, 'class_lecturers', 'class_id', 'lecturer_id')->withPivot('is_primary'); }
    public function schedules() { return $this->hasMany(ClassSchedule::class, 'class_id'); }
    public function exams() { return $this->hasMany(ExamSchedule::class, 'class_id'); }
}
