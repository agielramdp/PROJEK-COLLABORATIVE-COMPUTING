<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeSubmissionDeadline extends Model
{
    protected $fillable = ['academic_year_id', 'semester_id', 'course_id', 'class_id', 'unlocked_by', 'deadline_at', 'is_locked', 'notes'];

    protected function casts(): array
    {
        return ['deadline_at' => 'datetime', 'is_locked' => 'boolean'];
    }

    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function course() { return $this->belongsTo(Course::class); }
    public function courseClass() { return $this->belongsTo(CourseClass::class, 'class_id'); }
    public function unlockedBy() { return $this->belongsTo(User::class, 'unlocked_by'); }
}
