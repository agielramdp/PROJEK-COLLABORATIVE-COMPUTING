<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $table = 'exam_schedules';

    protected $fillable = [
        'course_class_id',
        'exam_type',
        'exam_date',
        'start_time',
        'end_time',
        'room_id',
        'academic_year_id',
        'notes',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function invigilators()
    {
        return $this->hasMany(ExamInvigilator::class, 'exam_schedule_id');
    }
}
