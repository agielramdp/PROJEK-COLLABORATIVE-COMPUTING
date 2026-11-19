<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['floor_id', 'code', 'name', 'capacity', 'room_type', 'is_active'];

    protected function casts(): array
    {
        return ['capacity' => 'integer', 'is_active' => 'boolean'];
    }

    public function floor() { return $this->belongsTo(Floor::class); }
    public function schedules() { return $this->hasMany(ClassSchedule::class); }
}
