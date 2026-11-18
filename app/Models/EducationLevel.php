<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;

    protected $table = 'education_levels';

    protected $fillable = [
        'code',
        'name',
        'degree_title',
        'standard_credits',
        'standard_semesters',
    ];

    public function studyPrograms()
    {
        return $this->hasMany(StudyProgram::class, 'education_level_id');
    }
}
