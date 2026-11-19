<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $fillable = ['class_id', 'lecturer_id', 'room_id', 'academic_year_id', 'semester_id', 'day', 'start_time', 'end_time'];

    public function courseClass() { return $this->belongsTo(CourseClass::class, 'class_id'); }
    public function lecturer() { return $this->belongsTo(Lecturer::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
}
