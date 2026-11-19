<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = ['code', 'name', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function studyPrograms()
    {
        return $this->hasMany(StudyProgram::class);
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }
}
