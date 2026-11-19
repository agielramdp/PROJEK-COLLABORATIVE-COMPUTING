<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamInvigilator extends Model
{
    use HasFactory;

    protected $table = 'exam_invigilators';

    protected $fillable = [
        'exam_schedule_id',
        'invigilator_id',
        'role',
        'attendance_status',
        'notes',
    ];

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class, 'exam_schedule_id');
    }

    public function invigilator()
    {
        return $this->belongsTo(User::class, 'invigilator_id');
    }
}
