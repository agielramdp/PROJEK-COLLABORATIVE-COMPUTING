<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClass extends Model
{
    use HasFactory;

    protected $table = 'course_classes';

    protected $fillable = [
        'course_id',
        'academic_year_id',
        'name',
        'quota',
        'current_enrolled',
        'is_active',
    ];

    protected $casts = [
        'quota'            => 'integer',
        'current_enrolled' => 'integer',
        'is_active'        => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function classLecturers()
    {
        return $this->hasMany(ClassLecturer::class, 'course_class_id');
    }

    public function lecturers()
    {
        return $this->belongsToMany(User::class, 'class_lecturers', 'course_class_id', 'lecturer_id')
                    ->withPivot(['role', 'teaching_load_sks']);
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'course_class_id');
    }

    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class, 'course_class_id');
    }

    public function gradeLock()
    {
        return $this->hasOne(GradeLock::class, 'course_class_id');
    }
}
