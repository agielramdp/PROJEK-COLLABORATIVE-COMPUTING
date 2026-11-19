<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLecturer extends Model
{
    use HasFactory;

    protected $table = 'class_lecturers';

    protected $fillable = [
        'course_class_id',
        'lecturer_id',
        'role',
        'teaching_load_sks',
    ];

    protected $casts = [
        'teaching_load_sks' => 'integer',
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}
