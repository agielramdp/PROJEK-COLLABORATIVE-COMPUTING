<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'building_id',
        'code',
        'name',
        'floor',
        'capacity',
        'room_type',
        'facilities',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'floor'     => 'integer',
        'capacity'  => 'integer',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class, 'room_id');
    }

    public function examSchedules()
    {
        return $this->hasMany(ExamSchedule::class, 'room_id');
    }
}
