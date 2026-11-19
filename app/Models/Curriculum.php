<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curriculums';

    protected $fillable = [
        'study_program_id',
        'code',
        'name',
        'year_start',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'year_start' => 'integer',
    ];

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'curriculum_id');
    }
}
