<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyProgram extends Model
{
    use HasFactory;

    protected $table = 'study_programs';

    protected $fillable = [
        'faculty_id',
        'education_level_id',
        'code',
        'name',
        'head_of_program',
        'accreditation',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class, 'study_program_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'study_program_id');
    }
}
