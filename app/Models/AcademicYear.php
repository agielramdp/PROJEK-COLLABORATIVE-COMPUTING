<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'year_name',
        'semester',
        'is_active',
        'start_date',
        'end_date',
        'description',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function krsPeriods()
    {
        return $this->hasMany(KrsPeriod::class, 'academic_year_id');
    }

    public function courseClasses()
    {
        return $this->hasMany(CourseClass::class, 'academic_year_id');
    }

    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }
}
