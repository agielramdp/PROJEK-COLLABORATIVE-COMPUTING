<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $fillable = ['user_id', 'file_name', 'successful_rows', 'failed_rows', 'status', 'processed_at', 'error_detail'];

    protected function casts(): array
    {
        return ['processed_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
