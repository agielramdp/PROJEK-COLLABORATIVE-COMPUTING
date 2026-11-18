<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePrerequisite extends Model
{
    use HasFactory;

    protected $table = 'course_prerequisites';

    protected $fillable = [
        'course_id',
        'prerequisite_course_id',
        'minimum_grade',
        'prerequisite_type',
        'notes',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function prerequisiteCourse()
    {
        return $this->belongsTo(Course::class, 'prerequisite_course_id');
    }
}
