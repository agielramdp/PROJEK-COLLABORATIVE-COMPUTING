<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentStatusHistory extends Model
{
    protected $fillable = ['student_id', 'previous_status_id', 'new_status_id', 'academic_year_id', 'changed_by', 'changed_at', 'reason'];

    protected function casts(): array
    {
        return ['changed_at' => 'date'];
    }

    public function student() { return $this->belongsTo(Student::class); }
    public function previousStatus() { return $this->belongsTo(StudentStatus::class, 'previous_status_id'); }
    public function newStatus() { return $this->belongsTo(StudentStatus::class, 'new_status_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function changedBy() { return $this->belongsTo(User::class, 'changed_by'); }
}
