<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'curriculum_id',
        'study_program_id',
        'code',
        'name',
        'name_en',
        'credits_theory',
        'credits_practical',
        'total_credits',
        'semester',
        'course_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'credits_theory'    => 'integer',
        'credits_practical' => 'integer',
        'total_credits'     => 'integer',
        'semester'          => 'integer',
        'is_active'         => 'boolean',
    ];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }

    public function prerequisites()
    {
        return $this->hasMany(CoursePrerequisite::class, 'course_id');
    }

    public function prerequisiteCourses()
    {
        return $this->belongsToMany(Course::class, 'course_prerequisites', 'course_id', 'prerequisite_course_id')
                    ->withPivot(['minimum_grade', 'prerequisite_type', 'notes']);
    }

    public function classes()
    {
        return $this->hasMany(CourseClass::class, 'course_id');
    }
}
