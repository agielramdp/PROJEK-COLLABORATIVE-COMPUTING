<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['code', 'name', 'credits', 'semester', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['credits' => 'integer', 'semester' => 'integer', 'is_active' => 'boolean'];
    }

    public function curriculums() { return $this->belongsToMany(Curriculum::class, 'curriculum_courses')->withPivot(['semester', 'is_required']); }
    public function prerequisites() { return $this->belongsToMany(Course::class, 'course_prerequisites', 'course_id', 'prerequisite_course_id'); }
    public function classes() { return $this->hasMany(CourseClass::class); }
}
