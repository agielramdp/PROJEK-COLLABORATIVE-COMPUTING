<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendarEvent extends Model
{
    use HasFactory;

    protected $table = 'academic_calendar_events';

    protected $fillable = [
        'academic_year_id',
        'title',
        'event_type',
        'start_date',
        'end_date',
        'description',
        'is_public',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_public'  => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}
