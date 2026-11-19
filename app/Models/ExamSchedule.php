<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $fillable = ['class_id', 'room_id', 'academic_year_id', 'semester_id', 'exam_type', 'exam_date', 'start_time', 'end_time'];

    protected function casts(): array
    {
        return ['exam_date' => 'date'];
    }

    public function courseClass() { return $this->belongsTo(CourseClass::class, 'class_id'); }
    public function room() { return $this->belongsTo(Room::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function supervisors() { return $this->belongsToMany(Lecturer::class, 'exam_supervisors', 'exam_schedule_id', 'lecturer_id'); }
}
