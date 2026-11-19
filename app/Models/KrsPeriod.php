<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KrsPeriod extends Model
{
    protected $fillable = ['academic_year_id', 'semester_id', 'start_at', 'end_at', 'is_open', 'description'];

    protected function casts(): array
    {
        return ['start_at' => 'datetime', 'end_at' => 'datetime', 'is_open' => 'boolean'];
    }

    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
}
