<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'activity_type', 'entity_name', 'entity_id', 'before_data', 'after_data', 'occurred_at', 'ip_address'];

    protected function casts(): array
    {
        return ['before_data' => 'array', 'after_data' => 'array', 'occurred_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
